<?php

namespace App\Http\Controllers;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Illuminate\Http\Request;


class GoogleSheetController extends Controller
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = config('sheets.spreadsheet_id');

        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/google/google-service.json'));
        $this->client->addScope(Sheets::SPREADSHEETS);

        $this->service = new Sheets($this->client);
    }

    // Dashboard view
    public function dashboard()
    {
        // Staff data
        $staffResp = $this->service->spreadsheets_values->get($this->spreadsheetId, 'Staff');
        $staffData = $staffResp->getValues() ?? [];
        $staffDataRows = array_slice($staffData, 1); // exclude header
        $totalStaff = count($staffDataRows);
        $activeStaff = count(array_filter($staffDataRows, fn($row) => isset($row[2]) && strtolower($row[2]) == 'active'));
        $inactiveStaff = $totalStaff - $activeStaff;

        // Vehicles data
        $vehResp = $this->service->spreadsheets_values->get($this->spreadsheetId, 'Vehicles');
        $vehicleData = $vehResp->getValues() ?? [];
        $vehicleDataRows = array_slice($vehicleData, 1); // exclude header
        $totalVehicles = count($vehicleDataRows);
        $activeVehicles = count(array_filter($vehicleDataRows, fn($row) => isset($row[2]) && strtolower($row[2]) == 'active'));
        $inactiveVehicles = $totalVehicles - $activeVehicles;

        return view('dashboard', compact(
            'totalStaff',
            'activeStaff',
            'inactiveStaff',
            'totalVehicles',
            'activeVehicles',
            'inactiveVehicles'
        ));
    }

    // Staff view (all rows, no Load More)
    public function staffView(Request $request)
    {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, 'Staff');
        $allRows = $response->getValues() ?? [];
        $dataRows = array_slice($allRows, 1); // exclude header

        $staff = $dataRows;

        return view('staff', compact('staff'));
    }

    // Vehicles view (all rows, no Load More)
    public function vehiclesView(Request $request)
    {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, 'Vehicles');
        $allRows = $response->getValues() ?? [];
        $dataRows = array_slice($allRows, 1); // exclude header

        $vehicles = $dataRows;
        $totalVehicles = count($dataRows);

        return view('vehicles', compact('vehicles', 'totalVehicles'));
    }

    // Inline update staff
    public function inlineUpdateStaff(Request $request, $rowIndex)
    {
        $this->inlineUpdate('Staff', $rowIndex, $request->col, $request->value);
        return response()->json(['success' => true]);
    }

    // Inline update vehicle
    public function inlineUpdateVehicle(Request $request, $rowIndex)
    {
        $this->inlineUpdate('Vehicles', $rowIndex, $request->col, $request->value);
        return response()->json(['success' => true]);
    }

    // Inline update helper
 protected function inlineUpdate($sheet, $rowIndex, $colLetter, $value)
{
    $range = "$sheet!{$colLetter}{$rowIndex}";

    $body = new ValueRange([
        'values' => [[$value]]
    ]);

    $params = ['valueInputOption' => 'RAW'];

    $this->service->spreadsheets_values->update(
        $this->spreadsheetId,
        $range,
        $body,
        $params
    );
}



    // Delete staff row
    public function deleteStaff($rowIndex)
    {
        $sheetId = $this->getSheetId('Staff');

        $requests = [
            new \Google\Service\Sheets\Request([
                'deleteDimension' => [
                    'range' => [
                        'sheetId' => $sheetId,
                        'dimension' => 'ROWS',
                        'startIndex' => $rowIndex - 1,
                        'endIndex' => $rowIndex
                    ]
                ]
            ])
        ];

        $batchRequest = new BatchUpdateSpreadsheetRequest(['requests' => $requests]);
        $this->service->spreadsheets->batchUpdate($this->spreadsheetId, $batchRequest);

        return response()->json(['success' => true]);
    }

    // Delete vehicle row
    public function deleteVehicle($rowIndex)
    {
        $sheetId = $this->getSheetId('Vehicles');

        $requests = [
            new \Google\Service\Sheets\Request([
                'deleteDimension' => [
                    'range' => [
                        'sheetId' => $sheetId,
                        'dimension' => 'ROWS',
                        'startIndex' => $rowIndex - 1,
                        'endIndex' => $rowIndex
                    ]
                ]
            ])
        ];

        $batchRequest = new BatchUpdateSpreadsheetRequest(['requests' => $requests]);
        $this->service->spreadsheets->batchUpdate($this->spreadsheetId, $batchRequest);

        return response()->json(['success' => true]);
    }

    // AJAX partial for staff rows
    public function staffAjax(Request $request)
    {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, 'Staff');
        $allRows = $response->getValues() ?? [];
        $dataRows = array_slice($allRows, 1); // exclude header

        $staffRows = array_filter($dataRows, fn($row) => isset($row[0]) && trim($row[0]) !== '');

        return view('partials.staff-rows', compact('staffRows'));
    }

    // Helper to get sheet ID by name
    protected function getSheetId($sheetName)
    {
        $spreadsheet = $this->service->spreadsheets->get($this->spreadsheetId);
        foreach ($spreadsheet->getSheets() as $sheet) {
            if ($sheet->getProperties()->getTitle() === $sheetName) {
                return $sheet->getProperties()->getSheetId();
            }
        }
        throw new \Exception("Sheet '{$sheetName}' not found.");
    }
}

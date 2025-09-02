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
        $ActiveStaff = count(array_filter($staffDataRows, function ($row) {
            // columns are zero-indexed → 10th col = index 9, 14th = 13, 18th = 17
            $columnsToCheck = [8];

            foreach ($columnsToCheck as $col) {
                if (isset($row[$col]) && strtolower(trim($row[$col])) === 'active') {
                    return true; // mark this row as expired
                }
            }

            return false;
        }));

        $expiredStaff = count(array_filter($staffDataRows, function ($row) {
            // columns are zero-indexed → 10th col = index 9, 14th = 13, 18th = 17
            $columnsToCheck = [8];

            foreach ($columnsToCheck as $col) {
                if (isset($row[$col]) && strtolower(trim($row[$col])) === 'expired') {
                    return true; // mark this row as expired
                }
            }

            return false;
        }));


        $timetorenewStaff = count(array_filter($staffDataRows, function ($row) {
            // columns are zero-indexed → 10th col = index 9, 14th = 13, 18th = 17
            $columnsToCheck = [8];

            foreach ($columnsToCheck as $col) {
                if (isset($row[$col]) && strtolower(trim($row[$col])) === 'time to renew') {
                    return true; // mark this row as expired
                }
            }

            return false;
        }));


        // Vehicles data
        $vehResp = $this->service->spreadsheets_values->get($this->spreadsheetId, 'Vehicles');
        $vehicleData = $vehResp->getValues() ?? [];
        $vehicleDataRows = array_slice($vehicleData, 1); // exclude header
        $totalVehicles = count($vehicleDataRows);

        $expiredVehicles = count(array_filter($vehicleDataRows, function ($row) {
            // columns are zero-indexed → 10th col = index 9, 14th = 13, 18th = 17
            $columnsToCheck = [10, 14, 18, 22, 26];

            foreach ($columnsToCheck as $col) {
                if (isset($row[$col]) && strtolower(trim($row[$col])) === 'expired') {
                    return true; // mark this row as expired
                }
            }

            return false;
        }));


        $ActiveVehicles = count(array_filter($vehicleDataRows, function ($row) {
            // columns are zero-indexed → 10th col = index 9, 14th = 13, 18th = 17
            $columnsToCheck = [10, 14, 18, 22, 26];

            foreach ($columnsToCheck as $col) {
                if (isset($row[$col]) && strtolower(trim($row[$col])) === 'active') {
                    return true; // mark this row as expired
                }
            }

            return false;
        }));

        $TimetorenewVehicles = count(array_filter($vehicleDataRows, function ($row) {
            // columns are zero-indexed → 10th col = index 9, 14th = 13, 18th = 17
            $columnsToCheck = [10, 14, 18, 22, 26];

            foreach ($columnsToCheck as $col) {
                if (isset($row[$col]) && strtolower(trim($row[$col])) === 'time to renew') {
                    return true; // mark this row as expired
                }
            }

            return false;
        }));


        $typeColumns = [
            'Gate Pass' => 10,
            'RC' => 14,
            'Insurance' => 18,
            'Pollution' => 22,
            'Tax' => 26,
        ];

        $vehicleStats = [];

        foreach ($typeColumns as $type => $colIndex) {
            $col = $colIndex; // convert 1-based to 0-based

            $expiredCount = count(array_filter($vehicleDataRows, fn($row) => isset($row[$col]) && strtolower(trim($row[$col])) === 'expired'));
            $activeCount = count(array_filter($vehicleDataRows, fn($row) => isset($row[$col]) && strtolower(trim($row[$col])) === 'active'));
            $expiringSoonCount = count(array_filter($vehicleDataRows, fn($row) => isset($row[$col]) && strtolower(trim($row[$col])) === 'time to renew'));

            $total = $expiredCount + $activeCount + $expiringSoonCount;

            $vehicleStats[] = [
                "type" => $type,
                "statusexpired" => $expiredCount,
                "statusactive" => $activeCount,
                "statusexpiringsoon" => $expiringSoonCount,
                "pctExpired" => $total ? round(($expiredCount / $total) * 100, 2) : 0,
                "pctExpiringSoon" => $total ? round(($expiringSoonCount / $total) * 100, 2) : 0,
                "pctActive" => $total ? round(($activeCount / $total) * 100, 2) : 0,
            ];
        }



        return view('dashboard', compact(
            'totalStaff',
            'ActiveStaff',
            'expiredStaff',
            'timetorenewStaff',
            'totalVehicles',
            'expiredVehicles',
            'ActiveVehicles',
            'TimetorenewVehicles',
            'vehicleStats'
        ));
    }

    // Staff view (all rows, no Load More)
    public function staffView(Request $request)
    {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, 'Staff');
        $allRows = $response->getValues() ?? [];
        $dataRows = array_slice($allRows, 3); // exclude header

        $staff = $dataRows;

        return view('staff', compact('staff'));
    }

    // Vehicles view (all rows, no Load More)
    public function vehiclesView(Request $request)
    {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, 'Vehicles');
        $allRows = $response->getValues() ?? [];
        $dataRows = array_slice($allRows, 3); // exclude header

        $vehicles = $dataRows;

        return view('vehicles', compact('vehicles'));
    }

}

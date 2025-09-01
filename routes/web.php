<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleSheetController;
use Google\Client;


Route::get('/', [GoogleSheetController::class, 'dashboard'])->name('dashboard');

Route::get('/staff', [GoogleSheetController::class, 'staffView'])->name('staff.view');
Route::post('/staff/inline-update/{rowIndex}', [GoogleSheetController::class, 'inlineUpdate']);

Route::delete('/staff/delete/{rowIndex}', [GoogleSheetController::class, 'deleteStaff']);
Route::get('/staff/ajax', [GoogleSheetController::class, 'staffAjax']);


Route::get('/vehicles', [GoogleSheetController::class, 'vehiclesView'])->name('vehicles.view');
Route::post('/vehicle/inline-update/{rowIndex}', [GoogleSheetController::class, 'inlineUpdateVehicle']);
Route::delete('/vehicle/delete/{rowIndex}', [GoogleSheetController::class, 'deleteVehicle']);
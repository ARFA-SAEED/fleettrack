<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleSheetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;



// Guest routes (not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => view('auth.login'))->name('login');
    Route::get('/register', fn() => view('auth.register'))->name('register');

    Route::post('/', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Logout route (authenticated users only)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');



Route::get('/dashboard', [GoogleSheetController::class, 'dashboard'])->name('dashboard');
// Admin password management
Route::get('/admin/password', [AuthController::class, 'editPassword'])->name('admin.password.edit');
Route::post('/admin/password', [AuthController::class, 'updatePassword'])->name('admin.password.update');

// Settings
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings/add-users', [SettingsController::class, 'addUsers'])->name('settings.addUsers');
Route::post('/settings/pause-user/{id}', [SettingsController::class, 'pauseUser'])->name('settings.pauseUser');
Route::get('/settings/edit-user/{id}', [SettingsController::class, 'editUser'])->name('settings.editUser');
Route::post('/settings/update-user/{id}', [SettingsController::class, 'updateUser'])->name('settings.updateUser');
Route::delete('/settings/delete-user/{id}', [SettingsController::class, 'deleteUser'])->name('settings.deleteUser');


// Vehicle manager routes
Route::get('/vehicles', [GoogleSheetController::class, 'vehiclesView'])->name('vehicles.view');


// Staff manager routes
Route::get('/staff', [GoogleSheetController::class, 'staffView'])->name('staff.view');



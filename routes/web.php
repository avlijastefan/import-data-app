<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ImportedDataController;
use App\Http\Controllers\ImportsController;
use App\Http\Controllers\ImportFileController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::middleware('permission:user-management')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('permissions', PermissionController::class);
    });

    Route::get('/imports', [ImportController::class, 'index'])->name('imports.index');
    Route::post('/imports', [ImportController::class, 'store'])->name('imports.store');
    Route::get('/imports/headers/{type}', [ImportController::class, 'headers'])->name('imports.headers');
    Route::get('/import-file/{type}', [ImportFileController::class, 'show']);

    Route::get('/imported-data/{type}', [ImportedDataController::class, 'show'])->name('imported-data.show');
    Route::delete('/imported-data/{type}/{id}', [ImportedDataController::class, 'destroy'])->name('imported-data.destroy');
    Route::get('/imported-data/{type}/export', [ImportedDataController::class, 'export'])->name('imported-data.export');
    Route::get('/imported-data/{type}/{id}/audit', [ImportedDataController::class, 'audit'])->name('imported-data.audit');

    Route::get('/imports-history', [ImportsController::class, 'index'])->name('imports-history.index');
    Route::get('/imports-history/{import}/logs', [ImportsController::class, 'logs'])->name('imports-history.logs');
});

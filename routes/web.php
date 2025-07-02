<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ImportWebController;
use App\Http\Controllers\InvoiceWebController;

Route::get('/', function () {
    return view('welcome');
});

// Admin routes (should be protected by auth middleware in real use)
Route::post('/api/imports', [ImportController::class, 'store']);
Route::put('/api/imports/{id}', [ImportController::class, 'update']);
Route::delete('/api/imports/{id}', [ImportController::class, 'destroy']);
Route::get('/api/imports', [ImportController::class, 'index']);

// Public route
Route::get('/public/imports', [ImportController::class, 'publicIndex']);

// Web interface routes (admin)
Route::get('/imports', [ImportWebController::class, 'index'])->name('imports.index');
Route::get('/imports/create', [ImportWebController::class, 'create'])->name('imports.create');
Route::post('/imports', [ImportWebController::class, 'store'])->name('imports.store');
Route::get('/imports/{import}/edit', [ImportWebController::class, 'edit'])->name('imports.edit');
Route::put('/imports/{import}', [ImportWebController::class, 'update'])->name('imports.update');
Route::delete('/imports/{import}', [ImportWebController::class, 'destroy'])->name('imports.destroy');

// Public web interface
Route::get('/public/imports', [ImportWebController::class, 'publicIndex'])->name('imports.public');

// Invoice details
Route::get('/invoices', [InvoiceWebController::class, 'index'])->name('invoices.index');
Route::get('/invoices/{invoice}', [InvoiceWebController::class, 'show'])->name('invoices.show');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

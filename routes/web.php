<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\Admin\PackageController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/track', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/track', [TrackingController::class, 'search'])->name('tracking.search');
Route::get('/track/{package}', [TrackingController::class, 'show'])->name('tracking.show');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/packages', [PackageController::class, 'index'])->name('admin.packages.index');
    Route::get('/packages/create', [PackageController::class, 'create'])->name('admin.packages.create');
    Route::post('/packages', [PackageController::class, 'store'])->name('admin.packages.store');
    Route::get('/packages/{package}', [PackageController::class, 'show'])->name('admin.packages.show');
    Route::get('/packages/{package}/edit', [PackageController::class, 'edit'])->name('admin.packages.edit');
    Route::put('/packages/{package}', [PackageController::class, 'update'])->name('admin.packages.update');
    Route::delete('/packages/{package}', [PackageController::class, 'destroy'])->name('admin.packages.destroy');
});

require __DIR__.'/auth.php';

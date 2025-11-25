<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmSelectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckFarmContext;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Farm Selection
    Route::get('/farms/select', [FarmSelectionController::class, 'index'])->name('farms.selection');
    Route::post('/farms/{farm}/select', [FarmSelectionController::class, 'select'])->name('farms.select');
    
    // Farm Management (CRUD)
    Route::resource('farms', \App\Http\Controllers\Admin\FarmController::class);
    
    // User Management (CRUD)
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    
    // Harvest Management (CRUD)
    Route::resource('harvests', \App\Http\Controllers\Admin\HarvestController::class);
});

require __DIR__.'/auth.php';

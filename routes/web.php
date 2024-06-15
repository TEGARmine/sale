<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('auth/login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth/register');
    })->name('register');

    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login')->name('login');
        Route::post('/register', 'register')->name('register');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('sales')->name('sales.')->group(function () {
        Route::controller(SalesController::class)->group(function () {
            Route::get('/input', 'index')->name('input');
            Route::post('/store', 'store')->name('store');

            Route::put('/edit/{id}', 'update')->name('update');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::delete('/delete/{id}', 'delete')->name('delete');
        });

        Route::controller(ReportController::class)->group(function () {
            Route::get('/export', 'export')->name('export');
            Route::get('/reports', 'index')->name('reports');
        });
    });
});

<?php

use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Support\Facades\Route;

// rute admin: middleware auth dari Orang 1 ditambah nanti kalau sudah merge
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});

// rute pembayaran pelanggan ditambah di Tahap 2
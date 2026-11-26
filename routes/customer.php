
<?php

use App\Http\Controllers\Customer\MenuController;
use Illuminate\Support\Facades\Route;

// Halaman menu pelanggan melalui QR Code (Orang 2)
Route::prefix('menu/{qrToken}')
    ->where(['qrToken' => '[A-Za-z0-9]+', 'id' => '[0-9]+'])
    ->name('customer.menu.')
    ->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('index');
        Route::get('item/{id}', [MenuController::class, 'show'])->name('show');
    });

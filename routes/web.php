
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

Route::get('/', function () {
    return view('welcome');
});

// Halaman menu pelanggan melalui QR Code
Route::get('/menu/{token}', [MenuController::class, 'index'])
    ->name('customer.menu');

// Detail menu pelanggan
Route::get('/menu/{token}/detail/{id}', [MenuController::class, 'show'])
    ->name('customer.menu.detail');

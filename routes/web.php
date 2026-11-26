
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route menu pelanggan (Orang 2)
require __DIR__ . '/customer.php';

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request, $token)
    {
        // Memeriksa token dan status meja
        $table = RestaurantTable::where('qr_token', $token)
            ->where('is_active', true)
            ->first();

        if (!$table) {
            return response()->view('errors.404', [], 404);
        }

        // Mengambil kategori
        $categories = Category::orderBy('name')->get();

        // Mengambil menu yang tersedia
        
// Mengambil pilihan pengurutan
$sort = $request->query('sort');

$sortColumn = match ($sort) {
    'price_asc', 'price_desc' => 'price',
    default => 'name',
};

$sortDirection = match ($sort) {
    'price_asc' => 'asc',
    'price_desc' => 'desc',
    default => 'asc',
};

// Mengambil menu yang tersedia
$menus = MenuItem::with('category')
    ->where('is_available', true)
    ->when($request->filled('category'), function ($query) use ($request) {
        $query->where('category_id', $request->category);
    })
    ->when($request->filled('search'), function ($query) use ($request) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
    })
    ->orderBy($sortColumn, $sortDirection)
    ->get();


        return view('customer.menu', compact(
            'table',
            'categories',
            'menus'
        ));
    }

    public function show($token, $id)
    {
        // Memeriksa token dan status meja
        $table = RestaurantTable::where('qr_token', $token)
            ->where('is_active', true)
            ->first();

        if (!$table) {
            return response()->view('errors.404', [], 404);
        }

        // Memeriksa menu dan ketersediaannya
        $menu = MenuItem::with('category')
            ->where('is_available', true)
            ->where('id', $id)
            ->first();

        if (!$menu) {
            return response()->view('errors.404', [], 404);
        }

        return view('customer.detail', compact('table', 'menu'));
    }
}

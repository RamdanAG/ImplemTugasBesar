<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request, string $qrToken)
    {
        $table = $this->cariMeja($qrToken);

        if (!$table) {
            return response()->view('customer.menu.invalid', [], 404);
        }

        $this->simpanMejaDiSession($table);

        $kata = trim((string) $request->query('q', ''));
        $kategoriId = $request->integer('kategori');

        $query = MenuItem::with('category');

        if ($kata !== '') {
            $query->where(function ($w) use ($kata) {
                $w->where('name', 'like', '%' . $kata . '%')
                  ->orWhere('description', 'like', '%' . $kata . '%');
            });
        }

        if ($kategoriId > 0) {
            $query->where('category_id', $kategoriId);
        }

        $menus = $query
            ->orderByDesc('is_available')
            ->orderBy('name')
            ->get();

        $categories = Category::orderBy('name')->get();

        return view('customer.menu.index', compact(
            'table',
            'menus',
            'categories',
            'kata',
            'kategoriId'
        ));
    }

    public function show(string $qrToken, int $id)
    {
        $table = $this->cariMeja($qrToken);

        if (!$table) {
            return response()->view('customer.menu.invalid', [], 404);
        }

        $this->simpanMejaDiSession($table);

        $menu = MenuItem::with('category')->find($id);

        if (!$menu) {
            return response()->view(
                'customer.menu.not-found',
                compact('table'),
                404
            );
        }

        return view('customer.menu.show', compact('table', 'menu'));
    }

    private function cariMeja(string $qrToken): ?RestaurantTable
    {
        return RestaurantTable::where('qr_token', $qrToken)
            ->where('is_active', true)
            ->first();
    }

    private function simpanMejaDiSession(RestaurantTable $table): void
    {
        session([
            'table_id' => $table->id,
            'qr_token' => $table->qr_token,
        ]);
    }
}

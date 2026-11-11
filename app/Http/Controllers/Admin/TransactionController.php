<?php

namespace App\Http\Controllers\Admin;

use App\Constants\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
            'status' => ['nullable', Rule::in(PaymentStatus::all())],
        ]);

        $query = Payment::with('order.table');

        // filter tanggal: pakai paid_at, kalau kosong pakai created_at
        if ($request->filled('dari')) {
            $awal = Carbon::parse($request->dari)->startOfDay();
            $query->whereRaw('COALESCE(payments.paid_at, payments.created_at) >= ?', [$awal]);
        }

        if ($request->filled('sampai')) {
            $akhir = Carbon::parse($request->sampai)->endOfDay();
            $query->whereRaw('COALESCE(payments.paid_at, payments.created_at) <= ?', [$akhir]);
        }

        // filter status pembayaran
        if ($request->filled('status')) {
            $query->where('payments.status', $request->status);
        }

        // ringkasan dihitung dari filter yang sama
        $jumlah = (clone $query)->count();
        $totalPenjualan = (clone $query)->penjualan()->sum('amount');

        $payments = $query
            ->orderByRaw('COALESCE(payments.paid_at, payments.created_at) DESC')
            ->orderByDesc('payments.id')
            ->simplePaginate(15)
            ->withQueryString();

        return view('transaction.index', compact('payments', 'jumlah', 'totalPenjualan'));
    }
}
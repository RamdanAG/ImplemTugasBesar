<?php

namespace App\Http\Controllers\Admin;

use App\Constants\PaymentMethod;
use App\Constants\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Support\CsvExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->queryTerfilter($request);

        // ringkasan dihitung dari filter yang sama
        $jumlah = (clone $query)->count();
        $totalPenjualan = (clone $query)->penjualan()->sum('amount');

        $payments = $this->urutkan($query)
            ->simplePaginate(15)
            ->withQueryString();

        return view('transaction.index', compact('payments', 'jumlah', 'totalPenjualan'));
    }

    public function export(Request $request)
    {
        $query = $this->queryTerfilter($request);

        $totalPenjualan = (clone $query)->penjualan()->sum('amount');
        $payments = $this->urutkan($query)->get();

        $baris = [
            ['Waktu', 'No. Pesanan', 'Meja', 'Metode', 'Nominal', 'Status bayar', 'Status pesanan'],
        ];

        foreach ($payments as $p) {
            $baris[] = [
                ($p->paid_at ?? $p->created_at)->format('Y-m-d H:i'),
                $p->order->order_number,
                $p->order->table->table_number,
                PaymentMethod::label($p->payment_method),
                (int) $p->amount,
                $p->status,
                $p->order->status,
            ];
        }

        // baris penutup: total penjualan berhasil dari filter yang sama
        $baris[] = [];
        $baris[] = ['Total penjualan berhasil', '', '', '', (int) $totalPenjualan];

        return CsvExport::unduh('transaksi-' . now()->format('Ymd-His') . '.csv', $baris);
    }

    // validasi dan filter dipakai bersama oleh halaman dan CSV
    private function queryTerfilter(Request $request)
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

        if ($request->filled('status')) {
            $query->where('payments.status', $request->status);
        }

        return $query;
    }

    private function urutkan($query)
    {
        return $query
            ->orderByRaw('COALESCE(payments.paid_at, payments.created_at) DESC')
            ->orderByDesc('payments.id');
    }
}
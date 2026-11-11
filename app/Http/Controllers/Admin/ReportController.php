<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'jenis' => ['nullable', Rule::in(['harian', 'bulanan'])],
            'tanggal' => ['nullable', 'date'],
            'bulan' => ['nullable', 'date_format:Y-m'],
        ]);

        $jenis = $request->input('jenis', 'harian');

        // tentukan batas awal dan akhir periode
        if ($jenis === 'harian') {
            $tanggal = Carbon::parse($request->input('tanggal', now()->toDateString()));
            $awal = $tanggal->copy()->startOfDay();
            $akhir = $tanggal->copy()->endOfDay();
            $judul = $awal->copy()->locale('id')->translatedFormat('d F Y');
        } else {
            $bulan = Carbon::createFromFormat('Y-m-d', $request->input('bulan', now()->format('Y-m')) . '-01');
            $awal = $bulan->copy()->startOfMonth();
            $akhir = $bulan->copy()->endOfMonth();
            $judul = $awal->copy()->locale('id')->translatedFormat('F Y');
        }

        // query dasar: semua penjualan berhasil di periode ini
        $penjualan = Payment::penjualan()
            ->whereBetween('payments.paid_at', [$awal, $akhir]);

        $totalPenjualan = (clone $penjualan)->sum('amount');
        $jumlahPesanan = (clone $penjualan)->distinct()->count('order_id');

        // 5 menu paling banyak dipesan pada periode ini
        $menuTerlaris = OrderItem::selectRaw('menu_name, SUM(quantity) as jumlah, SUM(subtotal) as omzet')
            ->whereIn('order_id', (clone $penjualan)->select('order_id'))
            ->groupBy('menu_name')
            ->orderByDesc('jumlah')
            ->orderBy('menu_name')
            ->limit(5)
            ->get();

        // harian: daftar transaksi hari itu, bulanan: rekap per hari
        $daftar = collect();
        $perHari = collect();

        if ($jenis === 'harian') {
            $daftar = (clone $penjualan)->with('order')->orderBy('paid_at')->get();
        } else {
            $perHari = (clone $penjualan)
                ->selectRaw('DATE(paid_at) as tanggal, COUNT(DISTINCT order_id) as jumlah_pesanan, SUM(amount) as total')
                ->groupByRaw('DATE(paid_at)')
                ->orderBy('tanggal')
                ->get();
        }

        return view('report.index', [
            'jenis' => $jenis,
            'judul' => $judul,
            'nilaiTanggal' => $awal->toDateString(),
            'nilaiBulan' => $awal->format('Y-m'),
            'totalPenjualan' => $totalPenjualan,
            'jumlahPesanan' => $jumlahPesanan,
            'menuTerlaris' => $menuTerlaris,
            'daftar' => $daftar,
            'perHari' => $perHari,
        ]);
    }
}
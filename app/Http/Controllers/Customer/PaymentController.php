<?php

namespace App\Http\Controllers\Customer;

use App\Constants\OrderStatus;
use App\Constants\PaymentMethod;
use App\Constants\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function show(string $orderNumber)
    {
        // cari pesanan lewat nomor pesanan, kalau tidak ada tampil 404
        $order = Order::with(['items', 'table'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // pesanan dianggap sudah dibayar kalau ada pembayaran berstatus paid
        $sudahDibayar = $order->payments()
            ->where('status', PaymentStatus::PAID)
            ->exists();

        // pesanan yang dibatalkan atau sudah dibayar tidak boleh dibayar lagi
        $bisaDibayar = !$sudahDibayar && $order->status !== OrderStatus::CANCELLED;

        return view('payment.show', compact('order', 'sudahDibayar', 'bisaDibayar'));
    }

    public function store(Request $request, string $orderNumber)
    {
        // sementara baru validasi metode, simpan pembayaran dibuat di Tahap 3
        $request->validate([
            'payment_method' => ['required', Rule::in(PaymentMethod::all())],
        ]);

        return back()->with('info', 'Metode dipilih. Proses simpan pembayaran dibuat di Tahap 3.');
    }
}
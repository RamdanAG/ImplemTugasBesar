<?php

namespace App\Http\Controllers\Customer;

use App\Constants\OrderStatus;
use App\Constants\PaymentMethod;
use App\Constants\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Payment;
use App\Models\StatusHistory;
use Illuminate\Support\Facades\DB;

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
    $data = $request->validate([
        'payment_method' => ['required', Rule::in(PaymentMethod::all())],
        'hasil' => ['nullable', Rule::in(['berhasil', 'gagal'])],
    ]);

    // kalau tombol hasil tidak terkirim, anggap berhasil
    $hasil = $data['hasil'] ?? 'berhasil';

    // semua proses di dalam transaksi, kalau ada yang error semuanya dibatalkan
    $balasan = DB::transaction(function () use ($orderNumber, $data, $hasil) {

        // kunci baris pesanan supaya request kedua harus menunggu
        $order = Order::where('order_number', $orderNumber)
            ->lockForUpdate()
            ->firstOrFail();

        if ($order->status === OrderStatus::CANCELLED) {
            return ['error', 'Pesanan ini sudah dibatalkan, tidak bisa dibayar.'];
        }

        $sudahDibayar = $order->payments()
            ->where('status', PaymentStatus::PAID)
            ->exists();

        if ($sudahDibayar) {
            return ['error', 'Pesanan ini sudah dibayar, tidak perlu bayar lagi.'];
        }

        // nominal dari database, bukan dari browser
        if ($order->total_amount <= 0) {
            return ['error', 'Total pesanan tidak valid.'];
        }

        // pakai ulang pembayaran pending kalau ada, kalau tidak buat baru
        $payment = $order->payments()
            ->where('status', PaymentStatus::PENDING)
            ->latest('id')
            ->first();

        if (!$payment) {
            $payment = new Payment(['order_id' => $order->id]);
        }

        $payment->payment_method = $data['payment_method'];
        $payment->amount = $order->total_amount;

        if ($hasil === 'gagal') {
            $payment->status = PaymentStatus::FAILED;
            $payment->save();

            return ['info', 'Pembayaran gagal (simulasi). Silakan coba lagi.'];
        }

        $payment->status = PaymentStatus::PAID;
        $payment->paid_at = now();
        $payment->save();

        // pembayaran berhasil, pesanan pending jadi confirmed
        if ($order->status === OrderStatus::PENDING) {
            $order->update(['status' => OrderStatus::CONFIRMED]);

            StatusHistory::create([
                'order_id' => $order->id,
                'old_status' => OrderStatus::PENDING,
                'new_status' => OrderStatus::CONFIRMED,
                'note' => 'Pembayaran berhasil',
            ]);
        }

        return ['info', 'Pembayaran berhasil (simulasi). Terima kasih!'];
    });

    // redirect supaya refresh browser tidak mengirim ulang form
    $redirect = redirect()->route('payment.show', $orderNumber);

    if ($balasan[0] === 'error') {
        return $redirect->withErrors(['payment' => $balasan[1]]);
    }

    return $redirect->with('info', $balasan[1]);
}
}
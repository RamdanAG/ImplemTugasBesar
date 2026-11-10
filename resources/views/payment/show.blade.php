@extends('layouts.payment-customer-temp')

@section('title', 'Pembayaran')

@section('content')
    <h1>Pembayaran</h1>
    <p class="muted">
        Pesanan {{ $order->order_number }} &middot; Meja {{ $order->table->table_number }}
    </p>

    <h2>Ringkasan pesanan</h2>
    <table>
        @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->menu_name }} x{{ $item->quantity }}</td>
                <td class="angka">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr class="total">
            <td>Total</td>
            <td class="angka">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if ($sudahDibayar)
        <h2>Status</h2>
        <div class="pesan">Pesanan ini sudah dibayar.</div>
    @elseif (! $bisaDibayar)
        <h2>Status</h2>
        <div class="pesan">Pesanan ini sudah dibatalkan, jadi tidak bisa dibayar.</div>
    @else
        <h2>Pilih metode pembayaran</h2>
        <p class="muted" style="margin-bottom: 8px;">Pembayaran ini hanya simulasi, tidak ada uang sungguhan.</p>

        <form method="POST" action="{{ route('payment.store', $order->order_number) }}">
            @csrf

            <label class="opsi">
                <input type="radio" name="payment_method" value="qris" required> QRIS
            </label>
            <div class="instruksi" id="instruksi-qris">
                Scan kode QR di bawah lewat aplikasi pembayaran (simulasi).
                <div class="kotak-qr">QR simulasi</div>
                Setelah itu tekan tombol Bayar.
            </div>

            <label class="opsi">
                <input type="radio" name="payment_method" value="bank_transfer"> Transfer bank
            </label>
            <div class="instruksi" id="instruksi-bank_transfer">
                Transfer ke rekening berikut (simulasi):<br>
                <strong>1234 5678 90</strong> a.n. Menora<br>
                Nominal: Rp {{ number_format($order->total_amount, 0, ',', '.') }}<br>
                Setelah itu tekan tombol Bayar.
            </div>

            <button type="submit">Bayar Rp {{ number_format($order->total_amount, 0, ',', '.') }}</button>
        </form>

        <script>
            // tampilkan instruksi sesuai metode yang dipilih
            var radios = document.querySelectorAll('input[name="payment_method"]');
            radios.forEach(function (radio) {
                radio.addEventListener('change', function () {
                    document.querySelectorAll('.instruksi').forEach(function (kotak) {
                        kotak.style.display = 'none';
                    });
                    document.getElementById('instruksi-' + radio.value).style.display = 'block';
                });
            });
        </script>
    @endif
@endsection
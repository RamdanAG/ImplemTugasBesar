@extends('layouts.payment-temp')

@section('title', 'Transaksi')

@section('content')
    <h1>Riwayat Transaksi</h1>

    @if ($errors->any())
        <div class="pesan">{{ $errors->first() }}</div>
    @endif

    <form method="GET" action="{{ route('admin.transactions.index') }}" class="filter">
        <div>
            <label for="dari">Dari tanggal</label>
            <input type="date" id="dari" name="dari" value="{{ request('dari') }}">
        </div>
        <div>
            <label for="sampai">Sampai tanggal</label>
            <input type="date" id="sampai" name="sampai" value="{{ request('sampai') }}">
        </div>
        <div>
            <label for="status">Status pembayaran</label>
            <select id="status" name="status">
                <option value="">Semua</option>
                @foreach (\App\Constants\PaymentStatus::all() as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit">Terapkan</button>
        <a href="{{ route('admin.transactions.index') }}">Reset</a>
    </form>

    <div class="ringkasan">
        <div>
            <span>Jumlah transaksi</span>
            <strong>{{ $jumlah }}</strong>
        </div>
        <div>
            <span>Total penjualan berhasil</span>
            <strong>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>No. Pesanan</th>
                <th>Meja</th>
                <th>Metode</th>
                <th class="angka">Nominal</th>
                <th>Status bayar</th>
                <th>Status pesanan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $p)
                <tr>
                    <td>{{ ($p->paid_at ?? $p->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $p->order->order_number }}</td>
                    <td>{{ $p->order->table->table_number }}</td>
                    <td>{{ \App\Constants\PaymentMethod::label($p->payment_method) }}</td>
                    <td class="angka">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                    <td>{{ $p->status }}</td>
                    <td>{{ $p->order->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada transaksi untuk filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="nav-halaman">
        <div>
            @if ($payments->previousPageUrl())
                <a href="{{ $payments->previousPageUrl() }}">&laquo; Sebelumnya</a>
            @endif
        </div>
        <div>Halaman {{ $payments->currentPage() }}</div>
        <div>
            @if ($payments->nextPageUrl())
                <a href="{{ $payments->nextPageUrl() }}">Berikutnya &raquo;</a>
            @endif
        </div>
    </div>
@endsection
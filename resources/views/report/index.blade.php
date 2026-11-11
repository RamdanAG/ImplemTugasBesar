@extends('layouts.payment-temp')

@section('title', 'Laporan')

@section('content')
    <h1>Laporan Penjualan</h1>

    @if ($errors->any())
        <div class="pesan">{{ $errors->first() }}</div>
    @endif

    <p>
        <a href="{{ route('admin.reports.index', ['jenis' => 'harian']) }}"
           class="tab {{ $jenis === 'harian' ? 'aktif' : '' }}">Harian</a>
        <a href="{{ route('admin.reports.index', ['jenis' => 'bulanan']) }}"
           class="tab {{ $jenis === 'bulanan' ? 'aktif' : '' }}">Bulanan</a>
    </p>

    <form method="GET" action="{{ route('admin.reports.index') }}" class="filter">
        <input type="hidden" name="jenis" value="{{ $jenis }}">

        @if ($jenis === 'harian')
            <div>
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" value="{{ $nilaiTanggal }}">
            </div>
        @else
            <div>
                <label for="bulan">Bulan</label>
                <input type="month" id="bulan" name="bulan" value="{{ $nilaiBulan }}">
            </div>
        @endif

        <button type="submit">Tampilkan</button>
    </form>

    <h2>Periode: {{ $judul }}</h2>

    <div class="ringkasan">
        <div>
            <span>Jumlah pesanan</span>
            <strong>{{ $jumlahPesanan }}</strong>
        </div>
        <div>
            <span>Total penjualan</span>
            <strong>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</strong>
        </div>
    </div>

    <h2>Menu terlaris</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Menu</th>
                <th class="angka">Terjual</th>
                <th class="angka">Omzet</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($menuTerlaris as $i => $menu)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $menu->menu_name }}</td>
                    <td class="angka">{{ $menu->jumlah }}</td>
                    <td class="angka">Rp {{ number_format($menu->omzet, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Belum ada penjualan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($jenis === 'harian')
        <h2>Transaksi hari ini</h2>
        <table>
            <thead>
                <tr>
                    <th>Jam</th>
                    <th>No. Pesanan</th>
                    <th>Metode</th>
                    <th class="angka">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftar as $p)
                    <tr>
                        <td>{{ $p->paid_at->format('H:i') }}</td>
                        <td>{{ $p->order->order_number }}</td>
                        <td>{{ \App\Constants\PaymentMethod::label($p->payment_method) }}</td>
                        <td class="angka">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Tidak ada transaksi berhasil pada tanggal ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <h2>Rekap per hari</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th class="angka">Jumlah pesanan</th>
                    <th class="angka">Total penjualan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perHari as $hari)
                    <tr>
                        <td>{{ \Illuminate\Support\Carbon::parse($hari->tanggal)->format('d/m/Y') }}</td>
                        <td class="angka">{{ $hari->jumlah_pesanan }}</td>
                        <td class="angka">Rp {{ number_format($hari->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Tidak ada penjualan pada bulan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <p class="catatan">
        Penjualan dihitung dari pembayaran berstatus paid pada pesanan yang tidak dibatalkan,
        berdasarkan waktu bayar. Pembayaran gagal, pending, dan pesanan dibatalkan tidak dihitung.
    </p>
@endsection
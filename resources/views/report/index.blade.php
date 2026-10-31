@extends('layouts.payment-temp')

@section('title', 'Laporan')

@section('content')
    <h1>Laporan Penjualan</h1>
    <p>Total pesanan di database: {{ \App\Models\Order::count() }}</p>
@endsection
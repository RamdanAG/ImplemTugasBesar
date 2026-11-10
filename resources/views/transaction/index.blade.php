@extends('layouts.payment-temp')

@section('title', 'Transaksi')

@section('content')
    <h1>Riwayat Transaksi</h1>
    <p>Total pembayaran di database: {{ \App\Models\Payment::count() }}</p>
@endsection
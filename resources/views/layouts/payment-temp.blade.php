<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Menora') - Menora</title>
    <style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: system-ui, sans-serif;
    color: #111;
    background: #fff;
}

header {
    padding: 14px 24px;
    border-bottom: 1px solid #111;
    display: flex;
    gap: 24px;
    align-items: center;
}

header strong {
    font-size: 18px;
}

header a {
    color: #111;
    text-decoration: none;
}

header a:hover {
    text-decoration: underline;
}

main {
    max-width: 1000px;
    margin: 0 auto;
    padding: 24px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    text-align: left;
    padding: 8px 10px;
    border-bottom: 1px solid #ddd;
}

th {
    border-bottom: 1px solid #111;
}

th.angka,
td.angka {
    text-align: right;
    white-space: nowrap;
}

.filter {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: flex-end;
    margin-bottom: 16px;
}

.filter label {
    display: block;
    font-size: 13px;
    margin-bottom: 4px;
}

.filter input,
.filter select {
    padding: 6px 8px;
    border: 1px solid #111;
    background: #fff;
    font: inherit;
}

.filter button {
    padding: 7px 14px;
    border: 1px solid #111;
    background: #111;
    color: #fff;
    font: inherit;
    cursor: pointer;
}

.filter a {
    color: #111;
    padding: 7px 4px;
}

.ringkasan {
    border: 1px solid #111;
    padding: 10px 12px;
    margin-bottom: 16px;
    display: flex;
    gap: 32px;
    flex-wrap: wrap;
}

.ringkasan span {
    display: block;
    font-size: 13px;
    color: #666;
}

.pesan {
    border: 1px solid #111;
    background: #eee;
    padding: 10px 12px;
    margin-bottom: 16px;
    font-size: 14px;
}

.nav-halaman {
    display: flex;
    justify-content: space-between;
    margin-top: 16px;
}

.nav-halaman a {
    color: #111;
}

h2 { font-size: 16px; margin: 24px 0 8px; }
.tab { display: inline-block; padding: 6px 14px; border: 1px solid #111; color: #111; text-decoration: none; margin-right: 4px; }
.tab.aktif { background: #111; color: #fff; }
.catatan { font-size: 13px; color: #666; margin-top: 24px; }

    </style>
</head>
<body>
    <header>
        <strong>Menora</strong>
        <a href="{{ route('admin.transactions.index') }}">Transaksi</a>
        <a href="{{ route('admin.reports.index') }}">Laporan</a>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>
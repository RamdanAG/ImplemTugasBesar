<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Menora') - Menora</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, sans-serif; color: #111; background: #fff; }
        header { padding: 14px 24px; border-bottom: 1px solid #111; display: flex; gap: 24px; align-items: center; }
        header strong { font-size: 18px; }
        header a { color: #111; text-decoration: none; }
        header a:hover { text-decoration: underline; }
        main { max-width: 1000px; margin: 0 auto; padding: 24px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px 10px; border-bottom: 1px solid #ddd; }
        th { border-bottom: 1px solid #111; }
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
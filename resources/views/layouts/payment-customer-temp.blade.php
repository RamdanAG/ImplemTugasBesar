<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Pembayaran') - Menora</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, sans-serif; color: #111; background: #fff; }
        header { padding: 14px 20px; border-bottom: 1px solid #111; }
        main { max-width: 480px; margin: 0 auto; padding: 20px; }
        h1 { font-size: 22px; margin: 0 0 4px; }
        h2 { font-size: 16px; margin: 24px 0 8px; }
        .muted { color: #666; font-size: 14px; margin: 0; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        td { padding: 6px 0; border-bottom: 1px solid #ddd; }
        td.angka { text-align: right; white-space: nowrap; }
        .total td { border-bottom: 1px solid #111; font-weight: 600; }
        .opsi { display: block; border: 1px solid #111; padding: 12px; margin-bottom: 8px; cursor: pointer; }
        .instruksi { border: 1px dashed #111; padding: 12px; margin: 8px 0 16px; font-size: 14px; display: none; }
        .kotak-qr { width: 140px; height: 140px; border: 1px solid #111; display: flex; align-items: center; justify-content: center; margin: 8px 0; font-size: 13px; }
        button { width: 100%; padding: 12px; border: 1px solid #111; background: #111; color: #fff; font-size: 16px; cursor: pointer; }
        .pesan { border: 1px solid #111; padding: 10px 12px; margin-bottom: 16px; font-size: 14px; }
        .error { border: 1px solid #111; background: #eee; }
    </style>
</head>
<body>
    <header><strong>Menora</strong></header>
    <main>
        @if (session('info'))
            <div class="pesan">{{ session('info') }}</div>
        @endif

        @if ($errors->any())
            <div class="pesan error">{{ $errors->first() }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
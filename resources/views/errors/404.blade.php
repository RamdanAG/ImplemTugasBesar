
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - MENORA</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .error-container {
            background: white;
            width: 100%;
            max-width: 450px;
            padding: 40px 25px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .error-code {
            font-size: 65px;
            font-weight: bold;
            color: #263b2b;
            margin: 0;
        }

        h2 {
            margin-bottom: 10px;
        }

        p {
            color: #666;
            line-height: 1.6;
        }

        .back-button {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 22px;
            background: #263b2b;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>

<body>

<div class="error-container">

    <p class="error-code">404</p>

    <h2>Halaman Tidak Ditemukan</h2>

    <p>
        Maaf, halaman atau menu yang kamu cari
        tidak ditemukan atau sudah tidak tersedia.
    </p>

    <p>
        Silakan periksa kembali QR Code atau hubungi
        petugas restoran.
    </p>

    <a href="{{ url('/') }}" class="back-button">
        Kembali ke Beranda
    </a>

</div>

</body>
</html>

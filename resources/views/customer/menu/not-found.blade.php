
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Tidak Ditemukan - Menora</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 30px 20px;
            border-radius: 12px;
            text-align: center;
        }

        .icon {
            font-size: 55px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 12px;
            color: #c0392b;
        }

        p {
            color: #666;
            line-height: 1.6;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #222;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="icon">🍽️</div>

        <h1>Menu Tidak Ditemukan</h1>

        <p>
            Maaf, menu yang Anda cari tidak ditemukan
            atau sudah tidak tersedia dalam daftar menu.
        </p>

        <a href="{{ url('/menu/' . $table->qr_token) }}">
            Kembali ke Daftar Menu
        </a>

    </div>
</body>
</html>

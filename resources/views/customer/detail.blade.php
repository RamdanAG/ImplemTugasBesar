
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Menu - MENORA</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f5f5;
            color: #333;
        }

        header {
            background: #263b2b;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .detail-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .detail-card img {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
        }

        .detail-info {
            padding: 25px;
        }

        .price {
            color: #263b2b;
            font-size: 22px;
            font-weight: bold;
        }

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background: #263b2b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .cart-button {
            width: 100%;
            padding: 14px;
            margin-top: 15px;
            background: #263b2b;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .cart-info {
            font-size: 13px;
            color: #777;
            text-align: center;
        }

        @media (max-width: 600px) {
            header {
            padding: 15px 10px;
        }

        .container {
            margin: 15px auto;
            padding: 0 12px;
        }

        .detail-info {
            padding: 18px;
         }

        .detail-card img {
             max-height: 250px;
         }

        .price {
            font-size: 20px;
    }
}
    </style>
</head>

<body>

<header>
    <h1>MENORA</h1>
    <p>Detail Menu</p>
    <p>Meja: {{ $table->table_number }}</p>
</header>

<div class="container">

    <a href="{{ route('customer.menu', $table->qr_token) }}"
       class="back-button">
        Kembali ke Menu
    </a>

    <div class="detail-card">

        @if ($menu->image)
            <img
                src="{{ asset('storage/' . $menu->image) }}"
                alt="{{ $menu->name }}"
            >
        @else
            <div style="height:200px; display:flex; align-items:center; justify-content:center; background:#e5e5e5;">
                Tidak ada gambar
            </div>
        @endif

        <div class="detail-info">

            <h2>{{ $menu->name }}</h2>

            <p>
                Kategori:
                {{ $menu->category->name ?? 'Tanpa Kategori' }}
            </p>

            <p>{{ $menu->description ?? 'Tidak ada deskripsi menu.' }}</p>

            <p class="price">
                Rp {{ number_format($menu->price, 0, ',', '.') }}
            </p>

            <button type="button" class="cart-button" disabled>
                Tambah ke Keranjang
            </button>

            <p class="cart-info">
                Fitur keranjang akan tersedia setelah integrasi pemesanan.
            </p>

        </div>

    </div>

</div>

</body>
</html>

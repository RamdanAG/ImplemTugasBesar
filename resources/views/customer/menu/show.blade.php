
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Menu - Menora</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 480px;
            min-height: 100vh;
            margin: auto;
            padding: 20px;
            background: white;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #333;
            text-decoration: none;
        }

        .menu-image,
        .placeholder {
            width: 100%;
            height: 230px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eee;
            color: #777;
        }

        h1 {
            font-size: 25px;
            margin-bottom: 10px;
        }

        .category {
            color: #777;
            margin-bottom: 15px;
        }

        .description {
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .price {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .available {
            color: green;
            margin-bottom: 15px;
        }

        .unavailable {
            color: red;
            margin-bottom: 15px;
        }

        .cart-form {
            margin-top: 20px;
        }

        .cart-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .cart-form button,
        .disabled-button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #222;
            color: white;
            cursor: pointer;
        }

        .disabled-button {
            background: #aaa;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="container">

        <a class="back"
           href="{{ url('/menu/' . $table->qr_token) }}">
            &larr; Kembali ke Menu
        </a>

        @if ($menu->image)
            <img
                class="menu-image"
                src="{{ asset('storage/' . $menu->image) }}"
                alt="{{ $menu->name }}"
            >
        @else
            <div class="placeholder">
                Gambar belum tersedia
            </div>
        @endif

        <h1>{{ $menu->name }}</h1>

        <p class="category">
            Kategori: {{ $menu->category->name ?? 'Tanpa kategori' }}
        </p>

        <p class="description">
            {{ $menu->description }}
        </p>

        <p class="price">
            Rp {{ number_format($menu->price, 0, ',', '.') }}
        </p>

        @if ($menu->is_available)
            <p class="available">Menu tersedia</p>

            <form class="cart-form"
                  method="POST"
                  action="{{ url('/cart/add') }}">

                @csrf

                <input
                    type="hidden"
                    name="menu_item_id"
                    value="{{ $menu->id }}"
                >

                <label for="quantity">Jumlah pesanan:</label>

                <input
                    type="number"
                    id="quantity"
                    name="quantity"
                    value="1"
                    min="1"
                    max="99"
                    required
                >

                <button type="submit">
                    Tambah ke Keranjang
                </button>
            </form>
        @else
            <p class="unavailable">
                Menu sedang tidak tersedia.
            </p>

            <button type="button" class="disabled-button" disabled>
                Menu Tidak Tersedia
            </button>
        @endif

    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Restoran MENORA</title>

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
            padding: 25px 20px;
            text-align: center;
        }

        .container {
            max-width: 1100px;
            margin: 25px auto;
            padding: 0 20px;
        }

        .filter {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        .filter input,
        .filter select,
        .filter button {
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .filter input {
            flex: 1;
            min-width: 180px;
        }

        .filter button {
            background: #263b2b;
            color: white;
            cursor: pointer;
        }

        .menu-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .menu-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .menu-card img {
            width: 100%;
            height: 170px;
            object-fit: cover;
        }

        .menu-info {
            padding: 15px;
        }

        .price {
            color: #263b2b;
            font-weight: bold;
        }

        .detail-button {
            display: inline-block;
            margin-top: 10px;
            padding: 9px 14px;
            background: #263b2b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

       
.empty {
    background: white;
    padding: 25px;
    text-align: center;
    border-radius: 8px;
}

/* Tampilan untuk perangkat HP */
@media (max-width: 600px) {
    header {
        padding: 20px 10px;
    }

    header h1 {
        font-size: 25px;
    }

    .container {
        margin: 15px auto;
        padding: 0 12px;
    }

    .filter {
        flex-direction: column;
    }

    .filter input,
    .filter select,
    .filter button,
    .filter a {
        width: 100%;
    }

    .menu-list {
        grid-template-columns: 1fr;
    }

    .menu-card img {
        height: 200px;
    }
}

    </style>
</head>

<body>

<header>
    <h1>MENORA</h1>
    <p>Selamat Datang di Menu Restoran Kami</p>
    <p>Meja: {{ $table->table_number }}</p>
</header>

<div class="container">

    <h2>Daftar Menu</h2>

    <form action="{{ route('customer.menu', $table->qr_token) }}"
          method="GET"
          class="filter">

        <input
            type="text"
            name="search"
            placeholder="Cari nama menu..."
            value="{{ request('search') }}"
        >

        <select name="category">
            <option value="">Semua Kategori</option>

            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <!-- Pilihan pengurutan harga -->
        <select name="sort">
             <option value="">Urutkan berdasarkan nama</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
            Harga termurah
        </option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
             Harga termahal
    </option>
</select>        

        <button type="submit">Cari Menu</button>
        <a href="{{ route('customer.menu', $table->qr_token) }}"
             style="padding: 11px; background: #777; color: white;
            text-decoration: none; border-radius: 6px;">
            Reset
        </a>
    </form>

    @if ($menus->count() > 0)

        <div class="menu-list">

            @foreach ($menus as $menu)
                <div class="menu-card">

                    @if ($menu->image)
                        <img
                            src="{{ asset('storage/' . $menu->image) }}"
                            alt="{{ $menu->name }}"
                        >
                    @else
                        <div style="height:170px; display:flex; align-items:center; justify-content:center; background:#e5e5e5;">
                            Tidak ada gambar
                        </div>
                    @endif

                    <div class="menu-info">
                        <h3>{{ $menu->name }}</h3>

                        <p>
                            {{ $menu->category->name ?? 'Tanpa Kategori' }}
                        </p>

                        <p>{{ $menu->description }}</p>

                        <p class="price">
                            Rp {{ number_format($menu->price, 0, ',', '.') }}
                        </p>

                        <a
                            href="{{ route('customer.menu.detail', [
                                'token' => $table->qr_token,
                                'id' => $menu->id
                            ]) }}"
                            class="detail-button">
                            Lihat Detail
                        </a>
                    </div>

                </div>
            @endforeach

        </div>

    @else
    <div class="empty">
        <h3>Menu Belum Tersedia</h3>

        @if (request('search') || request('category'))
            <p>
                Maaf, menu yang kamu cari tidak ditemukan.
                Silakan coba kata kunci atau kategori lainnya.
            </p>

            <a href="{{ route('customer.menu', $table->qr_token) }}"
               class="detail-button">
                Lihat Semua Menu
            </a>
        @else
            <p>
                Maaf, saat ini belum ada menu yang tersedia.
                Silakan kembali lagi nanti.
            </p>
        @endif
    </div>
@endif

</div>

</body>
</html>

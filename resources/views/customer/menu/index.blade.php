
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Menora</title>

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
            margin: auto;
            min-height: 100vh;
            background: white;
            padding: 20px;
        }

        .header {
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 25px;
            color: #222;
            margin-bottom: 8px;
        }

        .table-info {
            background: #f0f0f0;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .search {
            margin-bottom: 15px;
        }

        .search input,
        .search select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .search button {
            width: 100%;
            padding: 12px;
            background: #222;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .section-title {
            font-size: 19px;
            margin: 20px 0 15px;
        }

        .menu-card {
            border: 1px solid #eee;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .menu-card img,
        .placeholder {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eee;
            color: #777;
        }

        .menu-content {
            padding: 15px;
        }

        .menu-content h3 {
            margin-bottom: 7px;
        }

        .description {
            font-size: 14px;
            color: #777;
            margin-bottom: 10px;
        }

        .price {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .available {
            color: green;
            font-size: 13px;
        }

        .unavailable {
            color: red;
            font-size: 13px;
        }

        .detail-link {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 15px;
            background: #222;
            color: white;
            text-decoration: none;
            border-radius: 7px;
            font-size: 13px;
        }

        .empty {
            text-align: center;
            padding: 30px 10px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="header">
            <h1>Menu Menora</h1>
            <p>Silakan pilih menu favorit Anda</p>
        </div>

        <div class="table-info">
            <strong>Nomor Meja:</strong>
            {{ $table->table_number }}
        </div>

        <form class="search" method="GET"
              action="{{ url('/menu/' . $table->qr_token) }}">

            <input
                type="text"
                name="q"
                placeholder="Cari nama atau deskripsi menu..."
                value="{{ $kata }}"
            >

            <select name="kategori">
                <option value="">Semua Kategori</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ $kategoriId == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Cari Menu</button>
        </form>

        <h2 class="section-title">Daftar Menu</h2>

        @forelse ($menus as $menu)
            <div class="menu-card">

                @if ($menu->image)
                    <img
                        src="{{ asset('storage/' . $menu->image) }}"
                        alt="{{ $menu->name }}"
                    >
                @else
                    <div class="placeholder">
                        Gambar belum tersedia
                    </div>
                @endif

                <div class="menu-content">
                    <h3>{{ $menu->name }}</h3>

                    <p class="description">
                        {{ $menu->description }}
                    </p>

                    <p class="price">
                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                    </p>

                    @if ($menu->is_available)
                        <p class="available">Tersedia</p>
                    @else
                        <p class="unavailable">Tidak tersedia</p>
                    @endif

                    <a
                        class="detail-link"
                        href="{{ url('/menu/' . $table->qr_token . '/item/' . $menu->id) }}"
                    >
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="empty">
                <p>Menu tidak ditemukan.</p>
                <p>Silakan coba kata kunci atau kategori lain.</p>
            </div>
        @endforelse

    </div>
</body>
</html>

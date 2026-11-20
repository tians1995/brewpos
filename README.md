# BREWPOS — Fresh Laravel 12 + React + Inertia

Project source untuk aplikasi POS coffee shop. Tidak menyertakan `vendor/` dan `node_modules/`; keduanya dibuat melalui instalasi dependency.

## Kebutuhan
- PHP 8.2+
- Composer 2
- Node.js 20+
- MySQL 8+

## Instalasi
1. Extract ZIP.
2. Buat database MySQL bernama `brewpos`.
3. Salin `.env.example` menjadi `.env`, lalu sesuaikan konfigurasi database.
4. Jalankan:

```bash
composer install
npm install
php artisan key:generate
php artisan migrate --seed
npm run build
```

5. Jalankan aplikasi:

```bash
php artisan serve
```

Buka http://127.0.0.1:8000

## Akun demo
- Admin: `admin@brewpos.test` / `password123`
- Kasir: `kasir@brewpos.test` / `password123`

## Fitur
- Login/logout dan role admin/kasir
- Dashboard ringkasan penjualan
- Master kategori dan produk (tambah, edit, hapus)
- POS keranjang, qty, pembayaran tunai, kembalian
- Transaksi dan detail transaksi
- Stok otomatis berkurang saat checkout

Catatan: source ini sengaja menggunakan autentikasi session Laravel sederhana agar instalasi tidak bergantung pada starter kit eksternal.

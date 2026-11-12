# StockMart 

Sistem manajemen inventaris berbasis web untuk mengelola barang, stok, supplier, dan transaksi barang masuk/keluar, dibangun dengan **Laravel 13**.

Proyek ini dikerjakan sebagai Tugas Kelompok mata kuliah *Pengembangan Sistem Informasi Berbasis Web Lanjut*, Program Studi Sistem Informasi.

## Deskripsi

Pencatatan stok secara manual rawan menimbulkan selisih data dan menyulitkan pelacakan barang. StockMart menyediakan pencatatan barang, penerimaan dan pengeluaran barang, peringatan stok menipis, serta laporan, dengan hak akses yang dibedakan berdasarkan peran pengguna.

## Fitur Utama

- **Dashboard**: total jenis barang, total stok, jumlah barang hampir habis, jumlah transaksi masuk/keluar, dan grafik transaksi persediaan.
- **Manajemen barang**: CRUD barang (kode, nama, kategori, satuan, harga beli/jual, stok minimum, foto), pencarian, dan filter.
- **Manajemen kategori dan supplier**: CRUD kategori dan supplier beserta informasi kontak.
- **Barang masuk**: input penerimaan dari supplier, banyak barang sekaligus, unggah bukti penerimaan, stok bertambah otomatis.
- **Barang keluar**: input pengeluaran dengan alasan, validasi ketersediaan stok, stok berkurang otomatis.
- **Monitoring stok**: stok tersedia, peringatan stok menipis, barang habis, dan riwayat pergerakan stok.
- **Laporan**: laporan stok, barang masuk, dan barang keluar dengan filter periode, serta cetak/export.
- **Manajemen pengguna**: login/logout, CRUD pengguna, profil, role, dan pembatasan akses.
- **Activity log**: pencatatan aktivitas penting pengguna (khusus Admin).
- **REST API**: endpoint produk, stok, dan transaksi dengan autentikasi token Sanctum.

## Hak Akses per Role

| Fitur | Admin | Kepala Toko | Petugas Gudang |
|---|---|---|---|
| Dashboard | Lihat | Lihat | Lihat |
| Data barang, kategori, supplier | CRUD | Lihat | Lihat |
| Barang masuk & keluar | Lihat | Lihat | CRUD |
| Monitoring stok | Lihat | Lihat | Lihat |
| Laporan | Lihat | Lihat | - |
| Manajemen pengguna | CRUD | - | - |
| Activity log | Lihat | - | - |

## Teknologi yang Digunakan

- PHP 8.3 atau lebih baru
- Laravel 13
- Blade (templating) dan Laravel Breeze (autentikasi)
- SQLite (bawaan) atau MySQL/MariaDB
- Laravel Sanctum (autentikasi API)
- Vite dan npm (aset front-end)
- Chart.js (grafik dashboard)
- Git dan GitHub (kolaborasi)

## Konsep Laravel yang Diterapkan

Routing dan resource controller, Eloquent ORM beserta relasi, migration dan seeder, Blade (layout, komponen, stack), Form Request validation, autentikasi, middleware role, Gate/Policy, database transaction, file storage, REST API, dan feature test.

## Struktur Database

| Tabel | Keterangan |
|---|---|
| `users` | Pengguna beserta `role` (`admin`, `kepala_toko`, `petugas_gudang`) |
| `categories` | Kategori barang |
| `suppliers` | Data supplier |
| `products` | Data barang, harga, stok, stok minimum, foto |
| `stock_ins`, `stock_in_items` | Transaksi barang masuk dan rincian barangnya |
| `stock_outs`, `stock_out_items` | Transaksi barang keluar dan rincian barangnya |
| `stock_movements` | Riwayat pergerakan stok |
| `activity_logs` | Catatan aktivitas pengguna |

Relasi utama: Category 1-N Product, Supplier 1-N StockIn, StockIn 1-N StockInItem N-1 Product, StockOut 1-N StockOutItem N-1 Product, User 1-N StockIn/StockOut/ActivityLog.

## Instalasi dan Menjalankan Aplikasi

**Prasyarat:** PHP 8.3+ (dengan ekstensi `mbstring`, `fileinfo`, `openssl`, `curl`, `zip`, `gd`, dan `pdo_sqlite` atau `pdo_mysql`), Composer, Node.js, dan Git.

```bash
# 1. Clone repository
git clone https://github.com/Ridd-dsgn/stockmart.git
cd stockmart

# 2. Pasang dependensi
composer install
npm install

# 3. Siapkan konfigurasi
cp .env.example .env
php artisan key:generate
```

**4. Siapkan database.** Dengan SQLite (bawaan), buat file databasenya:

```bash
# Linux / macOS / Git Bash
touch database/database.sqlite
# Windows CMD
type nul > database\database.sqlite
```

Jika memakai MySQL, ubah `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env`, lalu buat database kosongnya.

```bash
# 5. Jalankan migrasi dan isi data awal
php artisan migrate --seed

# 6. Tautkan folder penyimpanan (untuk foto barang dan bukti penerimaan)
php artisan storage:link

# 7. Jalankan aplikasi (dua terminal)
npm run dev
php artisan serve
```

Buka `http://127.0.0.1:8000` di browser.

## Akun Demo

Dibuat otomatis oleh seeder. Semua akun berpassword `password`.

| Role | Email |
|---|---|
| Admin | `admin@stockmart.test` |
| Kepala Toko | `kepala@stockmart.test` |
| Petugas Gudang | `gudang@stockmart.test` |

## REST API

Autentikasi memakai Laravel Sanctum. Dapatkan token lewat `POST /api/login`, lalu kirim di setiap request berikutnya dengan header `Authorization: Bearer <token>` dan `Accept: application/json`.

| Method | Endpoint | Keterangan |
|---|---|---|
| POST | `/api/login` | Login dengan `email` dan `password`, mengembalikan token |
| GET | `/api/me` | Data pengguna yang sedang login |
| POST | `/api/logout` | Mencabut token yang sedang dipakai |
| GET | `/api/products` | Daftar produk (parameter opsional: `q`, `category_id`, `page`) |
| GET | `/api/products/{id}` | Detail produk |
| GET | `/api/stocks` | Stok saat ini (parameter opsional: `status=habis` atau `status=menipis`) |
| GET | `/api/stock-movements` | Riwayat pergerakan stok (parameter opsional: `product_id`) |
| GET | `/api/transactions` | Daftar transaksi (parameter `type=in` atau `type=out`, bawaan `in`) |

Contoh:

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"email":"admin@stockmart.test","password":"password"}'

curl http://127.0.0.1:8000/api/products \
  -H "Accept: application/json" -H "Authorization: Bearer <token>"
```

## Pengujian

```bash
php artisan test
```

## Tim Pengembang

| Nama | GitHub | Tanggung jawab |
|---|---|---|
| Nashwa | @nashwaisn-ux |  Migration, model, seeder, autentikasi, role dan hak akses (middleware, Gate), layout Blade, manajemen pengguna, profil, activity log, REST API Sanctum, feature test |
| Farid | @Ridd-dsgn | Setup proyek dan repository, CRUD kategori, supplier, dan barang (upload foto, pencarian, filter) |
| Gosyen | @GosyenTampubolon | Barang masuk, barang keluar, monitoring stok, riwayat pergerakan stok |
| Michael | @MichaeL-PanjaitaN | Dashboard dan grafik, laporan dengan filter periode, cetak/export laporan |

## Alur Kolaborasi Git

- `main`: versi stabil dan final.
- `develop`: gabungan semua fitur yang sudah selesai.
- `feature/*`: satu branch per fitur, digabung ke `develop` lewat Pull Request dan di-review anggota lain.
- Pesan commit memakai awalan `feat:`, `fix:`, `test:`, `docs:`, `style:`, atau `chore:`.
- File `.env` tidak di-commit. Gunakan `.env.example` sebagai acuan.

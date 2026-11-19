# StockMart

Sistem manajemen inventaris berbasis web untuk mengelola barang, stok, supplier, dan transaksi barang masuk/keluar, dibangun dengan **Laravel 13**.

Proyek ini dikerjakan sebagai Tugas Kelompok mata kuliah *Pengembangan Sistem Informasi Berbasis Web Lanjut*, Program Studi Sistem Informasi.

## Daftar Isi

- [Deskripsi](#deskripsi)
- [Fitur Utama](#fitur-utama)
- [Hak Akses per Role](#hak-akses-per-role)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Konsep Laravel yang Diterapkan](#konsep-laravel-yang-diterapkan)
- [Struktur Database](#struktur-database)
- [Instalasi dan Menjalankan Aplikasi](#instalasi-dan-menjalankan-aplikasi)
- [Akun Demo](#akun-demo)
- [REST API](#rest-api)
- [Pengujian](#pengujian)
- [Tim Pengembang](#tim-pengembang)
- [Alur Kolaborasi Git](#alur-kolaborasi-git)

## Deskripsi

Pencatatan stok secara manual rawan menimbulkan selisih data dan menyulitkan pelacakan barang. StockMart menyediakan pencatatan barang, penerimaan dan pengeluaran barang, peringatan stok menipis, serta laporan, dengan hak akses yang dibedakan berdasarkan peran pengguna.

## Fitur Utama

- **Dashboard**: total barang, jumlah barang yang mencapai stok minimum, jumlah transaksi barang masuk dan keluar, daftar barang yang perlu diperhatikan, serta notifikasi stok menipis dan transaksi terbaru.
- **Manajemen barang**: CRUD barang (kode, nama, kategori, satuan, harga beli/jual, stok minimum, foto), pencarian, dan filter.
- **Manajemen kategori dan supplier**: CRUD kategori dan supplier beserta informasi kontak.
- **Barang masuk**: input penerimaan dari supplier, banyak barang sekaligus, unggah bukti penerimaan, stok bertambah otomatis.
- **Barang keluar**: input pengeluaran dengan alasan, validasi ketersediaan stok, stok berkurang otomatis.
- **Monitoring stok**: stok tersedia, peringatan stok menipis, barang habis, dan riwayat pergerakan stok.
- **Laporan**: rekap transaksi barang masuk dan barang keluar dengan filter periode (tanggal mulai dan akhir), serta cetak laporan yang dapat disimpan sebagai PDF lewat dialog cetak browser.
- **Manajemen pengguna**: login/logout, CRUD pengguna, profil, role, dan pembatasan akses.
- **Activity log**: pencatatan aktivitas penting pengguna (khusus Admin).
- **Halaman hak akses**: ringkasan hak akses setiap role (khusus Admin).
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
- Blade (templating), Tailwind CSS, dan Laravel Breeze (autentikasi)
- MySQL (database aplikasi)
- SQLite in-memory (hanya untuk pengujian otomatis)
- Laravel Sanctum (autentikasi API)
- Vite dan npm (aset front-end)
- Git dan GitHub (kolaborasi)

## Konsep Laravel yang Diterapkan

Routing dan resource controller, Eloquent ORM beserta relasi, migration dan seeder, Blade (layout, komponen, stack), Form Request validation, autentikasi, middleware role, Gate, database transaction, file storage, REST API, dan feature test.

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

**Prasyarat:**

- PHP 8.3 atau lebih baru (cek dengan `php -v`) dengan ekstensi `mbstring`, `fileinfo`, `openssl`, `curl`, `zip`, `gd`, `pdo_mysql`, dan `pdo_sqlite` (untuk pengujian)
- Composer
- Node.js dan npm
- Git
- MySQL Server (bisa memakai XAMPP, Laragon, atau instalasi MySQL biasa)

> Pengguna Windows yang punya lebih dari satu versi PHP (misalnya XAMPP dan Laragon): pastikan `php -v` menampilkan versi 8.3 atau lebih baru. Perintah `where php` menunjukkan PHP mana yang terbaca lebih dulu.

### 1. Clone repository dan pasang dependensi

```bash
git clone https://github.com/Ridd-dsgn/stockmart.git
cd stockmart
composer install
npm install
```

### 2. Siapkan konfigurasi

```bash
# Linux / macOS / Git Bash
cp .env.example .env

# Windows CMD
copy .env.example .env
```

```bash
php artisan key:generate
```

### 3. Siapkan database MySQL

Pastikan MySQL Server sudah berjalan, lalu buat database kosong bernama `stockmart`. Bisa lewat phpMyAdmin, atau lewat terminal:

```bash
mysql -u root -p -e "CREATE DATABASE stockmart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 4. Atur koneksi database di file `.env`

Sesuaikan dengan konfigurasi MySQL di komputer masing-masing:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stockmart
DB_USERNAME=root
DB_PASSWORD=
```

> Jika memakai XAMPP atau Laragon, user `root` biasanya tanpa password, sehingga `DB_PASSWORD` dibiarkan kosong.

### 5. Jalankan migration dan seeder

Membuat tabel beserta data awal (termasuk akun demo dan data barang contoh):

```bash
php artisan migrate --seed
php artisan storage:link
```

### 6. Jalankan aplikasi

Gunakan dua terminal:

```bash
# Terminal 1: server Laravel
php artisan serve

# Terminal 2: aset front-end
npm run dev
```

Aplikasi dapat diakses di `http://127.0.0.1:8000`.

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

**Contoh (Linux / macOS / Git Bash):**

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"email":"admin@stockmart.test","password":"password"}'

curl http://127.0.0.1:8000/api/products \
  -H "Accept: application/json" -H "Authorization: Bearer <token>"
```

**Contoh (Windows CMD):** tulis dalam satu baris dan gunakan tanda kutip ganda dengan escape.

```cmd
curl -X POST http://127.0.0.1:8000/api/login -H "Accept: application/json" -H "Content-Type: application/json" -d "{\"email\":\"admin@stockmart.test\",\"password\":\"password\"}"

curl http://127.0.0.1:8000/api/products -H "Accept: application/json" -H "Authorization: Bearer <token>"
```

## Pengujian

```bash
php artisan test
```

Pengujian otomatis memakai database SQLite di memori (diatur di `phpunit.xml`), sehingga tidak menyentuh data MySQL aplikasi dan membutuhkan ekstensi `pdo_sqlite`.

## Tim Pengembang

| Nama | GitHub | Tanggung jawab |
|---|---|---|
| Nashwa | [@nashwaisn-ux](https://github.com/nashwaisn-ux) | Setup proyek Laravel 13, migration, model, seeder, autentikasi, role dan hak akses (middleware, Gate), layout Blade, manajemen pengguna, profil, activity log, REST API Sanctum, feature test |
| Farid | [@Ridd-dsgn](https://github.com/Ridd-dsgn) | Membuat repository GitHub dan mengelola kolaborasi, CRUD kategori, supplier, dan barang (upload foto, pencarian, filter) |
| Gosyen | [@GosyenTampubolon](https://github.com/GosyenTampubolon) | Barang masuk, barang keluar, monitoring stok, riwayat pergerakan stok |
| Michael | [@MichaeL-PanjaitaN](https://github.com/MichaeL-PanjaitaN) | Dashboard, laporan dengan filter periode, cetak laporan |

## Alur Kolaborasi Git

- `main`: versi stabil dan final.
- `develop`: gabungan semua fitur yang sudah selesai.
- `feature/*`: satu branch per fitur, digabung ke `develop` lewat Pull Request dan di-review anggota lain.
- Pesan commit memakai awalan `feat:`, `fix:`, `test:`, `docs:`, `style:`, atau `chore:`.
- File `.env` tidak di-commit. Gunakan `.env.example` sebagai acuan.

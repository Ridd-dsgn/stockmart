<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessRightController extends Controller
{
    public function index()
    {
        $permissions = [
            [
                'name' => 'Dashboard',
                'admin' => 'Lihat',
                'kepala_toko' => 'Lihat',
                'petugas_gudang' => 'Lihat',
            ],
            [
                'name' => 'Barang',
                'admin' => 'Tambah, Edit, Hapus, Lihat',
                'kepala_toko' => 'Lihat',
                'petugas_gudang' => 'Lihat',
            ],
            [
                'name' => 'Kategori',
                'admin' => 'Tambah, Edit, Hapus, Lihat',
                'kepala_toko' => 'Lihat',
                'petugas_gudang' => 'Lihat',
            ],
            [
                'name' => 'Supplier',
                'admin' => 'Tambah, Edit, Hapus, Lihat',
                'kepala_toko' => 'Lihat',
                'petugas_gudang' => 'Lihat',
            ],
            [
                'name' => 'Barang Masuk',
                'admin' => 'Tambah, Lihat',
                'kepala_toko' => 'Lihat',
                'petugas_gudang' => 'Tambah, Lihat',
            ],
            [
                'name' => 'Barang Keluar',
                'admin' => 'Tambah, Lihat',
                'kepala_toko' => 'Lihat',
                'petugas_gudang' => 'Tambah, Lihat',
            ],
            [
                'name' => 'Monitoring Stok',
                'admin' => 'Lihat',
                'kepala_toko' => 'Lihat',
                'petugas_gudang' => 'Lihat',
            ],
            [
                'name' => 'Laporan',
                'admin' => 'Lihat',
                'kepala_toko' => 'Lihat',
                'petugas_gudang' => '-',
            ],
            [
                'name' => 'Manajemen Pengguna',
                'admin' => 'Tambah, Edit, Hapus, Lihat',
                'kepala_toko' => 'Tidak ada akses',
                'petugas_gudang' => 'Tidak ada akses',
            ],
            [
                'name' => 'Activity Log',
                'admin' => 'Lihat',
                'kepala_toko' => 'Tidak ada akses',
                'petugas_gudang' => 'Tidak ada akses',
            ],
        ];

        return view('access-rights.index', compact('permissions'));
    }
}

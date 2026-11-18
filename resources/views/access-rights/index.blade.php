
@extends('layouts.main')

@section('title', 'Hak Akses Pengguna')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Hak Akses Pengguna</h1>
    <p class="mt-1 text-sm text-gray-500">
        Daftar hak akses berdasarkan masing-masing role pengguna.
    </p>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
    <div class="rounded-xl border border-blue-200 bg-blue-50 p-5">
        <h2 class="text-lg font-bold text-blue-800">Admin</h2>
        <p class="mt-2 text-sm text-gray-600">
            Memiliki akses penuh untuk mengelola sistem.
        </p>
    </div>

    <div class="rounded-xl border border-green-200 bg-green-50 p-5">
        <h2 class="text-lg font-bold text-green-800">Kepala Toko</h2>
        <p class="mt-2 text-sm text-gray-600">
            Dapat melihat informasi stok dan laporan.
        </p>
    </div>

    <div class="rounded-xl border border-orange-200 bg-orange-50 p-5">
        <h2 class="text-lg font-bold text-orange-800">Petugas Gudang</h2>
        <p class="mt-2 text-sm text-gray-600">
            Dapat melihat stok dan mengelola barang masuk.
        </p>
    </div>
</div>

<div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b p-5">
        <h2 class="font-semibold text-gray-800">Rincian Hak Akses</h2>
    </div>

    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-gray-700">
            <tr>
                <th class="p-4">Menu / Aktivitas</th>
                <th class="p-4 text-center">Admin</th>
                <th class="p-4 text-center">Kepala Toko</th>
                <th class="p-4 text-center">Petugas Gudang</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($permissions as $permission)
                <tr>
                    <td class="p-4 font-medium text-gray-700">
                        {{ $permission['name'] }}
                    </td>

                    
@foreach (['admin', 'kepala_toko', 'petugas_gudang'] as $role)
    <td class="p-4 text-center">
        @php
            $access = $permission[$role] ?? 'Tidak ada akses';
        @endphp

        @if ($access === 'Tidak ada akses')
            <span class="text-gray-400">—</span>
        @else
            <span class="inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                {{ $access }}
            </span>
        @endif
    </td>
@endforeach

                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<p class="mt-4 text-xs text-gray-500">
    Catatan: Halaman ini menampilkan konfigurasi akses berdasarkan route aplikasi.
    Hak akses sebenarnya tetap ditentukan oleh middleware dan aturan pada controller.
</p>
@endsection

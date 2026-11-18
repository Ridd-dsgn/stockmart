
@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">
                Selamat datang kembali, {{ auth()->user()->name }}!
                Berikut ringkasan persediaan StockMart.
            </p>
        </div>

        <div class="text-sm text-gray-500">
            <span class="font-medium text-blue-600">●</span>
            Sistem Manajemen Stok
        </div>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Total Barang</p>
                    <h2 class="text-3xl font-bold text-gray-800 mt-2">
                        {{ \App\Models\Product::count() }}
                    </h2>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl">
                    📦
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-4">Jumlah produk terdaftar</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Total Kategori</p>
                    <h2 class="text-3xl font-bold text-gray-800 mt-2">
                        {{ \App\Models\Category::count() }}
                    </h2>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl">
                    🗂️
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-4">Kategori produk</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Total Supplier</p>
                    <h2 class="text-3xl font-bold text-gray-800 mt-2">
                        {{ \App\Models\Supplier::count() }}
                    </h2>
                </div>
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl">
                    🚚
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-4">Pemasok terdaftar</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Stok Menipis</p>
                    <h2 class="text-3xl font-bold text-red-600 mt-2">
                        {{ \App\Models\Product::whereColumn('stock', '<=', 'min_stock')->count() }}
                    </h2>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-2xl">
                    ⚠️
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-4">Barang perlu diperhatikan</p>
        </div>

    </div>

    {{-- Transaksi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-2xl p-6 text-white shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-blue-100 text-sm">Transaksi Barang Masuk</p>
                    <h2 class="text-3xl font-bold mt-2">
                        {{ \App\Models\StockIn::count() }}
                    </h2>
                    <p class="text-blue-100 text-xs mt-2">Total transaksi barang masuk</p>
                </div>
                <div class="text-5xl opacity-80">📥</div>
            </div>
            <a href="{{ route('stock-ins.index') }}"
               class="inline-block mt-5 text-sm font-semibold text-white hover:text-blue-100">
                Lihat transaksi →
            </a>
        </div>

        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-indigo-100 text-sm">Transaksi Barang Keluar</p>
                    <h2 class="text-3xl font-bold mt-2">
                        {{ \App\Models\StockOut::count() }}
                    </h2>
                    <p class="text-indigo-100 text-xs mt-2">Total transaksi barang keluar</p>
                </div>
                <div class="text-5xl opacity-80">📤</div>
            </div>
            <a href="{{ route('stock-outs.index') }}"
               class="inline-block mt-5 text-sm font-semibold text-white hover:text-indigo-100">
                Lihat transaksi →
            </a>
        </div>

    </div>

    {{-- Daftar stok menipis --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <div>
                <h2 class="font-bold text-gray-800 text-lg">Perhatian Stok</h2>
                <p class="text-sm text-gray-500">Daftar barang dengan stok mencapai batas minimum</p>
            </div>

            <a href="{{ route('stocks.index') }}"
               class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                Lihat semua stok →
            </a>
        </div>

        @php
            $lowStockProducts = \App\Models\Product::with('category')
                ->whereColumn('stock', '<=', 'min_stock')
                ->orderBy('stock')
                ->take(5)
                ->get();
        @endphp

        @if($lowStockProducts->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Kode</th>
                            <th class="px-6 py-3 font-semibold">Nama Barang</th>
                            <th class="px-6 py-3 font-semibold">Kategori</th>
                            <th class="px-6 py-3 font-semibold">Stok</th>
                            <th class="px-6 py-3 font-semibold">Stok Minimum</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($lowStockProducts as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-700">
                                    {{ $product->code }}
                                </td>
                                <td class="px-6 py-4 text-gray-800 font-medium">
                                    {{ $product->name }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $product->category?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-red-600">
                                    {{ $product->stock }} {{ $product->unit }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $product->min_stock }} {{ $product->unit }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        Stok Menipis
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-10 text-center">
                <div class="text-4xl mb-3">✅</div>
                <p class="font-semibold text-gray-700">Stok dalam kondisi aman</p>
                <p class="text-sm text-gray-400 mt-1">
                    Belum ada barang yang mencapai batas stok minimum.
                </p>
            </div>
        @endif

    </div>

</div>
@endsection

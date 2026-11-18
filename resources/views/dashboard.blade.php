
@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900">Dashboard</h1>
            <p class="mt-1 text-sm text-slate-500">
                Selamat datang kembali, {{ auth()->user()->name }}! Kelola persediaan dengan cermat dan presisi.
            </p>
        </div>

        <div class="inline-flex items-center gap-3 self-start rounded-full bg-white px-5 py-3 text-sm font-medium text-slate-700 shadow-sm">
            <x-icon name="calendar" class="h-5 w-5 text-slate-500" />
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    @php
        $totalProducts = \App\Models\Product::count();
        $lowStock = \App\Models\Product::whereColumn('stock', '<=', 'min_stock')->count();
    @endphp

    {{-- Baris atas: kartu sorotan + statistik utama --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        <div class="spark-card-dark flex min-h-[15.5rem] flex-col justify-between p-7">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold">
                    <span class="h-2 w-2 rounded-full {{ $lowStock > 0 ? 'bg-red-500' : 'bg-lime-400' }}"></span>
                    {{ $lowStock > 0 ? 'Perhatian' : 'Aman' }}
                </span>
                <p class="mt-5 text-xs text-white/50">{{ now()->translatedFormat('d F Y') }}</p>
                <h2 class="mt-1 max-w-[16rem] text-2xl font-bold leading-snug">
                    @if($lowStock > 0)
                        {{ $lowStock }} barang mencapai batas stok minimum
                    @else
                        Semua stok barang dalam kondisi aman
                    @endif
                </h2>
            </div>
            <a href="{{ route('stocks.index') }}"
               class="relative z-10 inline-flex items-center gap-2 text-sm font-semibold spark-star">
                Lihat monitoring stok <x-icon name="arrow-right" class="h-4 w-4" />
            </a>
            <x-spark-logo class="spark-star pointer-events-none absolute -bottom-6 -right-6 h-40 w-40 rotate-12" />
        </div>

        <div class="rounded-3xl bg-white p-7 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Total Barang</p>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <x-icon name="box" class="h-5 w-5" />
                </span>
            </div>
            <h2 class="mt-4 text-4xl font-extrabold tracking-tight text-slate-900">{{ number_format($totalProducts) }}</h2>
            <p class="mt-3 text-xs text-slate-400">Jumlah produk terdaftar</p>
            <div class="mt-6 h-2 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full" style="background: var(--spark-lime); width: {{ $totalProducts > 0 ? round((($totalProducts - $lowStock) / $totalProducts) * 100) : 100 }}%"></div>
            </div>
            <p class="mt-2 text-xs text-slate-500">
                {{ $totalProducts > 0 ? round((($totalProducts - $lowStock) / $totalProducts) * 100) : 100 }}% stok aman
            </p>
        </div>

        <div class="rounded-3xl bg-white p-7 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Stok Menipis</p>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-100 text-red-600">
                    <x-icon name="alert" class="h-5 w-5" />
                </span>
            </div>
            <h2 class="mt-4 text-4xl font-extrabold tracking-tight {{ $lowStock > 0 ? 'text-red-600' : 'text-slate-900' }}">
                {{ number_format($lowStock) }}
            </h2>
            <p class="mt-3 text-xs text-slate-400">Barang perlu diperhatikan</p>
            <div class="mt-6 h-2 overflow-hidden rounded-full bg-red-50">
                <div class="h-full rounded-full bg-red-400" style="width: {{ $totalProducts > 0 ? round(($lowStock / $totalProducts) * 100) : 0 }}%"></div>
            </div>
            <p class="mt-2 text-xs text-slate-500">
                {{ $totalProducts > 0 ? round(($lowStock / $totalProducts) * 100) : 0 }}% dari total barang
            </p>
        </div>

    </div>

    {{-- Statistik pendukung --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Total Kategori</p>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700"><x-icon name="folder" class="h-5 w-5" /></span>
            </div>
            <h2 class="mt-3 text-3xl font-extrabold text-slate-900">{{ \App\Models\Category::count() }}</h2>
            <p class="mt-2 text-xs text-slate-400">Kategori produk</p>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Total Supplier</p>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700"><x-icon name="truck" class="h-5 w-5" /></span>
            </div>
            <h2 class="mt-3 text-3xl font-extrabold text-slate-900">{{ \App\Models\Supplier::count() }}</h2>
            <p class="mt-2 text-xs text-slate-400">Pemasok terdaftar</p>
        </div>

        <div class="spark-card-dark p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm text-white/60">Transaksi Barang Masuk</p>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 spark-star"><x-icon name="download" class="h-5 w-5" /></span>
            </div>
            <h2 class="mt-3 text-3xl font-extrabold">{{ \App\Models\StockIn::count() }}</h2>
            <a href="{{ route('stock-ins.index') }}" class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold spark-star">
                Lihat transaksi <x-icon name="arrow-right" class="h-3.5 w-3.5" />
            </a>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Transaksi Barang Keluar</p>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700"><x-icon name="upload" class="h-5 w-5" /></span>
            </div>
            <h2 class="mt-3 text-3xl font-extrabold text-slate-900">{{ \App\Models\StockOut::count() }}</h2>
            <a href="{{ route('stock-outs.index') }}" class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700">
                Lihat transaksi <x-icon name="arrow-right" class="h-3.5 w-3.5" />
            </a>
        </div>

    </div>

    {{-- Daftar stok menipis --}}
    <div class="bg-white rounded-3xl shadow-sm overflow-hidden">

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


@extends('layouts.main')

@section('title', 'Barang')

@section('content')
<div class="space-y-6">

    {{-- Header halaman --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Barang</h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola informasi dan persediaan barang StockMart.
            </p>
        </div>

        @can('manage-master')
            <a href="{{ route('products.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                <span class="text-lg">+</span>
                Tambah Barang
            </a>
        @endcan
    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-2xl">
                    📦
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Barang Terdaftar</p>
                    <h2 class="mt-1 text-2xl font-bold text-slate-800">
                        {{ $products->total() }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-2xl">
                    🗂️
                </div>
                <div>
                    <p class="text-sm text-slate-500">Kategori Tersedia</p>
                    <h2 class="mt-1 text-2xl font-bold text-slate-800">
                        {{ $categories->count() }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Pencarian dan filter --}}
    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
        <div class="mb-4">
            <h2 class="font-semibold text-slate-800">Cari Barang</h2>
            <p class="mt-1 text-xs text-slate-500">
                Cari berdasarkan kode, nama, atau kategori barang.
            </p>
        </div>

        <form method="GET" action="{{ route('products.index') }}"
              class="flex flex-col gap-3 lg:flex-row">

            <div class="flex-1">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Masukkan kode atau nama barang..."
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <div class="w-full lg:w-64">
                <select
                    name="category_id"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    <option value="">Semua Kategori</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 rounded-xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 lg:flex-none">
                    🔍 Cari
                </button>

                @if (request()->hasAny(['q', 'category_id']))
                    <a href="{{ route('products.index') }}"
                       class="flex-1 rounded-xl bg-slate-100 px-5 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-200 lg:flex-none">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel barang --}}
    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">

        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-slate-800">Daftar Barang</h2>
                <p class="mt-1 text-xs text-slate-500">
                    Menampilkan {{ $products->count() }} dari {{ $products->total() }} barang.
                </p>
            </div>

            <span class="w-fit rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700">
                Data Persediaan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full whitespace-nowrap text-left text-sm">

                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Barang</th>
                        <th class="px-5 py-4">Kategori</th>
                        <th class="px-5 py-4">Satuan</th>
                        <th class="px-5 py-4 text-right">Harga Beli</th>
                        <th class="px-5 py-4 text-right">Harga Jual</th>
                        <th class="px-5 py-4 text-center">Stok</th>

                        @can('manage-master')
                            <th class="px-5 py-4 text-center">Aksi</th>
                        @endcan
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($products as $product)
                        <tr class="transition hover:bg-slate-50">

                            <td class="px-5 py-4 text-slate-400">
                                {{ $products->firstItem() + $loop->index }}
                            </td>

                            {{-- Informasi barang --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($product->photo)
                                        <img
                                            src="{{ asset('storage/' . $product->photo) }}"
                                            alt="{{ $product->name }}"
                                            class="h-12 w-12 rounded-xl border border-slate-100 object-cover">
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-xl">
                                            📦
                                        </div>
                                    @endif

                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $product->name }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $product->code }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $product->category?->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $product->unit }}
                            </td>

                            <td class="px-5 py-4 text-right text-slate-600">
                                Rp {{ number_format($product->buy_price, 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-right font-semibold text-slate-700">
                                Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                            </td>

                            {{-- Status stok --}}
                            <td class="px-5 py-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="font-bold text-slate-800">
                                        {{ $product->stock }} {{ $product->unit }}
                                    </span>

                                    @if ($product->stock <= 0)
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Habis
                                        </span>
                                    @elseif ($product->stock <= $product->min_stock)
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Menipis
                                        </span>
                                    @else
                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            Aman
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Aksi --}}
                            @can('manage-master')
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">

                                        <a href="{{ route('products.edit', $product) }}"
                                           class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100">
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('products.destroy', $product) }}"
                                            onsubmit="return confirm('Apakah kamu yakin ingin menghapus barang ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100">
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            @endcan

                        </tr>

                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->can('manage-master') ? 8 : 7 }}"
                                class="px-5 py-16 text-center">

                                <div class="text-5xl">📦</div>

                                <p class="mt-4 font-semibold text-slate-700">
                                    Belum ada barang
                                </p>

                                <p class="mt-1 text-sm text-slate-400">
                                    Data barang belum tersedia atau tidak ditemukan.
                                </p>

                                @can('manage-master')
                                    <a href="{{ route('products.create') }}"
                                       class="mt-4 inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                        Tambah Barang
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($products->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif

    </div>

</div>
@endsection

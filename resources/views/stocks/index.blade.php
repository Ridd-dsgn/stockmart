
@extends('layouts.main')

@section('title', 'Monitoring Stok')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Monitoring Stok</h1>
        <p class="mt-1 text-sm text-gray-500">
            Pantau ketersediaan dan kondisi stok barang.
        </p>
    </div>

    {{-- Ringkasan Stok --}}
    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-lg bg-white p-5 shadow">
            <p class="text-sm text-gray-500">Total Barang</p>
            <p class="mt-2 text-2xl font-bold">{{ $totalProducts }}</p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow">
            <p class="text-sm text-gray-500">Stok Aman</p>
            <p class="mt-2 text-2xl font-bold text-green-600">
                {{ $safeStockCount }}
            </p>
        </div>

        <div class="rounded-lg bg-white p-5 shadow">
            <p class="text-sm text-gray-500">Stok Menipis</p>
            <p class="mt-2 text-2xl font-bold text-red-600">
                {{ $lowStockCount }}
            </p>
        </div>
    </div>

    {{-- Pencarian dan Filter --}}
    <div class="mb-6 rounded-lg bg-white p-5 shadow">
        <form method="GET" action="{{ route('stocks.index') }}"
            class="flex flex-col gap-3 md:flex-row">

            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari kode atau nama barang..."
                class="w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
            >

            <select
                name="status"
                class="rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="">Semua Status</option>
                <option value="safe" @selected(request('status') === 'safe')>
                    Stok Aman
                </option>
                <option value="low" @selected(request('status') === 'low')>
                    Stok Menipis
                </option>
            </select>

            <button type="submit"
                class="rounded bg-indigo-600 px-5 py-2 text-sm text-white hover:bg-indigo-700">
                Cari
            </button>

            <a href="{{ route('stocks.index') }}"
                class="rounded bg-gray-200 px-5 py-2 text-center text-sm hover:bg-gray-300">
                Reset
            </a>
        </form>
    </div>

    {{-- Tabel Barang --}}
    <div class="overflow-hidden rounded-lg bg-white shadow">
        <div class="border-b px-5 py-4">
            <h2 class="font-semibold">Daftar Kondisi Stok Barang</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-5 py-3">No.</th>
                        <th class="px-5 py-3">Kode Barang</th>
                        <th class="px-5 py-3">Nama Barang</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3">Stok Saat Ini</th>
                        <th class="px-5 py-3">Stok Minimum</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                {{ $products->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-3">
                                {{ $product->code }}
                            </td>

                            <td class="px-5 py-3 font-medium">
                                {{ $product->name }}
                            </td>

                            <td class="px-5 py-3">
                                {{ $product->category?->name ?? '-' }}
                            </td>

                            <td class="px-5 py-3">
                                {{ number_format($product->stock) }}
                                {{ $product->unit }}
                            </td>

                            <td class="px-5 py-3">
                                {{ number_format($product->min_stock) }}
                                {{ $product->unit }}
                            </td>

                            <td class="px-5 py-3">
                                @if ($product->stock <= $product->min_stock)
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                        Menipis
                                    </span>
                                @else
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Aman
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-gray-500">
                                Tidak ada data barang yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t px-5 py-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection

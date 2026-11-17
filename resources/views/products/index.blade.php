@extends('layouts.main')

@section('title', 'Barang')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Barang</h1>
        @can('manage-master')
            <a href="{{ route('products.create') }}"
               class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Tambah Barang</a>
        @endcan
    </div>

    <form method="GET" class="mb-4 flex flex-wrap gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama..."
               class="w-72 rounded border-gray-300 text-sm">
        <select name="category_id" class="rounded border-gray-300 text-sm">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <button class="rounded bg-gray-800 px-3 py-2 text-sm text-white">Cari</button>
        @if (request()->hasAny(['q', 'category_id']))
            <a href="{{ route('products.index') }}" class="rounded bg-gray-200 px-3 py-2 text-sm">Reset</a>
        @endif
    </form>

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Satuan</th>
                    <th class="px-4 py-3 text-right">Harga Beli</th>
                    <th class="px-4 py-3 text-right">Harga Jual</th>
                    <th class="px-4 py-3">Stok</th>
                    @can('manage-master')
                        <th class="px-4 py-3 text-right">Aksi</th>
                    @endcan
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-4 py-3">{{ $products->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3">
                            @if ($product->photo)
                                <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}"
                                     class="h-10 w-10 rounded object-cover">
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $product->code }}</td>
                        <td class="px-4 py-3">{{ $product->name }}</td>
                        <td class="px-4 py-3">{{ $product->category?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $product->unit }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            {{ $product->stock }}
                            @if ($product->stock <= 0)
                                <span class="ml-1 rounded bg-red-100 px-2 py-0.5 text-xs text-red-700">Habis</span>
                            @elseif ($product->stock <= $product->min_stock)
                                <span class="ml-1 rounded bg-yellow-100 px-2 py-0.5 text-xs text-yellow-800">Menipis</span>
                            @endif
                        </td>
                        @can('manage-master')
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('products.edit', $product) }}" class="text-indigo-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('products.destroy', $product) }}" class="ml-3 inline"
                                      onsubmit="return confirm('Hapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->can('manage-master') ? 10 : 9 }}"
                            class="px-4 py-6 text-center text-gray-500">Belum ada barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
@endsection
@extends('layouts.main')

@section('title', 'Kategori')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Kategori Barang</h1>
        @can('manage-master')
            <a href="{{ route('categories.create') }}"
               class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Tambah Kategori</a>
        @endcan
    </div>

    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kategori..."
               class="w-72 rounded border-gray-300 text-sm">
        <button class="rounded bg-gray-800 px-3 py-2 text-sm text-white">Cari</button>
    </form>

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Deskripsi</th>
                    <th class="px-4 py-3">Jumlah Barang</th>
                    @can('manage-master')
                        <th class="px-4 py-3 text-right">Aksi</th>
                    @endcan
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3">{{ $categories->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3">{{ $category->name }}</td>
                        <td class="px-4 py-3">{{ $category->description ?: '-' }}</td>
                        <td class="px-4 py-3">{{ $category->products_count }}</td>
                        @can('manage-master')
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('categories.edit', $category) }}" class="text-indigo-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="ml-3 inline"
                                      onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->can('manage-master') ? 5 : 4 }}"
                            class="px-4 py-6 text-center text-gray-500">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>
@endsection
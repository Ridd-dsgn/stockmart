@extends('layouts.main')

@section('title', 'Supplier')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Supplier</h1>
        @can('manage-master')
            <a href="{{ route('suppliers.create') }}"
               class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Tambah Supplier</a>
        @endcan
    </div>

    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, telepon, atau email..."
               class="w-72 rounded border-gray-300 text-sm">
        <button class="rounded bg-gray-800 px-3 py-2 text-sm text-white">Cari</button>
    </form>

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Telepon</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Alamat</th>
                    @can('manage-master')
                        <th class="px-4 py-3 text-right">Aksi</th>
                    @endcan
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($suppliers as $supplier)
                    <tr>
                        <td class="px-4 py-3">{{ $suppliers->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3">{{ $supplier->name }}</td>
                        <td class="px-4 py-3">{{ $supplier->phone ?: '-' }}</td>
                        <td class="px-4 py-3">{{ $supplier->email ?: '-' }}</td>
                        <td class="px-4 py-3">{{ $supplier->address ?: '-' }}</td>
                        @can('manage-master')
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="text-indigo-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" class="ml-3 inline"
                                      onsubmit="return confirm('Hapus supplier ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->can('manage-master') ? 6 : 5 }}"
                            class="px-4 py-6 text-center text-gray-500">Belum ada supplier.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $suppliers->links() }}</div>
@endsection
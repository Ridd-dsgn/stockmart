
@extends('layouts.main')

@section('title', 'Barang Keluar')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Barang Keluar</h1>

        <a href="{{ route('stock-outs.create') }}"
           class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">
            Tambah Barang Keluar
        </a>
    </div>
    <form method="GET" class="mb-4 flex flex-wrap gap-2">
        <input type="text"
               name="q"
               value="{{ request('q') }}"
               placeholder="Cari alasan atau keterangan..."
               class="w-72 rounded border-gray-300 text-sm">

        <button class="rounded bg-gray-800 px-3 py-2 text-sm text-white">
            Cari
        </button>

        @if (request()->filled('q'))
            <a href="{{ route('stock-outs.index') }}"
               class="rounded bg-gray-200 px-3 py-2 text-sm">
                Reset
            </a>
        @endif
    </form>

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Alasan</th>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3">Total Jumlah</th>
                    <th class="px-4 py-3">Pengguna</th>
                    <th class="px-4 py-3">Keterangan</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($stockOuts as $stockOut)
                    <tr>
                        <td class="px-4 py-3">
                            {{ $stockOuts->firstItem() + $loop->index }}
                        </td>

                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($stockOut->date)->format('d/m/Y') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $stockOut->reason }}
                        </td>

                        <td class="px-4 py-3">
                            @foreach ($stockOut->items as $item)
                                <div>
                                    {{ $item->product?->name ?? 'Barang tidak ditemukan' }}
                                    ({{ $item->qty }})
                                </div>
                            @endforeach
                        </td>

                        <td class="px-4 py-3">
                            {{ $stockOut->items->sum('qty') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $stockOut->user?->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $stockOut->note ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            Belum ada transaksi barang keluar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $stockOuts->links() }}
    </div>
@endsection


@extends('layouts.main')

@section('title', 'Barang Masuk')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Barang Masuk</h1>

        @if (auth()->user()->can('manage-master') || auth()->user()->can('manage-stock'))
            <a href="{{ route('stock-ins.create') }}"
               class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">
                Tambah Barang Masuk
            </a>
        @endif
    </div>

    @if (session('status'))
        <div class="mb-4 rounded bg-green-100 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="GET" class="mb-4 flex flex-wrap gap-2">
        <input type="text"
               name="q"
               value="{{ request('q') }}"
               placeholder="Cari nama supplier..."
               class="w-72 rounded border-gray-300 text-sm">

        <button class="rounded bg-gray-800 px-3 py-2 text-sm text-white">
            Cari
        </button>

        @if (request()->filled('q'))
            <a href="{{ route('stock-ins.index') }}"
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
                    <th class="px-4 py-3">Supplier</th>
                    <th class="px-4 py-3">Jumlah Jenis Barang</th>
                    <th class="px-4 py-3">Petugas</th>
                    <th class="px-4 py-3">Bukti</th>
                    <th class="px-4 py-3">Catatan</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($stockIns as $stockIn)
                    <tr>
                        <td class="px-4 py-3">
                            {{ $stockIns->firstItem() + $loop->index }}
                        </td>

                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($stockIn->date)->format('d/m/Y') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $stockIn->supplier?->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $stockIn->items->count() }} jenis barang
                        </td>

                        <td class="px-4 py-3">
                            {{ $stockIn->user?->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            @if ($stockIn->proof_file)
                                <a href="{{ asset('storage/' . $stockIn->proof_file) }}"
                                   target="_blank"
                                   class="text-indigo-600 hover:underline">
                                    Lihat Bukti
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            {{ $stockIn->note ?: '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            Belum ada transaksi barang masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $stockIns->links() }}
    </div>
@endsection

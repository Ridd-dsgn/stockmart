
@extends('layouts.main')

@section('title', 'Barang Keluar')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Barang Keluar</h1>
            <p class="mt-1 text-sm text-gray-500">
                Riwayat seluruh transaksi barang keluar.
            </p>
        </div>

        @if (auth()->user()->can('manage-master') || auth()->user()->can('manage-stock'))
            <a href="{{ route('stock-outs.create') }}"
               class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow hover:bg-indigo-700">
                + Tambah Barang Keluar
            </a>
        @endif
    </div>

    <div class="mb-5 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('stock-outs.index') }}"
              class="flex flex-wrap items-center gap-2">

            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="Cari alasan atau keterangan..."
                   class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-80">

            <button type="submit"
                    class="rounded-lg bg-gray-800 px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-700">
                Cari
            </button>

            @if (request()->filled('q'))
                <a href="{{ route('stock-outs.index') }}"
                   class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-200">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-4">No.</th>
                        <th class="px-4 py-4">Tanggal</th>
                        <th class="px-4 py-4">Alasan</th>
                        <th class="px-4 py-4">Barang Keluar</th>
                        <th class="px-4 py-4">Total Qty</th>
                        <th class="px-4 py-4">Petugas</th>
                        <th class="px-4 py-4">Catatan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($stockOuts as $stockOut)
                        <tr class="align-top hover:bg-gray-50">
                            <td class="whitespace-nowrap px-4 py-4 text-gray-500">
                                {{ $stockOuts->firstItem() + $loop->index }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 font-medium text-gray-700">
                                {{ \Carbon\Carbon::parse($stockOut->date)->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-4">
                                <span class="font-semibold text-gray-800">
                                    {{ $stockOut->reason }}
                                </span>
                            </td>

                            <td class="min-w-52 px-4 py-4">
                                <div class="space-y-2">
                                    @forelse ($stockOut->items as $item)
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-gray-700">
                                                {{ $item->product?->name ?? 'Barang tidak ditemukan' }}
                                            </span>

                                            <span class="whitespace-nowrap rounded-md bg-orange-50 px-2 py-1 text-xs font-semibold text-orange-700">
                                                {{ number_format($item->qty) }} {{ $item->product?->unit ?? '' }}
                                            </span>
                                        </div>
                                    @empty
                                        <span class="text-gray-400">Tidak ada barang</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-4 py-4">
                                <span class="rounded-lg bg-orange-50 px-3 py-1.5 font-semibold text-orange-700">
                                    {{ number_format($stockOut->items->sum('qty')) }} unit
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 text-gray-600">
                                {{ $stockOut->user?->name ?? '-' }}
                            </td>

                            <td class="max-w-48 px-4 py-4 text-gray-600">
                                {{ $stockOut->note ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center">
                                <p class="font-medium text-gray-600">
                                    Belum ada transaksi barang keluar
                                </p>
                                <p class="mt-1 text-xs text-gray-400">
                                    Transaksi yang berhasil disimpan akan muncul di sini.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        {{ $stockOuts->links() }}
    </div>
@endsection

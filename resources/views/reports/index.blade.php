
@extends('layouts.main')

@section('title', 'Laporan Stok')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Stok</h1>
        <p class="mt-1 text-sm text-gray-500">
            Rekapitulasi transaksi barang masuk dan barang keluar.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Filter tanggal --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm print:hidden">
        <h2 class="mb-4 font-semibold text-gray-800">Filter Periode Laporan</h2>

        <form method="GET" action="{{ route('reports.index') }}"
              class="flex flex-wrap items-end gap-3">

            <div>
                <label class="mb-1 block text-sm text-gray-600">Tanggal Mulai</label>
                <input type="date"
                       name="start_date"
                       value="{{ $startDate }}"
                       class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="mb-1 block text-sm text-gray-600">Tanggal Akhir</label>
                <input type="date"
                       name="end_date"
                       value="{{ $endDate }}"
                       class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <button type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
                Tampilkan
            </button>

            <a href="{{ route('reports.index') }}"
               class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm text-gray-700 hover:bg-gray-200">
                Reset
            </a>

            <button type="button"
                    onclick="window.print()"
                    class="rounded-lg bg-gray-800 px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-700">
                Cetak Laporan
            </button>
        </form>
    </div>

    {{-- Judul cetak --}}
    <div class="mb-5 hidden print:block">
        <h2 class="text-xl font-bold">LAPORAN STOK STOCKMART</h2>
        <p class="text-sm">
            Periode:
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Semua tanggal' }}
            sampai
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Sekarang' }}
        </p>
    </div>

    {{-- Ringkasan --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-xl border border-green-100 bg-green-50 p-5">
            <p class="text-sm font-medium text-green-700">Total Barang Masuk</p>
            <h2 class="mt-2 text-3xl font-bold text-green-800">
                {{ number_format($totalStockIn) }}
            </h2>
            <p class="mt-1 text-xs text-green-700">Unit barang diterima</p>
        </div>

        <div class="rounded-xl border border-orange-100 bg-orange-50 p-5">
            <p class="text-sm font-medium text-orange-700">Total Barang Keluar</p>
            <h2 class="mt-2 text-3xl font-bold text-orange-800">
                {{ number_format($totalStockOut) }}
            </h2>
            <p class="mt-1 text-xs text-orange-700">Unit barang dikeluarkan</p>
        </div>
    </div>

    {{-- Tabel barang masuk --}}
    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="font-semibold text-green-700">Detail Barang Masuk</h2>
            <p class="mt-1 text-xs text-gray-500">
                Daftar transaksi penerimaan barang.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">No.</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Barang</th>
                        <th class="px-4 py-3">Supplier</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Petugas</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @php $no = 1; @endphp

                    @forelse ($stockIns as $stockIn)
                        @foreach ($stockIn->items as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $no++ }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    {{ \Carbon\Carbon::parse($stockIn->date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $item->product?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $stockIn->supplier?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-green-700">
                                    {{ number_format($item->qty) }}
                                    {{ $item->product?->unit ?? '' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $stockIn->user?->name ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                                Tidak ada transaksi barang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabel barang keluar --}}
    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="font-semibold text-orange-700">Detail Barang Keluar</h2>
            <p class="mt-1 text-xs text-gray-500">
                Daftar transaksi pengeluaran barang.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">No.</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Barang</th>
                        <th class="px-4 py-3">Alasan</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Petugas</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @php $no = 1; @endphp

                    @forelse ($stockOuts as $stockOut)
                        @foreach ($stockOut->items as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $no++ }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    {{ \Carbon\Carbon::parse($stockOut->date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $item->product?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $stockOut->reason }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-orange-700">
                                    {{ number_format($item->qty) }}
                                    {{ $item->product?->unit ?? '' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $stockOut->user?->name ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                                Tidak ada transaksi barang keluar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 12mm;
            }

            body {
                background: white !important;
            }

            aside, nav, header, footer {
                display: none !important;
            }

            body * {
                visibility: hidden;
            }

            main, main * {
                visibility: visible;
            }

            main {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0 !important;
                padding: 0 !important;
            }

            .print\:hidden {
                display: none !important;
            }

            .print\:block {
                display: block !important;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 7px !important;
            }

            tr {
                break-inside: avoid;
            }
        }
    </style>
@endsection
s
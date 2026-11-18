
@extends('layouts.main')

@section('title', 'Tambah Barang Keluar')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Tambah Barang Keluar</h1>

        <a href="{{ route('stock-outs.index') }}"
           class="rounded bg-gray-200 px-4 py-2 text-sm hover:bg-gray-300">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('stock-outs.store') }}" method="POST"
          class="space-y-5 rounded bg-white p-6 shadow">
        @csrf

        <div>
            <label class="mb-1 block text-sm font-medium">Tanggal</label>
            <input type="date" name="date"
                   value="{{ old('date', now()->toDateString()) }}"
                   required
                   class="w-full rounded border-gray-300">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Alasan Barang Keluar</label>
            <input type="text" name="reason"
                   value="{{ old('reason') }}"
                   placeholder="Contoh: Penjualan, barang rusak, atau retur"
                   required
                   class="w-full rounded border-gray-300">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Keterangan</label>
            <textarea name="note" rows="3"
                      class="w-full rounded border-gray-300"
                      placeholder="Keterangan tambahan (opsional)">{{ old('note') }}</textarea>
        </div>

        <div>
            <div class="mb-2 flex items-center justify-between">
                <h2 class="font-semibold">Daftar Barang</h2>

                <button type="button" id="add-item"
                        class="rounded bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-700">
                    + Tambah Barang
                </button>
            </div>

            <div id="items-container" class="space-y-3">
                <div class="item-row grid grid-cols-1 gap-3 rounded border p-3 md:grid-cols-12">
                    <div class="md:col-span-7">
                        <label class="mb-1 block text-sm">Nama Barang</label>
                        <select name="items[0][product_id]" required
                                class="product-select w-full rounded border-gray-300">
                            <option value="">Pilih barang</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                        data-stock="{{ $product->stock }}">
                                    {{ $product->code }} - {{ $product->name }}
                                    (Stok: {{ $product->stock }} {{ $product->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="mb-1 block text-sm">Jumlah Keluar</label>
                        <input type="number" name="items[0][qty]"
                               min="1" required
                               class="w-full rounded border-gray-300"
                               placeholder="Jumlah">
                    </div>

                    <div class="flex items-end md:col-span-2">
                        <button type="button"
                                class="remove-item w-full rounded bg-red-100 px-3 py-2 text-sm text-red-700 hover:bg-red-200">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-4">
            <a href="{{ route('stock-outs.index') }}"
               class="rounded bg-gray-200 px-4 py-2 text-sm">
                Batal
            </a>

            <button type="submit"
                    class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">
                Simpan Transaksi
            </button>
        </div>
    </form>

    <script>
        let itemIndex = 1;

        const container = document.getElementById('items-container');
        const addButton = document.getElementById('add-item');

        function updateProductOptions() {
            const selects = container.querySelectorAll('.product-select');
            const selectedValues = Array.from(selects)
                .map(select => select.value)
                .filter(value => value !== '');

            selects.forEach(select => {
                Array.from(select.options).forEach(option => {
                    if (!option.value) return;

                    option.disabled =
                        selectedValues.includes(option.value) &&
                        select.value !== option.value;
                });
            });
        }

        addButton.addEventListener('click', function () {
            const firstRow = container.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelectorAll('select, input').forEach(input => {
                input.name = input.name.replace(
                    /\[\d+\]/,
                    '[' + itemIndex + ']'
                );
                input.value = '';
            });

            container.appendChild(newRow);
            itemIndex++;
            updateProductOptions();
        });

        container.addEventListener('change', function (event) {
            if (event.target.classList.contains('product-select')) {
                updateProductOptions();
            }
        });

        container.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-item')) {
                const rows = container.querySelectorAll('.item-row');

                if (rows.length > 1) {
                    event.target.closest('.item-row').remove();
                    updateProductOptions();
                }
            }
        });
    </script>
@endsection

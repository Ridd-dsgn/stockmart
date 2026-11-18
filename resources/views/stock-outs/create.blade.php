
@extends('layouts.main')

@section('title', 'Tambah Barang Keluar')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Tambah Barang Keluar</h1>
        <p class="mt-1 text-sm text-gray-500">
            Catat barang yang keluar dari gudang.
        </p>
    </div>

    <form action="{{ route('stock-outs.store') }}"
          method="POST"
          class="max-w-4xl space-y-5 rounded-xl bg-white p-6 shadow">

        @csrf

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium">
                    Tanggal Barang Keluar
                </label>

                <input type="date"
                       name="date"
                       value="{{ old('date', now()->toDateString()) }}"
                       required
                       class="w-full rounded-lg border-gray-300 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">
                    Alasan Barang Keluar
                </label>

                <input type="text"
                       name="reason"
                       value="{{ old('reason') }}"
                       placeholder="Contoh: Penjualan, barang rusak, retur"
                       required
                       class="w-full rounded-lg border-gray-300 text-sm">
            </div>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium">
                Keterangan
            </label>

            <textarea name="note"
                      rows="3"
                      placeholder="Keterangan tambahan (opsional)"
                      class="w-full rounded-lg border-gray-300 text-sm">{{ old('note') }}</textarea>
        </div>

        <div>
            <div class="mb-3 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold">Daftar Barang</h2>
                    <p class="text-xs text-gray-500">
                        Pilih barang dan masukkan jumlah yang keluar.
                    </p>
                </div>

                <button type="button"
                        id="add-item"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">
                    + Tambah Barang
                </button>
            </div>

            <div id="items-container" class="space-y-3">
                <div class="item-row grid grid-cols-1 gap-3 rounded-lg border p-4 md:grid-cols-12">

                    <div class="md:col-span-7">
                        <label class="mb-2 block text-sm font-medium">
                            Nama Barang
                        </label>

                        <select name="items[0][product_id]"
                                required
                                class="product-select w-full rounded-lg border-gray-300 text-sm">

                            <option value="">Pilih Barang</option>

                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                        data-stock="{{ $product->stock }}"
                                        data-unit="{{ $product->unit }}">
                                    {{ $product->code }} - {{ $product->name }}
                                </option>
                            @endforeach
                        </select>

                        <p class="stock-info mt-2 text-xs text-gray-500">
                            Stok tersedia: -
                        </p>
                    </div>

                    <div class="md:col-span-3">
                        <label class="mb-2 block text-sm font-medium">
                            Jumlah Keluar
                        </label>

                        <input type="number"
                               name="items[0][qty]"
                               min="1"
                               required
                               placeholder="Jumlah"
                               class="qty-input w-full rounded-lg border-gray-300 text-sm">
                    </div>

                    <div class="flex items-end md:col-span-2">
                        <button type="button"
                                class="remove-item w-full rounded-lg bg-red-50 px-3 py-2 text-sm text-red-600 hover:bg-red-100">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-4">
            <a href="{{ route('stock-outs.index') }}"
               class="rounded-lg bg-gray-100 px-4 py-2 text-sm hover:bg-gray-200">
                Batal
            </a>

            <button type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2 text-sm text-white hover:bg-indigo-700">
                Simpan Transaksi
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('items-container');
            const addButton = document.getElementById('add-item');

            let itemIndex = 1;

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

            function updateStockInfo(row) {
                const select = row.querySelector('.product-select');
                const info = row.querySelector('.stock-info');
                const qtyInput = row.querySelector('.qty-input');

                const option = select.selectedOptions[0];

                if (!option || !option.value) {
                    info.textContent = 'Stok tersedia: -';
                    qtyInput.removeAttribute('max');
                    return;
                }

                const stock = Number(option.dataset.stock);
                const unit = option.dataset.unit;

                info.textContent = `Stok tersedia: ${stock} ${unit}`;
                qtyInput.max = stock;
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

                    if (input.classList.contains('qty-input')) {
                        input.removeAttribute('max');
                    }
                });

                newRow.querySelector('.stock-info').textContent =
                    'Stok tersedia: -';

                container.appendChild(newRow);
                itemIndex++;

                updateProductOptions();
            });

            container.addEventListener('change', function (event) {
                if (event.target.classList.contains('product-select')) {
                    updateStockInfo(event.target.closest('.item-row'));
                    updateProductOptions();
                }
            });

            container.addEventListener('input', function (event) {
                if (event.target.classList.contains('qty-input')) {
                    const input = event.target;
                    const max = Number(input.max);

                    if (max && Number(input.value) > max) {
                        input.setCustomValidity(
                            'Jumlah keluar melebihi stok yang tersedia.'
                        );
                    } else {
                        input.setCustomValidity('');
                    }
                }
            });

            container.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-item')) {
                    const rows = container.querySelectorAll('.item-row');

                    if (rows.length > 1) {
                        event.target.closest('.item-row').remove();
                        updateProductOptions();
                    } else {
                        alert('Minimal harus ada satu barang.');
                    }
                }
            });
        });
    </script>
@endsection

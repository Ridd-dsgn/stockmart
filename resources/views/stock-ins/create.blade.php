
@extends('layouts.main')

@section('title', 'Tambah Barang Masuk')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">Tambah Barang Masuk</h1>

    <form method="POST"
          action="{{ route('stock-ins.store') }}"
          enctype="multipart/form-data"
          class="max-w-4xl rounded bg-white p-6 shadow">

        @csrf

        <div class="mb-4 grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium">Supplier</label>
                <select name="supplier_id"
                        required
                        class="w-full rounded border-gray-300 text-sm">
                    <option value="">Pilih Supplier</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}"
                            @selected(old('supplier_id') == $supplier->id)>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Tanggal Barang Masuk</label>
                <input type="date"
                       name="date"
                       value="{{ old('date', now()->toDateString()) }}"
                       required
                       class="w-full rounded border-gray-300 text-sm">
                @error('date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <div class="mb-2 flex items-center justify-between">
                <h2 class="font-semibold">Daftar Barang</h2>
                <button type="button"
                        id="add-item"
                        class="rounded bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-700">
                    + Tambah Barang
                </button>
            </div>

            <div id="items-container" class="space-y-3">
                @foreach (old('items', [['product_id' => '', 'qty' => 1]]) as $index => $oldItem)
                    <div class="item-row grid grid-cols-1 gap-2 rounded border p-3 md:grid-cols-12">
                        <div class="md:col-span-7">
                            <label class="mb-1 block text-sm">Nama Barang</label>
                            <select name="items[{{ $index }}][product_id]"
                                    required
                                    class="w-full rounded border-gray-300 text-sm">
                                <option value="">Pilih Barang</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        @selected(($oldItem['product_id'] ?? '') == $product->id)>
                                        {{ $product->code }} - {{ $product->name }}
                                        (Stok: {{ $product->stock }} {{ $product->unit }})
                                    </option>
                                @endforeach
                            </select>
                            @error("items.$index.product_id")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="mb-1 block text-sm">Jumlah</label>
                            <input type="number"
                                   name="items[{{ $index }}][qty]"
                                   value="{{ $oldItem['qty'] ?? 1 }}"
                                   min="1"
                                   required
                                   class="w-full rounded border-gray-300 text-sm">
                            @error("items.$index.qty")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-end md:col-span-2">
                            <button type="button"
                                    class="remove-item w-full rounded bg-red-100 px-3 py-2 text-sm text-red-700 hover:bg-red-200">
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            @error('items')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="mb-1 block text-sm font-medium">Bukti Transaksi</label>
            <input type="file"
                   name="proof_file"
                   accept=".jpg,.jpeg,.png,.pdf"
                   class="w-full rounded border border-gray-300 p-2 text-sm">
            <p class="mt-1 text-xs text-gray-500">
                Format JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.
            </p>
            @error('proof_file')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="mb-1 block text-sm font-medium">Catatan</label>
            <textarea name="note"
                      rows="3"
                      class="w-full rounded border-gray-300 text-sm"
                      placeholder="Tambahkan catatan jika diperlukan">{{ old('note') }}</textarea>
            @error('note')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('stock-ins.index') }}"
               class="rounded bg-gray-200 px-4 py-2 text-sm hover:bg-gray-300">
                Batal
            </a>

            <button type="submit"
                    class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">
                Simpan Transaksi
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('items-container');
            const addButton = document.getElementById('add-item');

            let nextIndex = {{ count(old('items', [['product_id' => '', 'qty' => 1]])) }};

            addButton.addEventListener('click', function () {
                const row = document.createElement('div');

                row.className = 'item-row grid grid-cols-1 gap-2 rounded border p-3 md:grid-cols-12';

                row.innerHTML = `
                    <div class="md:col-span-7">
                        <label class="mb-1 block text-sm">Nama Barang</label>
                        <select name="items[${nextIndex}][product_id]"
                                required
                                class="w-full rounded border-gray-300 text-sm">
                            <option value="">Pilih Barang</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->code }} - {{ $product->name }}
                                    (Stok: {{ $product->stock }} {{ $product->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="mb-1 block text-sm">Jumlah</label>
                        <input type="number"
                               name="items[${nextIndex}][qty]"
                               value="1"
                               min="1"
                               required
                               class="w-full rounded border-gray-300 text-sm">
                    </div>

                    <div class="flex items-end md:col-span-2">
                        <button type="button"
                                class="remove-item w-full rounded bg-red-100 px-3 py-2 text-sm text-red-700 hover:bg-red-200">
                            Hapus
                        </button>
                    </div>
                `;

                container.appendChild(row);
                nextIndex++;
            });

            container.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-item')) {
                    const rows = container.querySelectorAll('.item-row');

                    if (rows.length > 1) {
                        event.target.closest('.item-row').remove();
                    } else {
                        alert('Minimal harus ada satu barang.');
                    }
                }
            });
        });
    </script>
@endsection

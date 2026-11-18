
@extends('layouts.main')

@section('title', 'Tambah Barang Masuk')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Tambah Barang Masuk</h1>
        <p class="mt-1 text-sm text-gray-500">
            Masukkan informasi supplier dan barang yang diterima.
        </p>
    </div>

    <form method="POST"
          action="{{ route('stock-ins.store') }}"
          enctype="multipart/form-data"
          class="max-w-4xl rounded-xl bg-white p-6 shadow">

        @csrf

        <div class="mb-6 grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium">
                    Nama Supplier
                </label>

                <input type="text"
                       name="supplier_name"
                       value="{{ old('supplier_name') }}"
                       placeholder="Masukkan nama supplier"
                       maxlength="255"
                       required
                       class="w-full rounded-lg border-gray-300 text-sm">

                @error('supplier_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">
                    Tanggal Barang Masuk
                </label>

                <input type="date"
                       name="date"
                       value="{{ old('date', now()->toDateString()) }}"
                       required
                       class="w-full rounded-lg border-gray-300 text-sm">

                @error('date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <div class="mb-3 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold">Daftar Barang</h2>
                    <p class="text-xs text-gray-500">
                        Ketik nama barang secara langsung.
                    </p>
                </div>

                <button type="button"
                        id="add-item"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">
                    + Tambah Barang
                </button>
            </div>

            <div id="items-container" class="space-y-3">
                @foreach (old('items', [['name' => '', 'qty' => 1]]) as $index => $oldItem)
                    <div class="item-row grid grid-cols-1 gap-3 rounded-lg border border-gray-200 p-4 md:grid-cols-12">

                        <div class="md:col-span-7">
                            <label class="mb-2 block text-sm font-medium">
                                Nama Barang
                            </label>

                            <input type="text"
                                   name="items[{{ $index }}][name]"
                                   value="{{ $oldItem['name'] ?? '' }}"
                                   placeholder="Contoh: Pulpen, Buku, Kertas A4"
                                   maxlength="255"
                                   required
                                   class="w-full rounded-lg border-gray-300 text-sm">

                            @error("items.$index.name")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="mb-2 block text-sm font-medium">
                                Jumlah
                            </label>

                            <input type="number"
                                   name="items[{{ $index }}][qty]"
                                   value="{{ $oldItem['qty'] ?? 1 }}"
                                   min="1"
                                   required
                                   class="w-full rounded-lg border-gray-300 text-sm">

                            @error("items.$index.qty")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-end md:col-span-2">
                            <button type="button"
                                    class="remove-item w-full rounded-lg bg-red-50 px-3 py-2 text-sm text-red-600 hover:bg-red-100">
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

        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium">
                Bukti Transaksi
            </label>

            <input type="file"
                   name="proof_file"
                   accept=".jpg,.jpeg,.png,.pdf"
                   class="w-full rounded-lg border border-gray-300 p-2 text-sm">

            <p class="mt-1 text-xs text-gray-500">
                Format JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.
            </p>

            @error('proof_file')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="mb-2 block text-sm font-medium">Catatan</label>

            <textarea name="note"
                      rows="3"
                      placeholder="Tambahkan catatan jika diperlukan"
                      class="w-full rounded-lg border-gray-300 text-sm">{{ old('note') }}</textarea>

            @error('note')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2 border-t pt-4">
            <a href="{{ route('stock-ins.index') }}"
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

            let nextIndex = {{ count(old('items', [['name' => '', 'qty' => 1]])) }};

            addButton.addEventListener('click', function () {
                const row = document.createElement('div');

                row.className = 'item-row grid grid-cols-1 gap-3 rounded-lg border border-gray-200 p-4 md:grid-cols-12';

                row.innerHTML = `
                    <div class="md:col-span-7">
                        <label class="mb-2 block text-sm font-medium">Nama Barang</label>
                        <input type="text"
                               name="items[${nextIndex}][name]"
                               placeholder="Contoh: Pulpen, Buku, Kertas A4"
                               maxlength="255"
                               required
                               class="w-full rounded-lg border-gray-300 text-sm">
                    </div>

                    <div class="md:col-span-3">
                        <label class="mb-2 block text-sm font-medium">Jumlah</label>
                        <input type="number"
                               name="items[${nextIndex}][qty]"
                               value="1"
                               min="1"
                               required
                               class="w-full rounded-lg border-gray-300 text-sm">
                    </div>

                    <div class="flex items-end md:col-span-2">
                        <button type="button"
                                class="remove-item w-full rounded-lg bg-red-50 px-3 py-2 text-sm text-red-600 hover:bg-red-100">
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

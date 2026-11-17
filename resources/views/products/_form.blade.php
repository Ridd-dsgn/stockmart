@csrf

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div>
        <label class="block text-sm font-medium">Kode Barang</label>
        <input type="text" name="code" value="{{ old('code', $product?->code) }}"
               class="mt-1 w-full rounded border-gray-300">
        @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Nama Barang</label>
        <input type="text" name="name" value="{{ old('name', $product?->name) }}"
               class="mt-1 w-full rounded border-gray-300">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Kategori</label>
        <select name="category_id" class="mt-1 w-full rounded border-gray-300">
            <option value="">-- Pilih kategori --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(old('category_id', $product?->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Satuan</label>
        <select name="unit" class="mt-1 w-full rounded border-gray-300">
            <option value="">-- Pilih satuan --</option>
            @foreach ($units as $unit)
                <option value="{{ $unit }}" @selected(old('unit', $product?->unit) === $unit)>{{ $unit }}</option>
            @endforeach
        </select>
        @error('unit') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Harga Beli (Rp)</label>
        <input type="number" min="0" name="buy_price" value="{{ old('buy_price', $product?->buy_price ?? 0) }}"
               class="mt-1 w-full rounded border-gray-300">
        @error('buy_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Harga Jual (Rp)</label>
        <input type="number" min="0" name="sell_price" value="{{ old('sell_price', $product?->sell_price ?? 0) }}"
               class="mt-1 w-full rounded border-gray-300">
        @error('sell_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        @if ($product)
            <label class="block text-sm font-medium">Stok Saat Ini</label>
            <div class="mt-1 rounded bg-gray-100 px-3 py-2 text-sm">{{ $product->stock }}</div>
            <p class="mt-1 text-xs text-gray-500">Stok berubah lewat transaksi barang masuk/keluar.</p>
        @else
            <label class="block text-sm font-medium">Stok Awal</label>
            <input type="number" min="0" name="stock" value="{{ old('stock', 0) }}"
                   class="mt-1 w-full rounded border-gray-300">
            @error('stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium">Stok Minimum</label>
        <input type="number" min="0" name="min_stock" value="{{ old('min_stock', $product?->min_stock ?? 0) }}"
               class="mt-1 w-full rounded border-gray-300">
        @error('min_stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium">Foto Barang <span class="font-normal text-gray-500">(opsional, maks 2 MB)</span></label>
        @if ($product?->photo)
            <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}"
                 class="mt-2 h-24 w-24 rounded object-cover">
            <p class="mt-1 text-xs text-gray-500">Pilih file baru untuk mengganti foto.</p>
        @endif
        <input type="file" name="photo" accept="image/*" class="mt-2 block w-full text-sm">
        @error('photo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex gap-2 pt-2 md:col-span-2">
        <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Simpan</button>
        <a href="{{ route('products.index') }}" class="rounded bg-gray-200 px-4 py-2 text-sm">Batal</a>
    </div>
</div>
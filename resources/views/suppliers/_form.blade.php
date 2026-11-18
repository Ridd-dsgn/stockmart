@csrf

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Nama Supplier</label>
        <input type="text" name="name" value="{{ old('name', $supplier?->name) }}"
               class="mt-1 w-full rounded border-gray-300">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $supplier?->phone) }}"
               class="mt-1 w-full rounded border-gray-300">
        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" value="{{ old('email', $supplier?->email) }}"
               class="mt-1 w-full rounded border-gray-300">
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Alamat</label>
        <textarea name="address" rows="3"
                  class="mt-1 w-full rounded border-gray-300">{{ old('address', $supplier?->address) }}</textarea>
        @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex gap-2 pt-2">
        <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Simpan</button>
        <a href="{{ route('suppliers.index') }}" class="rounded bg-gray-200 px-4 py-2 text-sm">Batal</a>
    </div>
</div>
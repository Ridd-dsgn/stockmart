@csrf

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Nama</label>
        <input type="text" name="name" value="{{ old('name', $category?->name) }}"
               class="mt-1 w-full rounded border-gray-300">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Deskripsi</label>
        <textarea name="description" rows="3"
                  class="mt-1 w-full rounded border-gray-300">{{ old('description', $category?->description) }}</textarea>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex gap-2 pt-2">
        <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Simpan</button>
        <a href="{{ route('categories.index') }}" class="rounded bg-gray-200 px-4 py-2 text-sm">Batal</a>
    </div>
</div>
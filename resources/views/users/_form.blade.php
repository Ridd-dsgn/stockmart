@csrf

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user?->name) }}"
               class="mt-1 w-full rounded border-gray-300">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" value="{{ old('email', $user?->email) }}"
               class="mt-1 w-full rounded border-gray-300">
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Role</label>
        <select name="role" class="mt-1 w-full rounded border-gray-300">
            @foreach ($roles as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $user?->role) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">
            Password @if ($user) <span class="font-normal text-gray-500">(kosongkan jika tidak diganti)</span> @endif
        </label>
        <input type="password" name="password" class="mt-1 w-full rounded border-gray-300">
        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="mt-1 w-full rounded border-gray-300">
    </div>

    <div class="flex gap-2 pt-2">
        <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Simpan</button>
        <a href="{{ route('users.index') }}" class="rounded bg-gray-200 px-4 py-2 text-sm">Batal</a>
    </div>
</div>
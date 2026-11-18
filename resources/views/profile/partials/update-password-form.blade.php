
<section>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">
            Keamanan Akun
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Ganti password secara berkala untuk menjaga keamanan akun.
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password"
                class="mb-2 block text-sm font-semibold text-gray-700">
                Password Lama
            </label>
            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                autocomplete="current-password"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                placeholder="Masukkan password lama"
            >
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password"
                class="mb-2 block text-sm font-semibold text-gray-700">
                Password Baru
            </label>
            <input
                id="update_password_password"
                name="password"
                type="password"
                autocomplete="new-password"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                placeholder="Masukkan password baru"
            >
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation"
                class="mb-2 block text-sm font-semibold text-gray-700">
                Konfirmasi Password Baru
            </label>
            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                placeholder="Ulangi password baru"
            >
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-blue-700">
                Ganti Password
            </button>

            @if (session('status') === 'password-updated')
                <span class="text-sm font-medium text-green-600">
                    Password berhasil diperbarui!
                </span>
            @endif
        </div>
    </form>
</section>

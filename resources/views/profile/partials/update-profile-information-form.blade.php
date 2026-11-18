
<section>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">
            Informasi Profil
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Perbarui nama dan alamat email akun StockMart kamu.
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">
                Nama Lengkap
            </label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                placeholder="Masukkan nama lengkap"
            >
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                Alamat Email
            </label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                required
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                placeholder="Masukkan email"
            >
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="mt-3 text-sm text-orange-600">
                    Email kamu belum diverifikasi.
                    <button form="send-verification" class="font-semibold underline">
                        Kirim ulang email verifikasi
                    </button>
                </p>
            @endif

            @if (session('status') === 'verification-link-sent')
                <p class="mt-2 text-sm text-green-600">
                    Email verifikasi berhasil dikirim.
                </p>
            @endif
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-blue-700">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-sm font-medium text-green-600">
                    Profil berhasil diperbarui!
                </span>
            @endif
        </div>
    </form>
</section>

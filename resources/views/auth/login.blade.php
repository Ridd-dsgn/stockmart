<x-guest-layout>
    <p class="mt-5 text-center text-sm text-spark-muted">
        Silakan masuk untuk mengakses dashboard Anda
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mt-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-spark-text">Alamat Email</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-spark-muted">
                    <x-icon name="mail" class="h-5 w-5" />
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autofocus autocomplete="username" placeholder="nama@stockmart.test"
                       class="h-14 w-full rounded-2xl border-0 bg-spark-bg pl-12 pr-4 text-sm text-spark-text placeholder:text-spark-muted/70 focus:ring-2 focus:ring-lime-300">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label for="password" class="mb-2 block text-sm font-semibold text-spark-text">Kata Sandi</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-spark-muted">
                    <x-icon name="lock" class="h-5 w-5" />
                </span>
                <input id="password" name="password" :type="show ? 'text' : 'password'"
                       required autocomplete="current-password" placeholder="••••••••"
                       class="h-14 w-full rounded-2xl border-0 bg-spark-bg pl-12 pr-12 text-sm text-spark-text placeholder:text-spark-muted/70 focus:ring-2 focus:ring-lime-300">
                <button type="button" @click="show = !show" tabindex="-1"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-spark-muted transition hover:text-spark-text"
                        :title="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                    <span x-show="!show"><x-icon name="eye" class="h-5 w-5" /></span>
                    <span x-show="show" x-cloak><x-icon name="eye-off" class="h-5 w-5" /></span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Ingat saya & lupa sandi -->
        <div class="flex items-center justify-between text-sm">
            <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2 text-spark-muted">
                <input id="remember_me" type="checkbox" name="remember"
                       class="h-4 w-4 rounded border-slate-300 text-spark shadow-sm focus:ring-lime-300">
                Ingat saya
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="font-semibold text-spark-text hover:underline">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <!-- Tombol masuk -->
        <button type="submit"
                class="flex h-14 w-full items-center justify-center gap-2 rounded-2xl text-sm font-semibold text-white transition hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-lime-300 focus:ring-offset-2"
                style="background: var(--spark-dark);">
            Masuk
        </button>
    </form>
</x-guest-layout>

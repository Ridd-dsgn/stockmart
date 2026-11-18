
@extends('layouts.main')

@section('title', 'Profil Saya')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Profil Saya</h1>
        <p class="mt-1 text-sm text-slate-500">
            Kelola informasi akun dan keamanan akun StockMart.
        </p>
    </div>

    {{-- Kartu Identitas Pengguna --}}
    <div class="mb-6 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
        <div class="h-28 bg-gradient-to-r from-blue-600 to-indigo-700"></div>

        <div class="px-6 pb-6">
            <div class="-mt-12 flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-blue-100 text-3xl font-bold text-blue-700 shadow">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div class="flex-1 pt-2">
                    <h2 class="text-xl font-bold text-slate-800">
                        {{ $user->name }}
                    </h2>

                    <p class="text-sm text-slate-500">
                        {{ $user->email }}
                    </p>
                </div>

                <div>
                    <span class="inline-flex rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">
                        {{ ucwords(str_replace('_', ' ', $user->role ?? 'Pengguna')) }}
                    </span>
                </div>
            </div>

            <div class="mt-6 grid gap-4 border-t border-slate-100 pt-5 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nama Pengguna
                    </p>
                    <p class="mt-1 font-semibold text-slate-700">
                        {{ $user->name }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Email
                    </p>
                    <p class="mt-1 font-semibold text-slate-700">
                        {{ $user->email }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Hak Akses
                    </p>
                    <p class="mt-1 font-semibold text-slate-700">
                        {{ ucwords(str_replace('_', ' ', $user->role ?? 'Pengguna')) }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Akun Dibuat
                    </p>
                    <p class="mt-1 font-semibold text-slate-700">
                        {{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Informasi Profil --}}
    <div class="mb-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
        @include('profile.partials.update-profile-information-form')
    </div>

    {{-- Form Keamanan Akun --}}
    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
        @include('profile.partials.update-password-form')
    </div>
@endsection
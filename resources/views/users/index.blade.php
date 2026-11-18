
@extends('layouts.main')

@section('title', 'Manajemen Pengguna')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Pengguna</h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola akun dan hak akses pengguna StockMart.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            <span class="text-lg">+</span>
            Tambah Pengguna
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('status'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Pengguna</p>
                    <h2 class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $users->total() }}
                    </h2>
                    <p class="mt-1 text-xs text-slate-400">Seluruh akun terdaftar</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-2xl">
                    👥
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Ditampilkan</p>
                    <h2 class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $users->count() }}
                    </h2>
                    <p class="mt-1 text-xs text-slate-400">Pengguna pada halaman ini</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-2xl">
                    📋
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Pengguna --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-slate-800">Daftar Pengguna</h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Informasi akun dan hak akses pengguna.
                    </p>
                </div>

                <form method="GET" action="{{ route('users.index') }}"
                      class="flex flex-col gap-2 sm:flex-row">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari nama atau email..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500 sm:w-64"
                    >

                    <button type="submit"
                            class="rounded-xl bg-slate-800 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-700">
                        Cari
                    </button>

                    @if(request('q'))
                        <a href="{{ route('users.index') }}"
                           class="rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm text-slate-600 hover:bg-slate-50">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Pengguna</th>
                        <th class="px-5 py-4">Email</th>
                        <th class="px-5 py-4">Role / Hak Akses</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4 text-slate-500">
                                {{ $users->firstItem() + $loop->index }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $user->name }}
                                            @if(auth()->id() === $user->id)
                                                <span class="ml-1 text-xs font-normal text-blue-600">(Anda)</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-400">ID: {{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                {{ $user->email }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                @php
                                    $roleColor = match($user->role) {
                                        'admin' => 'bg-purple-100 text-purple-700',
                                        'petugas_gudang' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $roleColor }}">
                                    {{ \App\Models\User::ROLES[$user->role] ?? str_replace('_', ' ', ucfirst($user->role)) }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <a href="{{ route('users.edit', $user) }}"
                                   class="inline-flex rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100">
                                    Edit
                                </a>

                                @if(auth()->id() !== $user->id)
                                    <form method="POST"
                                          action="{{ route('users.destroy', $user) }}"
                                          class="ml-1 inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-100">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <div class="text-4xl">👥</div>
                                <p class="mt-3 font-semibold text-slate-700">
                                    Pengguna tidak ditemukan
                                </p>
                                <p class="mt-1 text-sm text-slate-400">
                                    Coba gunakan kata kunci pencarian lainnya.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $users->links() }}
            </div>
        @endif

    </div>
@endsection

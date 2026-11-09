@extends('layouts.main')

@section('title', 'Pengguna')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Pengguna</h1>
        <a href="{{ route('users.create') }}"
           class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">Tambah Pengguna</a>
    </div>

    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..."
               class="w-72 rounded border-gray-300 text-sm">
        <button class="rounded bg-gray-800 px-3 py-2 text-sm text-white">Cari</button>
    </form>

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3">{{ $users->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ \App\Models\User::ROLES[$user->role] ?? $user->role }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('users.destroy', $user) }}" class="ml-3 inline"
                                  onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
@endsection
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->q, fn ($query, $s) => $query->where(
                fn ($w) => $w->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%")
            ))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create', ['roles' => User::ROLES]);
    }

    public function store(StoreUserRequest $request)
    {
        User::create($request->validated()); // password otomatis di-hash oleh cast 'hashed'

        return redirect()->route('users.index')->with('status', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user, 'roles' => User::ROLES]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($user->is($request->user()) && $data['role'] !== 'admin') {
            return back()->with('error', 'Anda tidak bisa mengubah role akun sendiri.');
        }

        if (empty($data['password'])) {
            unset($data['password']); // kosong = password tidak diganti
        }

        $user->update($data);

        return redirect()->route('users.index')->with('status', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        try {
            $user->delete();
        } catch (QueryException) {
            return back()->with('error', 'Pengguna tidak bisa dihapus karena sudah punya riwayat transaksi.');
        }

        return redirect()->route('users.index')->with('status', 'Pengguna berhasil dihapus.');
    }
}
@extends('layouts.main')

@section('title', 'Tambah Pengguna')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">Tambah Pengguna</h1>

    <form method="POST" action="{{ route('users.store') }}" class="max-w-xl rounded bg-white p-6 shadow">
        @include('users._form', ['user' => null])
    </form>
@endsection
@extends('layouts.main')

@section('title', 'Edit Pengguna')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">Edit Pengguna</h1>

    <form method="POST" action="{{ route('users.update', $user) }}" class="max-w-xl rounded bg-white p-6 shadow">
        @method('PUT')
        @include('users._form')
    </form>
@endsection
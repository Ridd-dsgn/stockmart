@extends('layouts.main')

@section('title', 'Tambah Kategori')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">Tambah Kategori</h1>

    <form method="POST" action="{{ route('categories.store') }}" class="max-w-xl rounded bg-white p-6 shadow">
        @include('categories._form', ['category' => null])
    </form>
@endsection
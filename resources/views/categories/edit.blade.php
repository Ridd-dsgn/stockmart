@extends('layouts.main')

@section('title', 'Edit Kategori')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">Edit Kategori</h1>

    <form method="POST" action="{{ route('categories.update', $category) }}" class="max-w-xl rounded bg-white p-6 shadow">
        @method('PUT')
        @include('categories._form')
    </form>
@endsection
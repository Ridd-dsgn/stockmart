@extends('layouts.main')

@section('title', 'Edit Barang')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">Edit Barang</h1>

    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data"
          class="max-w-3xl rounded bg-white p-6 shadow">
        @method('PUT')
        @include('products._form')
    </form>
@endsection
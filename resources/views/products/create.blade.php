@extends('layouts.main')

@section('title', 'Tambah Barang')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">Tambah Barang</h1>

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"
          class="max-w-3xl rounded bg-white p-6 shadow">
        @include('products._form', ['product' => null])
    </form>
@endsection
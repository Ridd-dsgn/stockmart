@extends('layouts.main')

@section('title', 'Tambah Supplier')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">Tambah Supplier</h1>

    <form method="POST" action="{{ route('suppliers.store') }}" class="max-w-xl rounded bg-white p-6 shadow">
        @include('suppliers._form', ['supplier' => null])
    </form>
@endsection
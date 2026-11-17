@extends('layouts.main')

@section('title', 'Edit Supplier')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">Edit Supplier</h1>

    <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="max-w-xl rounded bg-white p-6 shadow">
        @method('PUT')
        @include('suppliers._form')
    </form>
@endsection
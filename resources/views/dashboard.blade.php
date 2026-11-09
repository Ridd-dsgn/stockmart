@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">Dashboard</h1>
    <p>Halo, {{ auth()->user()->name }} ({{ str_replace('_', ' ', auth()->user()->role) }}).</p>
@endsection
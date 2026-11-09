@extends('layouts.main')

@section('title', 'Profil')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">Profil Saya</h1>

    <div class="max-w-xl space-y-6">
        <div class="rounded bg-white p-6 shadow">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="rounded bg-white p-6 shadow">
            @include('profile.partials.update-password-form')
        </div>
    </div>
@endsection
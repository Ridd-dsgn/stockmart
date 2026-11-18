<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'StockMart') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="spark font-sans antialiased">
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">

            {{-- Glow lime di pojok --}}
            <div class="pointer-events-none absolute -left-24 -top-24 h-96 w-96 rounded-full"
                 style="background: radial-gradient(circle, rgb(182 245 0 / 0.22) 0%, rgb(182 245 0 / 0) 70%);"></div>
            <div class="pointer-events-none absolute -bottom-32 -right-24 h-[28rem] w-[28rem] rounded-full"
                 style="background: radial-gradient(circle, rgb(182 245 0 / 0.22) 0%, rgb(182 245 0 / 0) 70%);"></div>

            {{-- Kartu --}}
            <div class="relative w-full max-w-md rounded-3xl bg-white px-8 py-10 sm:px-10"
                 style="box-shadow: 0 10px 40px rgb(11 51 33 / 0.08);">

                <a href="/" class="flex items-center justify-center gap-3" style="color: var(--spark-dark);">
                    <x-spark-logo class="h-9 w-9" />
                    <span class="text-3xl font-extrabold tracking-tight">StockMart</span>
                </a>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>

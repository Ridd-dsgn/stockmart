<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'StockMart') | StockMart</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-800">
    @php
        $menu = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'can' => null],
            ['label' => 'Barang', 'route' => 'products.index', 'can' => null],
            ['label' => 'Kategori', 'route' => 'categories.index', 'can' => null],
            ['label' => 'Supplier', 'route' => 'suppliers.index', 'can' => null],
            ['label' => 'Barang Masuk', 'route' => 'stock-ins.index', 'can' => null],
            ['label' => 'Barang Keluar', 'route' => 'stock-outs.index', 'can' => null],
            ['label' => 'Monitoring Stok', 'route' => 'stocks.index', 'can' => null],
            ['label' => 'Laporan', 'route' => 'reports.index', 'can' => 'view-report'],
            ['label' => 'Pengguna', 'route' => 'users.index', 'can' => 'manage-master'],
            ['label' => 'Activity Log', 'route' => 'activity-logs.index', 'can' => 'manage-master'],
        ];
    @endphp

    <div class="flex min-h-screen">
        <aside class="w-64 shrink-0 bg-gray-900 text-gray-300 flex flex-col">
            <div class="px-6 py-5 text-xl font-bold text-white">StockMart</div>

            <nav class="flex-1 space-y-1 px-3">
                @foreach ($menu as $item)
                    @continue(!Route::has($item['route']))
                    @continue($item['can'] && auth()->user()->cannot($item['can']))
                    <a href="{{ route($item['route']) }}"
                        class="block rounded px-3 py-2 text-sm {{ request()->routeIs(Str::before($item['route'], '.') . '*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-gray-800 px-4 py-4 text-sm">
                <div class="font-medium text-white">{{ auth()->user()->name }}</div>
                <div class="mb-2 text-xs text-gray-400">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
                <a href="{{ route('profile.edit') }}" class="mr-3 hover:text-white">Profil</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-white">Logout</button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-8">
            @session('status')
                <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-800">{{ $value }}</div>
            @endsession

            @session('error')
                <div class="mb-4 rounded bg-red-100 px-4 py-3 text-red-800">{{ $value }}</div>
            @endsession

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>

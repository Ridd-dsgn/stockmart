
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | StockMart</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-800">

@php
    $menu = [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'active' => 'dashboard',
            'icon' => '🏠',
            'can' => null
        ],
        [
            'label' => 'Barang',
            'route' => 'products.index',
            'active' => 'products.*',
            'icon' => '📦',
            'can' => null
        ],
        [
            'label' => 'Kategori',
            'route' => 'categories.index',
            'active' => 'categories.*',
            'icon' => '🗂️',
            'can' => null
        ],
        [
            'label' => 'Supplier',
            'route' => 'suppliers.index',
            'active' => 'suppliers.*',
            'icon' => '🚚',
            'can' => null
        ],
        [
            'label' => 'Barang Masuk',
            'route' => 'stock-ins.index',
            'active' => 'stock-ins.*',
            'icon' => '📥',
            'can' => null
        ],
        [
            'label' => 'Barang Keluar',
            'route' => 'stock-outs.index',
            'active' => 'stock-outs.*',
            'icon' => '📤',
            'can' => null
        ],
        [
            'label' => 'Monitoring Stok',
            'route' => 'stocks.index',
            'active' => 'stocks.*',
            'icon' => '📊',
            'can' => null
        ],
        [
    'label' => 'Hak Akses',
    'route' => 'access-rights.index',
    'active' => 'access-rights.*',
    'icon' => '🔐',
    'can' => null
],
            [
    'label' => 'Laporan',
    'route' => 'reports.index',
    'active' => 'reports.*',
    'icon' => '📑',
    'can' => null
        ],
        [
            'label' => 'Pengguna',
            'route' => 'users.index',
            'active' => 'users.*',
            'icon' => '👥',
            'can' => null
        ],
        [
            'label' => 'Activity Log',
            'route' => 'activity-logs.index',
            'active' => 'activity-logs.*',
            'icon' => '📋',
            'can' => null
        ],
    ];
@endphp

<div class="min-h-screen">

    {{-- Overlay untuk tampilan HP --}}
    <div id="sidebarOverlay"
         class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"
         onclick="toggleSidebar()">
    </div>

    {{-- Sidebar --}}
    <aside id="sidebar"
         class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-slate-900 text-white transition-transform duration-300 lg:translate-x-0">

        {{-- Logo --}}
        <div class="flex h-20 items-center gap-3 border-b border-slate-700 px-6">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-2xl shadow-lg">
                📦
            </div>

            <div>
                <h1 class="text-xl font-bold tracking-wide">StockMart</h1>
                <p class="text-xs text-slate-400">Inventory Management</p>
            </div>
        </div>

        {{-- Navigasi --}}
        <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">

            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-widest text-slate-500">
                Menu Utama
            </p>

           @foreach ($menu as $item)
    @continue(!isset($item['route']) || !Route::has($item['route']))
    @continue(!empty($item['can']) && auth()->user()->cannot($item['can']))
                @php
                    $isActive = request()->routeIs($item['active']);
                @endphp

                <a href="{{ route($item['route']) }}"
                   class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                   {{ $isActive
                        ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/30'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                    <span class="text-lg">{{ $item['icon'] }}</span>

                    <span>{{ $item['label'] }}</span>

                    @if($isActive)
                        <span class="ml-auto h-2 w-2 rounded-full bg-white"></span>
                    @endif
                </a>
            @endforeach

        </nav>

        {{-- Profil pengguna --}}
        <div class="border-t border-slate-700 p-4">

            <div class="mb-4 flex items-center gap-3 rounded-xl bg-slate-800 p-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-600 font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-white">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs capitalize text-slate-400">
                        {{ str_replace('_', ' ', auth()->user()->role) }}
                    </p>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}"
               class="mb-2 flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                <span>⚙️</span>
                Pengaturan Profil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-sm text-red-400 transition hover:bg-red-500/10 hover:text-red-300">
                    <span>🚪</span>
                    Keluar
                </button>
            </form>

        </div>
    </aside>

    {{-- Area Konten --}}
    <div class="min-h-screen lg:ml-72">

        {{-- Header --}}
        <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-slate-200 bg-white px-5 shadow-sm sm:px-8">

            <div class="flex items-center gap-4">

                <button type="button"
                    onclick="toggleSidebar()"
                    class="rounded-lg border border-slate-200 p-2 text-slate-600 hover:bg-slate-100 lg:hidden">
                    ☰
                </button>

                <div>
                    <p class="text-sm font-semibold text-slate-800">
                        @yield('title', 'Dashboard')
                    </p>
                    <p class="hidden text-xs text-slate-400 sm:block">
                        StockMart / @yield('title', 'Dashboard')
                    </p>
                </div>

            </div>

<a href="{{ route('profile.edit') }}"
    class="flex items-center gap-3 rounded-xl px-2 py-2 transition hover:bg-slate-50">

    <div class="hidden text-right sm:block">
        <p class="text-sm font-semibold text-slate-700">
            {{ auth()->user()->name }}
        </p>

        <p class="text-xs text-slate-400">
            {{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}
        </p>

        <p class="text-xs text-blue-600">
            Pengaturan Profil
        </p>
    </div>

    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 font-bold text-lg text-blue-700">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>

</a>

        </header>

        {{-- Isi Halaman --}}
        <main class="p-4 sm:p-6 lg:p-8">

            @session('status')
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
                    ✓ {{ $value }}
                </div>
            @endsession

            @session('error')
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                    ⚠ {{ $value }}
                </div>
            @endsession

            @yield('content')

        </main>

        {{-- Footer --}}
        <footer class="px-8 pb-6 text-center text-xs text-slate-400">
            © {{ date('Y') }} StockMart. Inventory Management System.
        </footer>

    </div>
</div>

{{-- Script Sidebar Mobile --}}
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>

@stack('scripts')

</body>
</html>

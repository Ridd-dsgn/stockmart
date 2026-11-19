<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | StockMart</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="spark">

@php
    $menu = [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'active' => 'dashboard',
            'icon' => 'home',
            'can' => null
        ],
        [
            'label' => 'Barang',
            'route' => 'products.index',
            'active' => 'products.*',
            'icon' => 'box',
            'can' => null
        ],
        [
            'label' => 'Kategori',
            'route' => 'categories.index',
            'active' => 'categories.*',
            'icon' => 'folder',
            'can' => null
        ],
        [
            'label' => 'Supplier',
            'route' => 'suppliers.index',
            'active' => 'suppliers.*',
            'icon' => 'truck',
            'can' => null
        ],
        [
            'label' => 'Barang Masuk',
            'route' => 'stock-ins.index',
            'active' => 'stock-ins.*',
            'icon' => 'download',
            'can' => null
        ],
        [
            'label' => 'Barang Keluar',
            'route' => 'stock-outs.index',
            'active' => 'stock-outs.*',
            'icon' => 'upload',
            'can' => null
        ],
        [
            'label' => 'Monitoring Stok',
            'route' => 'stocks.index',
            'active' => 'stocks.*',
            'icon' => 'chart',
            'can' => null
        ],
        [
    'label' => 'Hak Akses',
    'route' => 'access-rights.index',
    'active' => 'access-rights.*',
    'icon' => 'lock',
    'can' => null
],
            [
    'label' => 'Laporan',
    'route' => 'reports.index',
    'active' => 'reports.*',
    'icon' => 'report',
    'can' => null
        ],
        [
            'label' => 'Pengguna',
            'route' => 'users.index',
            'active' => 'users.*',
            'icon' => 'users',
            'can' => null
        ],
        [
            'label' => 'Activity Log',
            'route' => 'activity-logs.index',
            'active' => 'activity-logs.*',
            'icon' => 'log',
            'can' => null
        ],
    ];
@endphp

@php
    $user = auth()->user();
    $role = $user->role;
    $roleLabel = ucwords(str_replace('_', ' ', $role));

    $allowedMenuRoutes = [
        'admin' => ['dashboard','products.index','categories.index','suppliers.index','stock-ins.index','stock-outs.index','stocks.index','reports.index','users.index','activity-logs.index','access-rights.index'],
        'kepala_toko' => ['dashboard','products.index','categories.index','suppliers.index','stock-ins.index','stock-outs.index','stocks.index','reports.index'],
        'petugas_gudang' => ['dashboard','products.index','categories.index','suppliers.index','stock-ins.index','stock-outs.index','stocks.index'],
    ];

    $groups = [
        'Menu' => ['dashboard'],
        'Data Master' => ['products.index', 'categories.index', 'suppliers.index'],
        'Transaksi' => ['stock-ins.index', 'stock-outs.index', 'stocks.index'],
        'Laporan & Sistem' => ['reports.index', 'users.index', 'access-rights.index', 'activity-logs.index'],
    ];

    $lowStockCount = \App\Models\Product::whereColumn('stock', '<=', 'min_stock')->count();

    // Data untuk popup notifikasi
    $lowStockItems = \App\Models\Product::whereColumn('stock', '<=', 'min_stock')->orderBy('stock')->take(4)->get();
    $recentIns  = \App\Models\StockIn::with(['items.product', 'user'])->latest()->take(3)->get();
    $recentOuts = \App\Models\StockOut::with(['items.product', 'user'])->latest()->take(3)->get();

    $notifMoves = $recentIns->map(fn ($m) => ['type' => 'in', 'model' => $m])
        ->concat($recentOuts->map(fn ($m) => ['type' => 'out', 'model' => $m]))
        ->sortByDesc(fn ($r) => $r['model']->created_at)
        ->take(5)
        ->values();

    $latestMoveAt = optional($notifMoves->first()['model']->created_at ?? null)->timestamp ?? 0;
@endphp

<div class="min-h-screen">

    {{-- Overlay untuk tampilan HP --}}
    <div id="sidebarOverlay"
         class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"
         onclick="toggleSidebar()">
    </div>

    {{-- Sidebar --}}
    <aside id="sidebar"
         class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col text-white transition-transform duration-300 lg:translate-x-0"
         style="background: var(--spark-dark);">

        {{-- Logo --}}
        <div class="flex h-24 items-center gap-3 px-7">
            <x-spark-logo class="spark-star h-9 w-9" />
            <h1 class="text-2xl font-extrabold tracking-tight">StockMart</h1>
        </div>

        {{-- Navigasi --}}
        <nav class="spark-sidebar-scroll flex-1 overflow-y-auto px-4 pb-6">

            @foreach ($groups as $groupLabel => $routes)
                @php
                    $items = collect($menu)->filter(fn ($i) =>
                        isset($i['route'])
                        && in_array($i['route'], $routes)
                        && Route::has($i['route'])
                        && in_array($i['route'], $allowedMenuRoutes[$role] ?? [])
                    );
                @endphp

                @continue($items->isEmpty())

                <p class="mb-2 mt-6 px-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-white/40 first:mt-2">
                    {{ $groupLabel }}
                </p>

                <div class="space-y-1">
                    @foreach ($items as $item)
                        @php $isActive = request()->routeIs($item['active']); @endphp

                        <a href="{{ route($item['route']) }}"
                           class="group flex items-center gap-3.5 rounded-2xl px-4 py-3 text-[15px] font-medium transition
                           {{ $isActive ? 'text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}"
                           @if($isActive) style="background: rgb(255 255 255 / 0.07);" @endif>

                            <x-icon :name="$item['icon']"
                                    class="h-5 w-5 shrink-0 {{ $isActive ? 'spark-star' : '' }}" />

                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endforeach

        </nav>

        {{-- Profil pengguna --}}
        <div class="p-4">
            <div class="flex items-center gap-3 rounded-2xl border border-white/10 p-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full font-bold"
                     style="background: var(--spark-lime); color: var(--spark-dark);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-white">{{ $user->name }}</p>
                    <p class="truncate text-xs text-white/50">{{ $roleLabel }}</p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar"
                            class="rounded-lg p-2 text-white/50 transition hover:bg-white/10 hover:text-white">
                        <x-icon name="logout" class="h-5 w-5" />
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Area Konten --}}
    <div id="spark-content" class="min-h-screen lg:ml-72">

        {{-- Header --}}
        <header class="sticky top-0 z-30 flex h-24 items-center gap-3 border-b border-black/5 px-4 backdrop-blur sm:px-8"
                style="background: rgb(245 245 243 / 0.85);">

            {{-- Tombol sidebar --}}
            <button type="button" onclick="toggleSidebar()" title="Menu"
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white text-slate-700 shadow-sm transition hover:bg-white/70">
                <x-icon name="menu" class="h-5 w-5" />
            </button>

            {{-- Tombol aksi cepat --}}
            @can('manage-master')
                <a href="{{ route('products.create') }}"
                   class="hidden h-11 items-center gap-2 rounded-2xl px-5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90 sm:inline-flex"
                   style="background: var(--spark-dark);">
                    <x-icon name="plus" class="h-4 w-4" />
                    Tambah Barang
                </a>
            @endcan

            {{-- Pencarian --}}
            <form method="GET" action="{{ Route::has('products.index') ? route('products.index') : '#' }}"
                  class="relative ml-2 hidden max-w-xl flex-1 md:block">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Cari barang di StockMart..."
                       class="h-12 w-full rounded-full border-0 bg-white pl-6 pr-12 text-sm text-slate-700 shadow-sm placeholder:text-slate-400 focus:ring-2 focus:ring-lime-300">
                <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700">
                    <x-icon name="search" class="h-5 w-5" />
                </button>
            </form>

            <div class="ml-auto flex items-center gap-2 sm:gap-3">

                {{-- Layar penuh --}}
                <button type="button" onclick="toggleFullscreen()" title="Layar penuh"
                    class="hidden h-11 w-11 items-center justify-center rounded-2xl bg-white text-slate-700 shadow-sm transition hover:bg-white/70 sm:flex">
                    <x-icon name="expand" class="h-5 w-5" />
                </button>

                {{-- Notifikasi (popup) --}}
                <div class="relative"
                     x-data="{
                        open: false,
                        latest: {{ $latestMoveAt }},
                        seen: Number(localStorage.getItem('spark_notif_seen') || 0),
                        get hasNew() { return {{ $lowStockCount }} > 0 || this.latest > this.seen; },
                        toggle() {
                            this.open = !this.open;
                            if (this.open) { this.seen = this.latest; localStorage.setItem('spark_notif_seen', this.latest); }
                        }
                     }"
                     @click.outside="open = false" @keydown.escape.window="open = false">

                    <button type="button" @click="toggle()" title="Notifikasi"
                        class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-slate-700 shadow-sm transition hover:bg-white/70">
                        <x-icon name="bell" class="h-5 w-5" />
                        <span x-show="hasNew" x-cloak
                              class="absolute right-2.5 top-2.5 h-2.5 w-2.5 rounded-full ring-2 ring-white"
                              style="background:#22c55e;"></span>
                    </button>

                    <div x-show="open" x-transition x-cloak
                         class="absolute right-0 mt-3 w-80 overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-black/5 sm:w-96">

                        <div class="flex items-center justify-between px-5 py-4">
                            <h3 class="text-base font-bold text-slate-900">Notifikasi</h3>
                            @if($lowStockCount > 0)
                                <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-600">{{ $lowStockCount }} stok menipis</span>
                            @endif
                        </div>

                        <div class="max-h-96 divide-y divide-slate-100 overflow-y-auto border-t border-slate-100">

                            {{-- Stok menipis --}}
                            @foreach($lowStockItems as $p)
                                <a href="{{ Route::has('stocks.index') ? route('stocks.index') : '#' }}"
                                   class="flex items-start gap-3 px-5 py-3 transition hover:bg-slate-50">
                                    <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                                        <x-icon name="alert" class="h-5 w-5" />
                                    </span>
                                    <div class="min-w-0 text-sm">
                                        <p class="truncate font-semibold text-slate-800">{{ $p->name }}</p>
                                        <p class="text-xs text-slate-500">Stok tinggal {{ $p->stock }} {{ $p->unit }} (min. {{ $p->min_stock }})</p>
                                    </div>
                                </a>
                            @endforeach

                            {{-- Pergerakan terbaru --}}
                            @forelse($notifMoves as $row)
                                @php
                                    $m = $row['model'];
                                    $isIn = $row['type'] === 'in';
                                    $first = $m->items->first();
                                    $qty = $m->items->sum('qty');
                                    $more = $m->items->count() - 1;
                                @endphp
                                <a href="{{ route($isIn ? 'stock-ins.index' : 'stock-outs.index') }}"
                                   class="flex items-start gap-3 px-5 py-3 transition hover:bg-slate-50">
                                    <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $isIn ? 'bg-lime-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        <x-icon :name="$isIn ? 'download' : 'upload'" class="h-5 w-5" />
                                    </span>
                                    <div class="min-w-0 text-sm">
                                        <p class="truncate font-semibold text-slate-800">
                                            {{ $isIn ? 'Barang masuk' : 'Barang keluar' }}: {{ $first?->product?->name ?? '-' }}{{ $more > 0 ? ' +'.$more.' lainnya' : '' }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ $qty }} unit · {{ $m->user?->name ?? 'Sistem' }} · {{ $m->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </a>
                            @empty
                                @if($lowStockItems->isEmpty())
                                    <p class="px-5 py-8 text-center text-sm text-slate-400">Belum ada notifikasi.</p>
                                @endif
                            @endforelse
                        </div>

                        <a href="{{ Route::has('stocks.index') ? route('stocks.index') : '#' }}"
                           class="block border-t border-slate-100 px-5 py-3 text-center text-sm font-semibold text-emerald-800 hover:bg-slate-50">
                            Lihat monitoring stok
                        </a>
                    </div>
                </div>

                {{-- Menu pengguna --}}
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button type="button" @click="open = !open"
                        class="flex items-center gap-3 rounded-2xl py-1 pl-1 pr-2 transition hover:bg-white/60">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full text-base font-bold"
                             style="background: var(--spark-dark); color: var(--spark-lime);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="hidden text-left sm:block">
                            <p class="text-sm font-semibold leading-tight text-slate-800">{{ $user->name }}</p>
                            <p class="text-xs leading-tight text-slate-500">{{ $roleLabel }}</p>
                        </div>
                        <x-icon name="chevron-down" class="hidden h-4 w-4 text-slate-500 sm:block" />
                    </button>

                    <div x-show="open" x-transition x-cloak
                         class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl bg-white p-2 shadow-xl ring-1 ring-black/5">
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
                            <x-icon name="user" class="h-5 w-5 text-slate-400" /> Pengaturan Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm text-red-600 hover:bg-red-50">
                                <x-icon name="logout" class="h-5 w-5" /> Keluar
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        {{-- Isi Halaman --}}
        <main class="p-4 sm:p-6 lg:p-8">

            @session('status')
                <div class="mb-5 rounded-2xl border border-lime-200 bg-lime-50 px-5 py-4 text-sm font-medium text-emerald-800">
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

        if (window.innerWidth >= 1024) {
            document.body.classList.toggle('sb-collapsed');
            return;
        }

        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen?.();
        } else {
            document.exitFullscreen?.();
        }
    }
</script>

@stack('scripts')

</body>
</html>
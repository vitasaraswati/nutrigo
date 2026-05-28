<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'NutriGo')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f0e8] text-slate-700" x-data>
@php
    $adminNav = [
        ['route'=>'admin.dashboard', 'label'=>'Dashboard'],
        ['route'=>'admin.users.index', 'label'=>'Users'],
        ['route'=>'admin.foods.index', 'label'=>'Makanan'],
        ['route'=>'admin.articles.index', 'label'=>'Artikel'],
    ];

    $avatarInitial = strtoupper(substr(auth()->user()->name ?? 'A', 0, 1));
@endphp

<div class="relative min-h-screen overflow-x-hidden">
    <div class="pointer-events-none absolute -left-16 top-20 h-56 w-56 rounded-full bg-[#9abc05]/40 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-16 bottom-20 h-56 w-56 rounded-full bg-[#f1c926]/45 blur-3xl"></div>

    <header class="fixed left-0 right-0 top-0 z-40 border-b border-[#e8dcc8] bg-[#f3e8cc]/95 backdrop-blur">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-extrabold tracking-tight text-[#d52518]">
                    NutriGo <span class="text-[#185420]">Admin</span>
                </a>

                <nav class="hidden items-center gap-5 md:flex">
                    @foreach($adminNav as $item)
                        <a href="{{ route($item['route']) }}"
                           class="border-b-2 pb-1 text-sm font-semibold transition-colors {{ request()->routeIs($item['route']) ? 'border-[#d52518] text-[#d52518]' : 'border-transparent text-[#6b7280] hover:text-[#185420]' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="flex items-center gap-3">
                <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#f96015] text-sm font-bold text-white shadow-sm">
                    {{ $avatarInitial }}
                </div>

                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button class="rounded-full border border-[#d8ccb6] px-3 py-1.5 text-xs font-semibold text-[#6b7280] transition hover:bg-white hover:text-[#d52518]">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="relative mx-auto w-full max-w-7xl px-4 pb-8 pt-24 sm:px-6 lg:px-8">
        <h1 class="mb-4 text-xl font-bold text-[#185420]">@yield('page-title', 'Admin Panel')</h1>

        @if(session('success'))
            <div class="mb-5 rounded-2xl border border-[#b4d86d] bg-[#ecf7ce] px-4 py-3 text-sm font-medium text-[#185420]">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>
<x-flash />
@stack('scripts')
</body>
</html>
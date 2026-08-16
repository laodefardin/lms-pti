<!DOCTYPE html>
<html lang="id" x-init="$store.theme.init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} — LMS PTI Unsulbar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
<div class="lms-layout">

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
            <div>
                <div class="sidebar-logo-text">LMS PTI</div>
                <div class="sidebar-logo-sub">Portal Dosen</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Menu Dosen</div>
            @php
            $nav = [
                ['route'=>'dosen.dashboard',       'label'=>'Dashboard',    'path'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route'=>'dosen.matakuliah.index','label'=>'Matakuliah',   'path'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['route'=>'dosen.pengumuman.index','label'=>'Pengumuman',   'path'=>'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
            ];
            $nav2 = [
                ['route'=>'dosen.profil',          'label'=>'Profil',       'path'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ];
            @endphp

            @foreach($nav as $item)
            <a href="{{ route($item['route']) }}" class="nav-item {{ request()->routeIs($item['route'].'*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['path'] }}"/></svg>
                {{ $item['label'] }}
            </a>
            @endforeach

            <div class="sidebar-section-label" style="margin-top:0.5rem;">Akun</div>
            @foreach($nav2 as $item)
            <a href="{{ route($item['route']) }}" class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['path'] }}"/></svg>
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>

        <div class="sidebar-user" x-data="{open:false}" @click="open=!open" style="position:relative;">
            <img src="{{ auth()->user()->foto_url }}" alt="Avatar" class="sidebar-avatar">
            <div style="flex:1; min-width:0;">
                <div class="sidebar-user-name" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role" style="color:#14a7a0;">Dosen</div>
            </div>
            <div x-show="open" x-transition @click.outside="open=false"
                 style="position:absolute; bottom:70px; left:12px; right:12px; background:#252840; border:1px solid rgba(255,255,255,0.1); border-radius:12px; overflow:hidden; z-index:100;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="width:100%; display:flex; align-items:center; gap:0.6rem; padding:0.7rem 1rem; font-size:0.8rem; color:#f87171; background:none; border:none; cursor:pointer; text-align:left;">🚪 Keluar</button>
                </form>
            </div>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">{{ $title ?? 'Dashboard' }}</span>
            <div class="topbar-actions">
                <livewire:notification-center />
                @include('components.theme-toggle')
                <div style="display:flex; align-items:center; gap:0.4rem; background:var(--teal-dim); border:1px solid var(--border-teal); border-radius:99px; padding:0.28rem 0.7rem;">
                    <span style="font-size:0.85rem;">👨‍🏫</span>
                    <span style="font-size:0.75rem; font-weight:600; color:var(--teal);">Dosen</span>
                </div>
            </div>
        </header>
        <main class="page-content">{{ $slot }}</main>
    </div>
</div>
@livewireScripts
</body>
</html>

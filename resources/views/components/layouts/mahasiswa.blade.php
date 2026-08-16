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

    {{-- ════════════════════════════ SIDEBAR ════════════════════════════ --}}
    <aside class="sidebar" id="sidebar" x-data="{ open: false }">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <div class="sidebar-logo-text">LMS PTI</div>
                <div class="sidebar-logo-sub">Unsulbar</div>
            </div>
        </div>

        {{-- Nav Items --}}
        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Menu Utama</div>

            @php
            $nav = [
                ['route'=>'mahasiswa.dashboard',     'label'=>'Dashboard',      'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>'],
                ['route'=>'mahasiswa.matakuliah.index','label'=>'Matakuliah Saya','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>'],
                ['route'=>'mahasiswa.tugas.index',   'label'=>'Tugas',          'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>'],
                ['route'=>'mahasiswa.kuis.index',    'label'=>'Kuis & Ujian',   'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>'],
                ['route'=>'mahasiswa.absensi.index', 'label'=>'Absensi',        'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ['route'=>'mahasiswa.nilai.index',   'label'=>'Nilai',          'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
            ];
            $nav2 = [
                ['route'=>'mahasiswa.forum.index',   'label'=>'Forum Diskusi',  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>'],
                ['route'=>'mahasiswa.kalender.index','label'=>'Kalender',       'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
                ['route'=>'mahasiswa.leaderboard',   'label'=>'Leaderboard',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>'],
            ];
        @endphp

        @foreach($nav as $item)
            <a href="{{ route($item['route']) }}"
               class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $item['icon'] !!}</svg>
                {{ $item['label'] }}
            </a>
        @endforeach

        <div class="sidebar-section-label" style="margin-top:0.5rem;">Komunitas</div>

        @foreach($nav2 as $item)
            <a href="{{ route($item['route']) }}"
               class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $item['icon'] !!}</svg>
                {{ $item['label'] }}
            </a>
        @endforeach
        </nav>

        {{-- User Info --}}
        <div class="sidebar-user" x-data="{ open:false }" @click="open=!open">
            <img src="{{ auth()->user()->foto_url }}" alt="Avatar" class="sidebar-avatar">
            <div style="flex:1; min-width:0;">
                <div class="sidebar-user-name" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">Mahasiswa</div>
            </div>
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#8b95a8; flex-shrink:0; transition:transform 0.2s;" :style="open ? 'transform:rotate(180deg)' : ''">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>

            {{-- Dropdown --}}
            <div x-show="open" x-transition @click.outside="open=false"
                 style="position:absolute; bottom:70px; left:12px; right:12px; background:#252840; border:1px solid rgba(255,255,255,0.1); border-radius:12px; overflow:hidden; z-index:100;">
                <a href="{{ route('mahasiswa.profil') }}" style="display:flex; align-items:center; gap:0.6rem; padding:0.7rem 1rem; font-size:0.8rem; color:#f0f4f8; text-decoration:none;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                    👤 Profil Saya
                </a>
                <div style="height:1px; background:rgba(255,255,255,0.07);"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="width:100%; display:flex; align-items:center; gap:0.6rem; padding:0.7rem 1rem; font-size:0.8rem; color:#f87171; background:none; border:none; cursor:pointer; text-align:left;" onmouseover="this.style.background='rgba(239,68,68,0.08)'" onmouseout="this.style.background='transparent'">
                        🚪 Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ════════════════════════════ MAIN ════════════════════════════ --}}
    <div class="main-content">

        {{-- Topbar --}}
        <header class="topbar">
            <div style="display:flex; align-items:center; gap:1rem;">
                <button onclick="document.getElementById('sidebar').classList.toggle('open')"
                        style="display:none; background:none; border:none; cursor:pointer; color:#8b95a8; padding:0.25rem;" class="md:hidden">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <span class="topbar-title">{{ $title ?? 'Dashboard' }}</span>
            </div>

            <div class="topbar-actions">
                {{-- Poin Gamifikasi --}}
                <div style="display:flex; align-items:center; gap:0.4rem; background:var(--teal-dim); border:1px solid var(--border-teal); border-radius:99px; padding:0.28rem 0.7rem;">
                    <span style="font-size:0.85rem;">⭐</span>
                    <span style="font-size:0.75rem; font-weight:600; color:var(--teal);">{{ auth()->user()->totalPoin() }} Poin</span>
                </div>

                {{-- Theme Toggle --}}
                @include('components.theme-toggle')

                {{-- Notifikasi --}}
                <div style="position:relative;">
                    <button class="notif-btn" id="notif-btn">
                        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                        <span class="notif-dot"></span>
                        @endif
                    </button>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="page-content">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
<script>
function toggleNotif() {
    // Will be handled by Livewire notification component
}
</script>
</body>
</html>

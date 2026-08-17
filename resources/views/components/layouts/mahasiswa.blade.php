<!DOCTYPE html>
<html lang="id" x-init="$store.theme.init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} — LMS Pendidikan Teknologi Informasi Unsulbar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
        (function() {
            var saved = localStorage.getItem('lms-theme');
            var isDark = saved !== null ? saved === 'dark' : true;
            document.documentElement.classList.add(isDark ? 'dark' : 'light');
        })();
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <div class="sidebar-logo-text">LMS Pendidikan Teknologi Informasi</div>
                <div class="sidebar-logo-sub">Unsulbar</div>
            </div>
        </div>

        {{-- Nav Items --}}
        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Menu Utama</div>

            @php
            $nav = [
                ['route'=>'mahasiswa.dashboard',     'label'=>'Dashboard',      'icon'=>'fas fa-home'],
                ['route'=>'mahasiswa.matakuliah.index','label'=>'Matakuliah Saya','icon'=>'fas fa-book'],
                ['route'=>'mahasiswa.tugas.index',   'label'=>'Tugas',          'icon'=>'fas fa-tasks'],
                ['route'=>'mahasiswa.kuis.index',    'label'=>'Kuis & Ujian',   'icon'=>'fas fa-file-alt'],
                ['route'=>'mahasiswa.absensi.index', 'label'=>'Absensi',        'icon'=>'fas fa-calendar-check'],
                ['route'=>'mahasiswa.nilai.index',   'label'=>'Nilai',          'icon'=>'fas fa-chart-bar'],
            ];
            $nav2 = [
                ['route'=>'mahasiswa.forum.index',   'label'=>'Forum Diskusi',  'icon'=>'fas fa-comments'],
                ['route'=>'mahasiswa.kalender.index','label'=>'Kalender',       'icon'=>'fas fa-calendar-alt'],
                ['route'=>'mahasiswa.leaderboard',   'label'=>'Leaderboard',    'icon'=>'fas fa-trophy'],
            ];
        @endphp

        @foreach($nav as $item)
            <a href="{{ route($item['route']) }}" wire:navigate
               class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                <i class="{{ $item['icon'] }} nav-icon" style="font-size: 1.1rem; width: 1.5rem; text-align: center;"></i>
                {{ $item['label'] }}
            </a>
        @endforeach

        <div class="sidebar-section-label" style="margin-top:0.5rem;">Komunitas</div>

        @foreach($nav2 as $item)
            <a href="{{ route($item['route']) }}" wire:navigate
               class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                <i class="{{ $item['icon'] }} nav-icon" style="font-size: 1.1rem; width: 1.5rem; text-align: center;"></i>
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
                 style="position:absolute; bottom:70px; left:12px; width:250px; background:var(--bg-card); border:1px solid var(--border); border-radius:12px; overflow:hidden; z-index:100; box-shadow:0 10px 25px rgba(0,0,0,0.1);">
                
                <div style="padding: 1rem; display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid var(--border);">
                    <div style="position: relative;">
                        <img src="{{ auth()->user()->foto_url }}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border);">
                        <div style="position: absolute; top: -2px; right: -2px; background: #3b82f6; color: white; border-radius: 50%; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; font-size: 0.5rem; border: 2px solid var(--bg-card);">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 600; font-size: 0.9rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                
                <div style="padding: 0.5rem 0; border-bottom: 1px solid var(--border);">
                    <a href="{{ route('mahasiswa.profil') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1.25rem; font-size: 0.85rem; color: var(--text-primary); text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='var(--input-bg)'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-user-cog" style="width: 1rem; text-align: center; color: var(--text-secondary);"></i> Pengaturan Profil
                    </a>
                    <a href="#" style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 1.25rem; font-size: 0.85rem; color: var(--text-primary); text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='var(--input-bg)'" onmouseout="this.style.background='transparent'">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <i class="far fa-bell" style="width: 1rem; text-align: center; color: var(--text-secondary);"></i> Notifikasi
                        </div>
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span style="background: #ef4444; color: white; font-size: 0.65rem; font-weight: bold; padding: 0.1rem 0.4rem; border-radius: 99px;">{{ auth()->user()->unreadNotifications()->count() }}</span>
                        @endif
                    </a>
                </div>
                
                <div style="padding: 0.5rem 0;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1.25rem; font-size: 0.85rem; color: #ef4444; background: none; border: none; cursor: pointer; text-align: left; transition: background 0.2s; font-weight: 500;" onmouseover="this.style.background='rgba(239,68,68,0.08)'" onmouseout="this.style.background='transparent'">
                            <i class="fas fa-sign-out-alt" style="width: 1rem; text-align: center;"></i> Keluar
                        </button>
                    </form>
                </div>
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
                    <i class="fas fa-bars"></i>
                </button>
                <span class="topbar-title">{{ $title ?? 'Dashboard' }}</span>
            </div>

            <div class="topbar-actions">
                {{-- User Name --}}
                <div style="display:flex; align-items:center; margin-right:0.5rem;">
                    <span style="font-size:0.85rem; font-weight:600; color:var(--text-primary);">{{ auth()->user()->name }}</span>
                </div>

                {{-- Poin Gamifikasi --}}
                <div style="display:flex; align-items:center; gap:0.4rem; background:var(--teal-dim); border:1px solid var(--border-teal); border-radius:99px; padding:0.28rem 0.7rem;">
                    <span style="font-size:0.85rem;"><i class="fas fa-star" style="color:var(--warning);"></i></span>
                    <span style="font-size:0.75rem; font-weight:600; color:var(--teal);">{{ auth()->user()->totalPoin() }} Poin</span>
                </div>

                {{-- Theme Toggle --}}
                @include('components.theme-toggle')

                {{-- Notifikasi --}}
                <livewire:notification-center />
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

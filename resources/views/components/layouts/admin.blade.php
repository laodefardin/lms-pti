<!DOCTYPE html>
<html lang="id" x-init="$store.theme.init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} — LMS Pendidikan Teknologi Informasi Unsulbar</title>
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

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <div class="sidebar-logo-text">LMS Pendidikan Teknologi Informasi</div>
                <div class="sidebar-logo-sub" style="color:#a78bfa;">Administrator</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Manajemen</div>
            @php
            $nav = [
                ['route'=>'admin.dashboard',      'label'=>'Dashboard',     'icon'=>'fas fa-home'],
                ['route'=>'admin.mahasiswa.index','label'=>'Mahasiswa',     'icon'=>'fas fa-user-graduate'],
                ['route'=>'admin.dosen.index',    'label'=>'Dosen',         'icon'=>'fas fa-chalkboard-teacher'],
                ['route'=>'admin.semester.index', 'label'=>'Semester',      'icon'=>'fas fa-calendar-alt'],
                ['route'=>'admin.mata-kuliah.index','label'=>'Matakuliah',  'icon'=>'fas fa-book'],
                ['route'=>'admin.kelas.index',    'label'=>'Kelas',         'icon'=>'fas fa-users-cog'],
            ];
            $nav2 = [
                ['route'=>'admin.kalender.index',   'label'=>'Kalender',    'icon'=>'fas fa-calendar-day'],
                ['route'=>'admin.pengumuman.index', 'label'=>'Pengumuman',  'icon'=>'fas fa-bullhorn'],
                ['route'=>'admin.laporan.index',    'label'=>'Laporan',     'icon'=>'fas fa-chart-line'],
                ['route'=>'admin.pengaturan.index', 'label'=>'Pengaturan',  'icon'=>'fas fa-cog'],
                ['route'=>'admin.audit-log',        'label'=>'Audit Log',   'icon'=>'fas fa-history'],
            ];
            @endphp

            @foreach($nav as $item)
            <a href="{{ route($item['route']) }}" wire:navigate class="nav-item {{ request()->routeIs($item['route'].'*') ? 'active' : '' }}"
               style="{{ request()->routeIs($item['route'].'*') ? 'background:rgba(139,92,246,0.15); color:#a78bfa;' : '' }}">
                <i class="{{ $item['icon'] }} nav-icon" style="font-size: 1.1rem; width: 1.5rem; text-align: center;"></i>
                {{ $item['label'] }}
            </a>
            @endforeach

            <div class="sidebar-section-label" style="margin-top:0.5rem;">Sistem</div>
            @foreach($nav2 as $item)
            <a href="{{ route($item['route']) }}" wire:navigate class="nav-item {{ request()->routeIs($item['route'].'*') ? 'active' : '' }}"
               style="{{ request()->routeIs($item['route'].'*') ? 'background:rgba(139,92,246,0.15); color:#a78bfa;' : '' }}">
                <i class="{{ $item['icon'] }} nav-icon" style="font-size: 1.1rem; width: 1.5rem; text-align: center;"></i>
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>

        <div class="sidebar-user" x-data="{open:false}" @click="open=!open" style="position:relative;">
            <img src="{{ auth()->user()->foto_url }}" alt="Avatar" class="sidebar-avatar" style="border-color:rgba(139,92,246,0.4);">
            <div style="flex:1; min-width:0;">
                <div class="sidebar-user-name" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role" style="color:#a78bfa;">Administrator</div>
            </div>
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
                    <a href="{{ route('admin.pengaturan.index') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1.25rem; font-size: 0.85rem; color: var(--text-primary); text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='var(--input-bg)'" onmouseout="this.style.background='transparent'">
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

    <div class="main-content">
        <header class="topbar">
            <span class="topbar-title">{{ $title ?? 'Admin Panel' }}</span>
            <div class="topbar-actions">
                <livewire:notification-center />
                @include('components.theme-toggle')
                <div style="display:flex; align-items:center; gap:0.4rem; background:rgba(139,92,246,0.1); border:1px solid rgba(139,92,246,0.25); border-radius:99px; padding:0.28rem 0.7rem;">
                    <span style="font-size:0.85rem;"><i class="fas fa-cog"></i></span>
                    <span style="font-size:0.75rem; font-weight:600; color:#a78bfa;">Admin</span>
                </div>
            </div>
        </header>
        <main class="page-content">{{ $slot }}</main>
    </div>
</div>
@livewireScripts
</body>
</html>

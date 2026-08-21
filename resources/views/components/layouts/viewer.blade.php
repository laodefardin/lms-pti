<!DOCTYPE html>
<html lang="id" x-init="$store.theme.init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $konten->judul ?? 'Materi' }} — {{ $kelas->mataKuliah->nama ?? 'LMS Pendidikan Teknologi Informasi' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        /* Monaco editor container */

        /* Monaco editor container */
        #monaco-editor { width: 100%; min-height: 400px; border-radius: 10px; overflow: hidden; border: 1px solid var(--border); }

        /* Video responsive */
        .video-wrap { position: relative; padding-bottom: 56.25%; height: 0; border-radius: 12px; overflow: hidden; }
        .video-wrap iframe, .video-wrap video { position: absolute; top:0; left:0; width:100%; height:100%; }

        /* Prose content (artikel) */
        .prose-lms { line-height: 1.8; color: var(--text-primary); }
        .prose-lms h1,.prose-lms h2,.prose-lms h3 { font-weight: 700; color: var(--text-primary); margin: 1.5rem 0 0.75rem; }
        .prose-lms p  { margin-bottom: 1rem; }
        .prose-lms ul,.prose-lms ol { padding-left: 1.5rem; margin-bottom: 1rem; }
        .prose-lms li { margin-bottom: 0.35rem; }
        .prose-lms pre { background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 1rem; overflow-x: auto; margin-bottom: 1rem; }
        .prose-lms code { font-size: 0.85em; background: var(--input-bg); padding: 0.15em 0.4em; border-radius: 4px; }
        .prose-lms pre code { background: none; padding: 0; }
        .prose-lms a { color: var(--teal); text-decoration: underline; }
        .prose-lms blockquote { border-left: 3px solid var(--teal); padding-left: 1rem; color: var(--text-secondary); margin: 1rem 0; }
        .prose-lms img { max-width: 100%; border-radius: 8px; margin: 1rem 0; }
        .lms-sidebar-left {
            width: 300px;
            min-width: 300px;
            transition: transform 0.3s ease, width 0.3s ease, min-width 0.3s ease, opacity 0.3s ease;
        }
        .lms-sidebar-left.is-closed {
            width: 0;
            min-width: 0;
            opacity: 0;
            pointer-events: none;
            border-right: none !important;
        }
        .lms-sidebar-right {
            width: 268px;
            min-width: 268px;
            transition: transform 0.3s ease, width 0.3s ease, min-width 0.3s ease, opacity 0.3s ease;
        }
        .lms-sidebar-right.is-closed {
            width: 0;
            min-width: 0;
            opacity: 0;
            pointer-events: none;
            border-left: none !important;
        }
        .lms-mobile-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 40;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        @media (max-width: 768px) {
            .lms-mobile-overlay.is-active {
                display: block;
                opacity: 1;
            }
            .lms-sidebar-left, .lms-sidebar-right {
                position: fixed;
                top: 52px; /* below navbar */
                bottom: 0;
                z-index: 50;
                background: #ffffff !important;
                height: calc(100vh - 52px);
                opacity: 1; /* always opaque when open on mobile */
            }
            .lms-sidebar-left { 
                left: 0; 
                transform: translateX(-100%); 
                width: 280px !important; 
                min-width: 280px !important; 
            }
            .lms-sidebar-left.is-open { 
                transform: translateX(0); 
            }
            .lms-sidebar-left.is-closed {
                /* On mobile, closing just translates it off-screen, width stays 280 so it slides out cleanly */
                width: 280px !important;
                min-width: 280px !important;
                opacity: 1; 
                transform: translateX(-100%);
            }
            
            .lms-sidebar-right { 
                right: 0; 
                transform: translateX(100%); 
                width: 280px !important; 
                min-width: 280px !important; 
            }
            .lms-sidebar-right.is-open { 
                transform: translateX(0); 
            }
            .lms-sidebar-right.is-closed {
                width: 280px !important;
                min-width: 280px !important;
                opacity: 1;
                transform: translateX(100%);
            }
            
            .lms-center-panel { padding: 1.5rem !important; }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>{{-- ═══════════════════════════ VIEWER SHELL ═══════════════════════════ --}}
{{ $slot }}
@livewireScripts
<style>
@keyframes spin { from{transform:rotate(0deg);} to{transform:rotate(360deg);} }
</style>
</body>
</html>

# LMS PTI — Task Checklist

## FASE 1 — FRONTEND

### Auth Pages
- [x] Login page (dark glassmorphism, show/hide pw, quick-fill, loading)
- [x] Forgot password page
- [x] Reset password page
- [x] Role-based redirect setelah login

### Design System
- [x] CSS variables dark/light mode
- [x] Alpine.js theme store (localStorage)
- [x] Theme toggle button (sun/moon) di semua topbar
- [x] Light mode full support
- [x] All components theme-aware

### Layouts
- [x] components/layouts/mahasiswa.blade.php
- [x] components/layouts/dosen.blade.php
- [x] components/layouts/admin.blade.php
- [x] components/layouts/viewer.blade.php (3-panel Codepolitan-style)
- [x] components/layouts/kuis.blade.php (fullscreen exam)
- [x] components/theme-toggle.blade.php

### Mahasiswa Pages
- [x] Dashboard
- [x] Matakuliah Index
- [x] Matakuliah Detail
- [x] Materi Viewer 3-panel
- [x] Kuis Index
- [x] Kuis Engine (timer, auto-save, hasil)
- [x] Tugas Index
- [x] Tugas Detail
- [x] Nilai Index
- [x] Absensi Index
- [x] Kalender Index
- [ ] Forum Index / Kelas / Thread
- [ ] Leaderboard
- [ ] Profil

### Dosen Pages
- [x] Dashboard
- [x] Matakuliah Index
- [x] Matakuliah Detail (tabs)
- [x] Tugas Index
- [x] Tugas Nilai (penilaian inline)
- [x] Absensi Index
- [ ] Materi Buat (Monaco editor)
- [ ] Tugas Buat
- [ ] Kuis Buat (bank soal)

### Admin Pages
- [x] Dashboard
- [ ] Mahasiswa Index (CRUD)
- [ ] Dosen Index (CRUD)
- [ ] Semester, MataKuliah, Kelas CRUD

## FASE 2 — BACKEND LOGIC
- [ ] Progress tracking + gamifikasi poin
- [ ] Auto-compute nilai akhir
- [ ] Notifikasi deadline
- [ ] File upload handler

## FASE 3 — PRODUCTION
- [ ] Final testing
- [ ] Production build

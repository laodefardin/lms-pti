# 🧠 PROJECT MEMORY — LMS PTI Unsulbar

> File ini dibuat untuk membantu AI agent berikutnya memahami konteks proyek secara lengkap.
> **Update file ini setiap kali ada perkembangan penting.**

---

## 📌 Identitas Proyek

| Atribut | Detail |
|---------|--------|
| **Nama Proyek** | LMS PTI — Learning Management System |
| **Program Studi** | Pendidikan Teknologi Informasi (PTI) |
| **Universitas** | Universitas Sulawesi Barat (Unsulbar) |
| **Lokasi Proyek** | /Users/laodefardin/Dosen/webpti/lms-pti |
| **Environment** | Lokal (development), belum deploy ke server |
| **Status** | Belum mulai coding — PRD selesai, siap development |

---

## 🛠️ Stack Teknologi (SUDAH DIPUTUSKAN — JANGAN GANTI)

| Layer | Teknologi |
|-------|-----------|
| Framework | Laravel 11 |
| Reactive UI | Livewire 3 (SPA-like, tanpa reload) |
| JS Ringan | Alpine.js |
| Styling | Tailwind CSS |
| Database | MySQL |
| Auth | Laravel Breeze + spatie/laravel-permission |
| Export | maatwebsite/excel + barryvdh/laravel-dompdf |
| Code Editor | Monaco Editor (NPM) |
| Grafik | ApexCharts |
| Kalender | FullCalendar.js |
| Syntax Highlight | highlight.js |

PENTING: User ingin SPA experience menggunakan Livewire, BUKAN Next.js/Vue/React.

---

## 👥 Peran Pengguna

- mahasiswa: Akses materi, tugas, kuis, nilai, forum
- dosen: Kelola matakuliah, materi, tugas, kuis, nilai, absensi
- admin: Kelola sistem, pengguna, semester, laporan

---

## 🎨 Panduan Desain

- Referensi: Codepolitan.com/dashboard/learn
- Primary Color: Teal #14a7a0
- Font: Inter / Open Sans
- Dark Mode: Ya
- Mobile-first responsive
- Animasi: Smooth Livewire transitions

---

## ✅ Keputusan FINAL (Jangan Diubah)

1. TIDAK ada QR Code absensi — hanya token manual 6 karakter
2. TIDAK ada sertifikat — hanya transkrip nilai + export PDF
3. Stack TALL final — jangan suggest framework lain
4. ADA Live Code Editor (Monaco) — matakuliah pemrograman ada
5. ADA Gamifikasi: poin, badge, leaderboard
6. ADA Forum diskusi + jalur Tanya Dosen
7. 3-Panel Viewer untuk halaman materi
8. Auto-save kuis tiap 30 detik, auto-submit saat waktu habis

---

## 📐 Database: 27 Tabel

users, program_studi, semesters, mata_kuliah, kelas, kelas_mahasiswa,
pertemuan, konten_materi, materi_progress, tugas, pengumpulan_tugas,
kuis, bank_soal, kuis_soal, kuis_sesi, kuis_jawaban, absensi,
absensi_mahasiswa, forum_thread, forum_reply, notifications, nilai_akhir,
catatan_mahasiswa, gamifikasi_poin, pengumuman, kalender_akademik, audit_log

---

## 🔑 Akun Demo Seeding

- Admin: admin@pti.unsulbar.ac.id / admin123
- Dosen: dosen@pti.unsulbar.ac.id / dosen123
- Mahasiswa: mahasiswa@pti.unsulbar.ac.id / mhs123

---

## 📝 Log Sesi

### Sesi 1 — 15 Agustus 2026
- DONE: Analisis Codepolitan dashboard
- DONE: Buat PRD v1.0 dan revisi ke v2.0
- DONE: Tetapkan stack TALL
- DONE: Hapus QR Code dan sertifikat
- DONE: Buat 4 fase pengerjaan dengan 180+ sub-task checklist
- DONE: Buat file memory ini

### Sesi Berikutnya
- TODO: Fase 0 — Install Laravel + setup proyek
- TODO: Buat semua 27 migration
- TODO: Layout dasar + komponen UI

---

## ⚠️ Peringatan untuk AI Berikutnya

1. Stack FINAL: Laravel + Livewire + Alpine + Tailwind. Jangan ganti!
2. TIDAK ada QR Code dan TIDAK ada sertifikat
3. ADA Live Code Editor (Monaco Editor)
4. Environment lokal, belum perlu server cloud
5. Gunakan Livewire untuk semua interaktivitas
6. Folder proyek MASIH KOSONG — belum ada kode
7. PRD lengkap ada di brain conversation: implementation_plan.md

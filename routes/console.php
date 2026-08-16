<?php

use Illuminate\Support\Facades\Schedule;

// ─── Notifikasi Deadline (setiap jam) ─────────────────────────────
Schedule::command('notifikasi:deadline')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();

// ─── Hitung Nilai Akhir Semua Kelas (setiap malam pukul 02:00) ───
Schedule::command('nilai:hitung-semua')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->runInBackground();

// ─── Award Login Harian (setiap hari pukul 00:05) ─────────────────
Schedule::command('gamifikasi:login-harian')
    ->dailyAt('00:05')
    ->withoutOverlapping();

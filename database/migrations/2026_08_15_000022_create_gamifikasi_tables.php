<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gamifikasi_poin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->enum('tipe_aktivitas', [
                'baca_materi',
                'tonton_video',
                'kerjakan_kuis',
                'nilai_sempurna',
                'kumpulkan_tugas',
                'aktif_diskusi',
                'hadir_kuliah',
                'streak_belajar',
            ]);
            $table->unsignedSmallInteger('poin');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('gamifikasi_badge', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('badge_key'); // e.g. "rajin_hadir", "nilai_sempurna"
            $table->string('nama_badge');
            $table->string('icon')->nullable();
            $table->string('warna')->nullable();
            $table->timestamp('diraih_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gamifikasi_badge');
        Schema::dropIfExists('gamifikasi_poin');
    }
};

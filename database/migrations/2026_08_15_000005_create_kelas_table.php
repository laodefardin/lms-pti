<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->string('nama_kelas', 5)->default('A'); // A, B, C, dll.
            $table->string('thumbnail')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('hari_kuliah', ['senin','selasa','rabu','kamis','jumat','sabtu'])->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('ruangan', 50)->nullable();
            // Formula penilaian (total harus 100)
            $table->unsignedTinyInteger('bobot_tugas')->default(20);
            $table->unsignedTinyInteger('bobot_kuis')->default(10);
            $table->unsignedTinyInteger('bobot_kehadiran')->default(10);
            $table->unsignedTinyInteger('bobot_uts')->default(30);
            $table->unsignedTinyInteger('bobot_uas')->default(30);
            $table->unsignedTinyInteger('batas_kehadiran')->default(75); // minimal % kehadiran
            $table->enum('mode_materi', ['semua', 'bertahap'])->default('semua');
            $table->enum('status', ['aktif', 'selesai', 'arsip'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};

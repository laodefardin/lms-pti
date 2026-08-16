<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->text('instruksi')->nullable();
            $table->enum('tipe', ['kuis', 'uts', 'uas'])->default('kuis');
            $table->unsignedSmallInteger('durasi_menit')->default(60);
            $table->dateTime('buka_at')->nullable();
            $table->dateTime('tutup_at')->nullable();
            $table->unsignedTinyInteger('nilai_max')->default(100);
            $table->unsignedTinyInteger('maks_percobaan')->default(1);
            $table->boolean('acak_soal')->default(true);
            $table->boolean('acak_pilihan')->default(true);
            $table->boolean('tampilkan_pembahasan')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis');
    }
};

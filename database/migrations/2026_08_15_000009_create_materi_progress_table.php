<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('konten_id')->constrained('konten_materi')->cascadeOnDelete();
            $table->boolean('is_selesai')->default(false);
            $table->timestamp('selesai_at')->nullable();
            $table->unsignedInteger('durasi_detik')->default(0); // time spent
            $table->timestamps();
            $table->unique(['mahasiswa_id', 'konten_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_progress');
    }
};

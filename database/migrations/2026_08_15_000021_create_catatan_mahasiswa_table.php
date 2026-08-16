<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('konten_id')->constrained('konten_materi')->cascadeOnDelete();
            $table->longText('isi');
            $table->timestamps();
            $table->unique(['mahasiswa_id', 'konten_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_mahasiswa');
    }
};

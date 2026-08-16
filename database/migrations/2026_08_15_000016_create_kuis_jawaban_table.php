<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuis_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_id')->constrained('kuis_sesi')->cascadeOnDelete();
            $table->foreignId('soal_id')->constrained('bank_soal')->cascadeOnDelete();
            $table->json('jawaban')->nullable(); // student's answer(s)
            $table->boolean('is_benar')->nullable(); // null for essay (manual grading)
            $table->decimal('nilai_dapat', 5, 2)->default(0);
            $table->text('catatan_dosen')->nullable(); // for essay grading
            $table->timestamp('dijawab_at')->nullable();
            $table->timestamps();
            $table->unique(['sesi_id', 'soal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis_jawaban');
    }
};

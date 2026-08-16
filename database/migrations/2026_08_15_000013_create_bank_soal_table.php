<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tipe', ['pilihan_ganda', 'multiple_answer', 'benar_salah', 'isian', 'essay', 'menjodohkan']);
            $table->longText('pertanyaan'); // supports HTML with images
            $table->json('opsi')->nullable(); // array of options [{id, teks, gambar?}]
            $table->json('jawaban'); // correct answer(s)
            $table->text('pembahasan')->nullable();
            $table->unsignedTinyInteger('bobot')->default(1);
            $table->string('topik')->nullable(); // for categorization
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_soal');
    }
};

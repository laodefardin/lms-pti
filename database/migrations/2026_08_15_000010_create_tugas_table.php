<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('pertemuan_id')->nullable()->constrained('pertemuan')->nullOnDelete();
            $table->string('judul');
            $table->longText('deskripsi');
            $table->string('file_soal')->nullable(); // uploaded assignment file
            $table->enum('tipe_pengumpulan', ['file', 'link', 'teks', 'file_link'])->default('file');
            $table->string('format_file')->nullable(); // allowed file extensions e.g. "pdf,docx,zip"
            $table->unsignedBigInteger('maks_ukuran_mb')->default(50);
            $table->unsignedTinyInteger('nilai_max')->default(100);
            $table->dateTime('deadline');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};

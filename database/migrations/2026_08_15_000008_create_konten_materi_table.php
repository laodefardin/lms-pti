<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konten_materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->constrained('pertemuan')->cascadeOnDelete();
            $table->enum('tipe', ['video', 'pdf', 'artikel', 'kode', 'link']);
            $table->string('judul');
            $table->longText('konten')->nullable(); // text content / rich text / embed URL
            $table->string('file_path')->nullable(); // uploaded file path
            $table->string('url')->nullable(); // external link / youtube url
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('estimasi_menit')->default(0); // estimated read/watch time
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konten_materi');
    }
};

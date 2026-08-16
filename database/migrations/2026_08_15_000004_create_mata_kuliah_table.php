<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama');
            $table->unsignedTinyInteger('sks')->default(3);
            $table->enum('kategori', ['wajib_umum', 'wajib_prodi', 'pilihan'])->default('wajib_prodi');
            $table->text('deskripsi')->nullable();
            $table->foreignId('prasyarat_id')->nullable()->constrained('mata_kuliah')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->string('link_url')->nullable();
            $table->longText('teks_jawaban')->nullable();
            $table->timestamp('dikumpulkan_at')->useCurrent();
            $table->boolean('is_terlambat')->default(false);
            $table->unsignedTinyInteger('nilai')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('dinilai_at')->nullable();
            $table->enum('status', ['dikirim', 'dinilai'])->default('dikirim');
            $table->timestamps();
            $table->unique(['tugas_id', 'mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};

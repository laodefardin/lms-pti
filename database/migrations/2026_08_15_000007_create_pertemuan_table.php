<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->unsignedTinyInteger('nomor'); // 1-16
            $table->string('topik');
            $table->date('tanggal')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->timestamps();
            $table->unique(['kelas_id', 'nomor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertemuan');
    }
};

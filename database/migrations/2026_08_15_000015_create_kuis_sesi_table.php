<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuis_sesi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_id')->constrained('kuis')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('percobaan_ke')->default(1);
            $table->timestamp('mulai_at')->useCurrent();
            $table->timestamp('selesai_at')->nullable();
            $table->unsignedSmallInteger('sisa_detik')->nullable();
            $table->decimal('nilai', 5, 2)->nullable();
            $table->enum('status', ['berlangsung', 'selesai', 'timeout'])->default('berlangsung');
            $table->json('urutan_soal')->nullable(); // shuffled question order
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis_sesi');
    }
};

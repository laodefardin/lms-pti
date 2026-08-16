<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pivot: soal dalam kuis
        Schema::create('kuis_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_id')->constrained('kuis')->cascadeOnDelete();
            $table->foreignId('bank_soal_id')->constrained('bank_soal')->cascadeOnDelete();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->unique(['kuis_id', 'bank_soal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis_soal');
    }
};

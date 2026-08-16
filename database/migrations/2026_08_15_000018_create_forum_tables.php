<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_thread', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul');
            $table->longText('isi');
            $table->enum('tipe', ['diskusi', 'tanya_dosen'])->default('diskusi');
            $table->boolean('is_terjawab')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });

        Schema::create('forum_reply', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('forum_thread')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('forum_reply')->nullOnDelete();
            $table->longText('isi');
            $table->boolean('is_jawaban_dosen')->default(false);
            $table->boolean('is_jawaban_terpilih')->default(false);
            $table->unsignedSmallInteger('likes')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_reply');
        Schema::dropIfExists('forum_thread');
    }
};

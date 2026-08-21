<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kuis', function (Blueprint $table) {
            $table->foreignId('pertemuan_id')
                  ->nullable()
                  ->after('kelas_id')
                  ->constrained('pertemuan')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kuis', function (Blueprint $table) {
            $table->dropForeign(['pertemuan_id']);
            $table->dropColumn('pertemuan_id');
        });
    }
};

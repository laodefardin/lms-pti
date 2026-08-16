<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim_nidn')->nullable()->unique()->after('name');
            $table->string('foto')->nullable()->after('email');
            $table->enum('role', ['mahasiswa', 'dosen', 'admin'])->default('mahasiswa')->after('foto');
            $table->string('angkatan', 4)->nullable()->after('role'); // khusus mahasiswa
            $table->string('no_hp', 20)->nullable()->after('angkatan');
            $table->text('bio')->nullable()->after('no_hp');
            $table->boolean('is_active')->default(true)->after('bio');
            $table->foreignId('program_studi_id')->nullable()->constrained('program_studi')->nullOnDelete()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim_nidn', 'foto', 'role', 'angkatan', 'no_hp', 'bio', 'is_active', 'program_studi_id']);
        });
    }
};

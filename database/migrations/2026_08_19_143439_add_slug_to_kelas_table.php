<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Kelas;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('slug')->unique()->after('id')->nullable();
        });

        // Generate slugs for existing data
        foreach (Kelas::with('mataKuliah')->get() as $kelas) {
            $slug = Str::slug(($kelas->mataKuliah->nama ?? 'kelas') . '-' . ($kelas->nama_kelas ?? 'a'));
            // Ensure unique slug
            $originalSlug = $slug;
            $count = 1;
            while (Kelas::where('slug', $slug)->where('id', '!=', $kelas->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $kelas->slug = $slug;
            $kelas->save();
        }

        Schema::table('kelas', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};

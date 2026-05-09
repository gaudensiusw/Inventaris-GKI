<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add new categories - only if they don't exist yet
        if (!DB::table('categories')->where('name', 'Alat Multimedia')->exists()) {
            DB::table('categories')->insert([
                'name' => 'Alat Multimedia',
                'description' => 'Peralatan multimedia seperti PC, Mic, Speaker, Mixer, Proyektor, TV',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!DB::table('categories')->where('name', 'Peralatan Dapur')->exists()) {
            DB::table('categories')->insert([
                'name' => 'Peralatan Dapur',
                'description' => 'Peralatan dapur seperti Kompor, Rice Cooker, Panci, Gelas, Piring',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('categories')->where('name', 'Alat Multimedia')->delete();
        DB::table('categories')->where('name', 'Peralatan Dapur')->delete();
    }
};

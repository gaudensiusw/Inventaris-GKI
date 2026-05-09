<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->integer('qty_kembali')->nullable()->after('tgl_kembali_aktual');
            $table->string('kondisi_kembali', 50)->nullable()->after('qty_kembali');
            $table->text('catatan_kembali')->nullable()->after('kondisi_kembali');
        });
    }

    public function down(): void
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->dropColumn(['qty_kembali', 'kondisi_kembali', 'catatan_kembali']);
        });
    }
};

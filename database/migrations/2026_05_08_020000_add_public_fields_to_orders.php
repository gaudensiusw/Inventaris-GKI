<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Make user_id nullable since public users won't have accounts
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Add public submitter fields
            $table->string('nama_peminjam', 255)->after('user_id');
            $table->string('kontak_peminjam', 255)->nullable()->after('nama_peminjam');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['nama_peminjam', 'kontak_peminjam']);
        });
    }
};

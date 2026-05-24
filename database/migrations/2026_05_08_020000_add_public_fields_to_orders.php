<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                // Make user_id nullable since public users won't have accounts
                $table->unsignedBigInteger('user_id')->nullable()->change();

                // Add public submitter fields
                if (!Schema::hasColumn('orders', 'nama_peminjam')) {
                    $table->string('nama_peminjam', 255)->after('user_id');
                }
                if (!Schema::hasColumn('orders', 'kontak_peminjam')) {
                    $table->string('kontak_peminjam', 255)->nullable()->after('nama_peminjam');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn(['nama_peminjam', 'kontak_peminjam']);
            });
        }
    }
};

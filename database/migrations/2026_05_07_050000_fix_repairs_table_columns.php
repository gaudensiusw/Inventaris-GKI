<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('repairs')) {
            Schema::table('repairs', function (Blueprint $table) {
                if (!Schema::hasColumn('repairs', 'id_barang')) {
                    $table->unsignedBigInteger('id_barang')->nullable();
                }
                if (!Schema::hasColumn('repairs', 'qty')) {
                    $table->integer('qty')->default(1);
                }
                if (!Schema::hasColumn('repairs', 'id_vendor')) {
                    $table->string('id_vendor')->nullable();
                }
                if (!Schema::hasColumn('repairs', 'tgl_service')) {
                    $table->date('tgl_service')->nullable();
                }
                if (!Schema::hasColumn('repairs', 'jenis_perbaikan')) {
                    $table->string('jenis_perbaikan')->nullable();
                }
                if (!Schema::hasColumn('repairs', 'status')) {
                    $table->string('status')->default('Proses');
                }
                if (!Schema::hasColumn('repairs', 'estimated_completion')) {
                    $table->date('estimated_completion')->nullable();
                }
                if (!Schema::hasColumn('repairs', 'biaya')) {
                    $table->decimal('biaya', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('repairs', 'id_user')) {
                    $table->unsignedBigInteger('id_user')->nullable();
                }
                if (!Schema::hasColumn('repairs', 'keterangan')) {
                    $table->text('keterangan')->nullable();
                }
            });

            // Copy values from old columns to new columns if they exist
            if (Schema::hasColumn('repairs', 'item_id') && Schema::hasColumn('repairs', 'id_barang')) {
                DB::statement("UPDATE repairs SET id_barang = item_id WHERE id_barang IS NULL AND item_id IS NOT NULL");
            }
            if (Schema::hasColumn('repairs', 'start_date') && Schema::hasColumn('repairs', 'tgl_service')) {
                DB::statement("UPDATE repairs SET tgl_service = start_date WHERE tgl_service IS NULL AND start_date IS NOT NULL");
            }
            if (Schema::hasColumn('repairs', 'estimate_end_date') && Schema::hasColumn('repairs', 'estimated_completion')) {
                DB::statement("UPDATE repairs SET estimated_completion = estimate_end_date WHERE estimated_completion IS NULL AND estimate_end_date IS NOT NULL");
            }
            if (Schema::hasColumn('repairs', 'notes') && Schema::hasColumn('repairs', 'keterangan')) {
                DB::statement("UPDATE repairs SET keterangan = notes WHERE keterangan IS NULL AND notes IS NOT NULL");
            }
            if (Schema::hasColumn('repairs', 'is_completed') && Schema::hasColumn('repairs', 'status')) {
                DB::statement("UPDATE repairs SET status = 'Selesai' WHERE is_completed = 1");
                DB::statement("UPDATE repairs SET status = 'Proses' WHERE is_completed = 0 AND status = 'Proses'");
            }
        }
    }

    public function down(): void
    {
        // Healing migration, no down needed
    }
};

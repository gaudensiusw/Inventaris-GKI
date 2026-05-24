<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) {
                if (!Schema::hasColumn('items', 'slug')) {
                    $table->string('slug')->nullable();
                }
                if (!Schema::hasColumn('items', 'entno')) {
                    $table->string('entno')->nullable();
                }
                if (!Schema::hasColumn('items', 'kode_aset')) {
                    $table->string('kode_aset')->nullable();
                }
                if (!Schema::hasColumn('items', 'barcode')) {
                    $table->string('barcode')->nullable();
                }
                if (!Schema::hasColumn('items', 'image')) {
                    $table->string('image')->nullable();
                }
                if (!Schema::hasColumn('items', 'merk_model')) {
                    $table->string('merk_model')->nullable();
                }
                if (!Schema::hasColumn('items', 'spesifikasi')) {
                    $table->text('spesifikasi')->nullable();
                }
                if (!Schema::hasColumn('items', 'quantity')) {
                    $table->integer('quantity')->default(0);
                }
                if (!Schema::hasColumn('items', 'unit')) {
                    $table->string('unit')->nullable();
                }
                if (!Schema::hasColumn('items', 'satuan')) {
                    $table->string('satuan')->nullable();
                }
                if (!Schema::hasColumn('items', 'qty_diperbaiki')) {
                    $table->integer('qty_diperbaiki')->default(0);
                }
                if (!Schema::hasColumn('items', 'condition')) {
                    $table->string('condition')->nullable();
                }
                if (!Schema::hasColumn('items', 'status')) {
                    $table->string('status')->nullable();
                }
                if (!Schema::hasColumn('items', 'tgl_perolehan')) {
                    $table->date('tgl_perolehan')->nullable();
                }
                if (!Schema::hasColumn('items', 'tgl_habis_garansi')) {
                    $table->date('tgl_habis_garansi')->nullable();
                }
                if (!Schema::hasColumn('items', 'foto_barang')) {
                    $table->string('foto_barang')->nullable();
                }
                if (!Schema::hasColumn('items', 'gambar_barang')) {
                    $table->string('gambar_barang')->nullable();
                }
                if (!Schema::hasColumn('items', 'supplier')) {
                    $table->string('supplier')->nullable();
                }
                if (!Schema::hasColumn('items', 'alamat_supplier')) {
                    $table->text('alamat_supplier')->nullable();
                }
                if (!Schema::hasColumn('items', 'area')) {
                    $table->string('area')->nullable();
                }
                if (!Schema::hasColumn('items', 'sumber_dana')) {
                    $table->string('sumber_dana')->nullable();
                }
                if (!Schema::hasColumn('items', 'keterangan')) {
                    $table->text('keterangan')->nullable();
                }
                if (!Schema::hasColumn('items', 'is_write_off')) {
                    $table->boolean('is_write_off')->default(false);
                }
            });

            // Copy values from old columns to new columns if they exist
            if (Schema::hasColumn('items', 'item_id') && Schema::hasColumn('items', 'kode_aset')) {
                DB::statement("UPDATE items SET kode_aset = item_id WHERE kode_aset IS NULL AND item_id IS NOT NULL");
            }
            if (Schema::hasColumn('items', 'total_qty') && Schema::hasColumn('items', 'quantity')) {
                DB::statement("UPDATE items SET quantity = total_qty WHERE quantity = 0 AND total_qty > 0");
            }
            if (Schema::hasColumn('items', 'qty_perbaikan') && Schema::hasColumn('items', 'qty_diperbaiki')) {
                DB::statement("UPDATE items SET qty_diperbaiki = qty_perbaikan WHERE qty_diperbaiki = 0 AND qty_perbaikan > 0");
            }
            if (Schema::hasColumn('items', 'photo') && Schema::hasColumn('items', 'image')) {
                DB::statement("UPDATE items SET image = photo WHERE image IS NULL AND photo IS NOT NULL");
            }
        }
    }

    public function down(): void
    {
        // Healing migration, no down needed
    }
};

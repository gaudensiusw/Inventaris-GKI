<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('items')) {
            Schema::create('items', function (Blueprint $table) {
                $table->id();
                $table->string('slug')->nullable()->unique();
                $table->string('entno')->nullable();
                $table->string('kode_aset')->nullable()->unique();
                $table->string('barcode')->nullable();
                $table->string('name');
                $table->string('image')->nullable();
                $table->foreignId('category_id')->constrained();
                $table->foreignId('room_id')->constrained();
                $table->string('merk_model')->nullable();
                $table->text('spesifikasi')->nullable();
                $table->integer('quantity')->default(0);
                $table->string('unit')->nullable();
                $table->string('satuan')->nullable();
                
                // Physical Condition Breakdown
                $table->integer('qty_baik')->default(0);
                $table->integer('qty_rusak_ringan')->default(0);
                $table->integer('qty_rusak_berat')->default(0);
                
                // Operational Status Breakdown
                $table->integer('qty_tersedia')->default(0);
                $table->integer('qty_dipinjam')->default(0);
                $table->integer('qty_diperbaiki')->default(0);
                $table->integer('qty_hilang')->default(0);
                $table->integer('qty_tidak_digunakan')->default(0);
                $table->integer('qty_pengadaan')->default(0);
                
                $table->string('condition')->nullable();
                $table->string('status')->nullable();
                $table->date('purchase_date')->nullable();
                $table->date('tgl_perolehan')->nullable();
                $table->decimal('price', 15, 2)->default(0);
                $table->date('tgl_habis_garansi')->nullable();
                $table->string('foto_barang')->nullable();
                $table->string('gambar_barang')->nullable();
                $table->string('supplier')->nullable();
                $table->text('alamat_supplier')->nullable();
                $table->string('area')->nullable();
                $table->string('sumber_dana')->nullable();
                
                $table->text('description')->nullable();
                $table->text('keterangan')->nullable();
                $table->boolean('is_write_off')->default(false);
                
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

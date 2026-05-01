<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
    $table->id();
    $table->string('entno', 50)->nullable();
    $table->string('kode_aset', 50)->nullable();
    $table->string('barcode')->nullable();
    $table->string('name', 200);
    $table->string('gambar_barang')->nullable();
    $table->foreignId('category_id')->constrained('categories');
    $table->foreignId('room_id')->constrained('rooms');
    $table->string('merk_model', 150)->nullable();
    $table->text('spesifikasi')->nullable();
    $table->integer('quantity')->default(0);
    $table->integer('qty_diperbaiki')->default(0);
    $table->integer('qty_dipinjam')->default(0);
    $table->string('satuan', 20)->nullable(); // Ini 'unit' yang kamu tambahkan kemarin
    $table->enum('condition', ['Bagus', 'Rusak Ringan', 'Rusak Berat']);
    $table->text('keterangan')->nullable();
    $table->string('sumber_dana', 100)->nullable();
    $table->date('tgl_perolehan')->nullable();
    $table->decimal('price', 15, 2)->default(0);
    $table->date('tgl_habis_garansi')->nullable();
    $table->string('supplier', 150)->nullable();
    $table->string('area', 100)->nullable();
    $table->boolean('is_write_off')->default(false);
    $table->string('status', 50)->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
};

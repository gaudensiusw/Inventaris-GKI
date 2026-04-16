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
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('room_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('barcode')->unique();
            $table->enum('condition', ['Baik', 'Rusak ringan', 'Rusak berat'])->default('Baik');
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Sedang diperbaiki', 'Hilang', 'Tidak digunakan', 'Dalam pengadaan'])->default('Tersedia');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
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

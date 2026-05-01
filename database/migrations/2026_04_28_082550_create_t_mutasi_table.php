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
        // 1. Jalankan penghapusan kolom image di categories (opsional jika masih butuh)
        if (Schema::hasColumn('categories', 'image')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }

        // 2. Buat tabel t_mutasi sesuai ERD
        Schema::create('t_mutasi', function (Blueprint $table) {
            $table->id('id_mutasi');
            $table->foreignId('id_barang')->constrained('items');
            
            // Karena ada dua relasi ke tabel yang sama (rooms), 
            // kita arahkan manual ke id di tabel rooms
            $table->foreignId('id_lokasi_asal')->constrained('rooms');
            $table->foreignId('id_lokasi_tujuan')->constrained('rooms');
            
            $table->dateTime('tanggal_mutasi');
            $table->integer('jumlah_mutasi');
            $table->text('keterangan')->nullable();
            $table->foreignId('id_user')->constrained('users');
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
        Schema::dropIfExists('t_mutasi');
        
        // Kembalikan kolom image jika di-rollback
        Schema::table('categories', function (Blueprint $table) {
            $table->string('image')->nullable();
        });
    }
};
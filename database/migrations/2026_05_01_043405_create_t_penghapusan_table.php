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
        Schema::create('t_penghapusan', function (Blueprint $table) {
    $table->id('id_hapus');
    $table->foreignId('id_barang')->constrained('items');
    $table->date('tgl_hapus');
    $table->enum('alasan', ['Rusak Total', 'Dijual', 'Dihibahkan', 'Hilang']);
    $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('t_penghapusan');
    }
};

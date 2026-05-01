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
        Schema::create('t_stok_log', function (Blueprint $table) {
    $table->id('id_log');
    $table->foreignId('id_barang')->constrained('items');
    $table->enum('tipe_transaksi', ['Masuk', 'Keluar', 'Penyesuaian']);
    $table->integer('jumlah');
    $table->text('keterangan')->nullable();
    $table->timestamp('tgl_log')->useCurrent();
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_stok_log');
    }
};

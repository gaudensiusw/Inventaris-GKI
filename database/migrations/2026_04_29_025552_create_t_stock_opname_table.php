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
        Schema::create('t_stock_opname', function (Blueprint $table) {
    $table->id('id_so');
    $table->date('tgl_audit');
    $table->foreignId('id_barang')->constrained('items');
    $table->integer('stok_di_sistem');
    $table->integer('stok_di_fisik');
    $table->integer('selisih');
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
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
    }
};

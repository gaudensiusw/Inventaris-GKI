<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->after('status');
            $table->integer('qty_baik')->default(0)->after('quantity');
            $table->integer('qty_rusak_ringan')->default(0)->after('qty_baik');
            $table->integer('qty_rusak_berat')->default(0)->after('qty_rusak_ringan');
            $table->integer('qty_tersedia')->default(0)->after('qty_rusak_berat');
            $table->integer('qty_dipinjam')->default(0)->after('qty_tersedia');
            $table->integer('qty_diperbaiki')->default(0)->after('qty_dipinjam');
            $table->integer('qty_hilang')->default(0)->after('qty_diperbaiki');
            $table->integer('qty_tidak_digunakan')->default(0)->after('qty_hilang');
            $table->integer('qty_pengadaan')->default(0)->after('qty_tidak_digunakan');
            $table->decimal('price', 15, 2)->default(0)->after('qty_pengadaan');
            $table->date('purchase_date')->nullable()->after('price');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'quantity', 'qty_baik', 'qty_rusak_ringan', 'qty_rusak_berat',
                'qty_tersedia', 'qty_dipinjam', 'qty_diperbaiki', 'qty_hilang',
                'qty_tidak_digunakan', 'qty_pengadaan', 'price', 'purchase_date'
            ]);
        });
    }
};

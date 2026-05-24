<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('rents')) {
            Schema::create('rents', function (Blueprint $table) {
                $table->id('id_pinjam');
                $table->foreignId('id_barang')->constrained('items')->onDelete('cascade');
                $table->integer('qty')->default(1);
                $table->string('peminjam');
                $table->string('komisi_terkait')->nullable();
                $table->date('tgl_pinjam');
                $table->date('tgl_kembali_rencana');
                $table->date('tgl_kembali_aktual')->nullable();
                $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
                $table->string('status_pinjam')->default('Dipinjam');
                $table->text('catatan')->nullable();
                
                $table->integer('qty_kembali')->nullable();
                $table->string('kondisi_kembali', 50)->nullable();
                $table->text('catatan_kembali')->nullable();
                
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('rents');
    }
};

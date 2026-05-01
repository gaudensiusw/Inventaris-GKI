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
        Schema::create('repairs', function (Blueprint $table) {
            $table->id(); // Sesuai ERD image_815f0a.jpg
            $table->foreignId('id_barang')->constrained('items');
            
            // Mengarahkan ke tabel m_vendor dengan kolom primary key id_vendor
            $table->foreignId('id_vendor')->constrained('m_vendor', 'id_vendor');
            
            $table->date('tgl_service');
            $table->text('jenis_perbaikan');
            $table->string('status', 50);
            $table->date('estimated_completion')->nullable();
            $table->decimal('biaya', 15, 2)->default(0);
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
        // Menghapus tabel repairs jika migrasi di-rollback
        Schema::dropIfExists('repairs');
    }
};
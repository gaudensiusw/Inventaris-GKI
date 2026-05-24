<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('repairs')) {
            Schema::create('repairs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_barang')->constrained('items')->onDelete('cascade');
                $table->integer('qty')->default(1);
                $table->string('id_vendor')->nullable();
                $table->date('tgl_service');
                $table->string('jenis_perbaikan');
                $table->string('status')->default('Proses');
                $table->date('estimated_completion')->nullable();
                $table->decimal('biaya', 15, 2)->default(0);
                $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};

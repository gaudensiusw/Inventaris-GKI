<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $user) {
            $user->id();
            $user->string('item_id')->unique(); // Custom ID like INV-001
            $user->string('name');
            $user->foreignId('category_id')->constrained();
            $user->foreignId('room_id')->constrained();
            
            // Quantity Totals
            $user->integer('total_qty')->default(0);
            
            // Physical Condition Breakdown
            $user->integer('qty_baik')->default(0);
            $user->integer('qty_rusak_ringan')->default(0);
            $user->integer('qty_rusak_berat')->default(0);
            
            // Operational Status Breakdown
            $user->integer('qty_tersedia')->default(0);
            $user->integer('qty_dipinjam')->default(0);
            $user->integer('qty_perbaikan')->default(0);
            $user->integer('qty_hilang')->default(0);
            $user->integer('qty_tidak_digunakan')->default(0);
            $user->integer('qty_pengadaan')->default(0);
            
            // Financial & Metadata
            $user->decimal('price', 15, 2)->default(0);
            $user->date('purchase_date')->nullable();
            $user->text('description')->nullable();
            $user->string('photo')->nullable();
            
            $user->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

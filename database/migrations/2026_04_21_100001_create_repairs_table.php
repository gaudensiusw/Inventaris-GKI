<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->string('repair_location')->nullable();
            $table->date('repair_date');
            $table->date('estimated_completion')->nullable();
            $table->date('actual_completion')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Dalam Perbaikan', 'Selesai'])->default('Dalam Perbaikan');
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('repairs');
    }
};

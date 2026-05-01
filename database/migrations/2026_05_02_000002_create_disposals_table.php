<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('disposals', function (Blueprint $table) {
            $table->id();
            $table->string('disposal_id')->unique(); // DIS-20260502-001
            $table->foreignId('item_id')->constrained('items');
            $table->date('disposal_date');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('qty');
            $table->enum('reason', ['Rusak Total', 'Dijual', 'Dihibahkan', 'Hilang']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('disposals');
    }
};

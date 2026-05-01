<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_opname_headers', function (Blueprint $table) {
            $table->id();
            $table->string('so_id')->unique(); // SO-20260502-001
            $table->date('audit_date');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('status', ['Draft', 'Completed'])->default('Draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_opname_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_header_id')->constrained('stock_opname_headers')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items');
            $table->integer('system_qty');
            $table->integer('physical_qty');
            $table->integer('difference');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_opname_details');
        Schema::dropIfExists('stock_opname_headers');
    }
};

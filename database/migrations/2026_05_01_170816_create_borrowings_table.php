<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $user) {
            $user->id();
            $user->foreignId('item_id')->constrained()->onDelete('cascade');
            $user->string('borrower_name');
            $user->integer('qty');
            $user->date('borrow_date');
            $user->date('return_date')->nullable();
            $user->text('notes')->nullable();
            $user->boolean('is_returned')->default(false);
            $user->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};

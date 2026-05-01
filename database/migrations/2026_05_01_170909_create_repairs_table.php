<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repairs', function (Blueprint $user) {
            $user->id();
            $user->foreignId('item_id')->constrained()->onDelete('cascade');
            $user->string('repair_shop');
            $user->date('start_date');
            $user->date('estimate_end_date')->nullable();
            $user->text('notes')->nullable();
            $user->boolean('is_completed')->default(false);
            $user->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->string('avatar')->nullable();
        $table->string('department'); // Sesuai data inventory kamu
        $table->tinyInteger('status')->default(1); 
        $table->rememberToken();
        $table->timestamps();
    });


        // Pastikan tabel roles sudah ada sebelum insert ini dijalankan
        // Biasanya Spatie migrate duluan karena timestamp-nya lebih baru, 
        // tapi jika kamu migrate fresh, ini akan aman.
        
     
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
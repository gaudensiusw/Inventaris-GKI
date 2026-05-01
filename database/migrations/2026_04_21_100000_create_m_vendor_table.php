<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Pastikan ini diimport

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Membuat Tabel m_vendor
        Schema::create('m_vendor', function (Blueprint $table) {
            $table->id('id_vendor'); 
            $table->string('nama_vendor', 150);
            $table->text('alamat')->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->string('keahlian', 100)->nullable();
            $table->timestamps();
        });

        // 2. INSERT ROLE (Admin & Super Admin)
        // Karena file ini jalan belakangan, tabel 'roles' milik Spatie sudah pasti ada
        DB::table('roles')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'Super Admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. INSERT USER ADMIN
        // Menggunakan updateOrCreate versi DB agar data pasti masuk ke tabel users
        DB::table('users')->insertOrIgnore([
            'id' => 1,
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'department' => 'Umum',
            // Pastikan kolom 'status' sudah kamu tambahkan di migrasi users sebelumnya
            // Jika belum ada di migrasi users, hapus baris status di bawah ini:
            'status' => 1, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. HUBUNGKAN USER KE ROLE (PENTING: Biar gak 403)
        DB::table('model_has_roles')->insertOrIgnore([
            'role_id' => 1,           // ID Super Admin
            'model_type' => 'App\Models\User',
            'model_id' => 1,          // ID User Admin Utama
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_vendor');
    }
};
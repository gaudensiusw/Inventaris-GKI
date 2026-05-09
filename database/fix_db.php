<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

echo "Memulai sinkronisasi database...\n";

// 1. Update Tabel Orders
if (Schema::hasTable('orders')) {
    Schema::table('orders', function (Blueprint $table) {
        if (!Schema::hasColumn('orders', 'nama_peminjam')) {
            $table->string('nama_peminjam')->nullable()->after('item_id');
            echo "- Kolom nama_peminjam ditambahkan\n";
        }
        if (!Schema::hasColumn('orders', 'kontak_peminjam')) {
            $table->string('kontak_peminjam')->nullable()->after('nama_peminjam');
            echo "- Kolom kontak_peminjam ditambahkan\n";
        }
        if (!Schema::hasColumn('orders', 'status')) {
            $table->enum('status', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending')->after('qty');
            echo "- Kolom status ditambahkan\n";
        }
        if (!Schema::hasColumn('orders', 'approved_by')) {
            $table->bigInteger('approved_by')->unsigned()->nullable()->after('status');
            echo "- Kolom approved_by ditambahkan\n";
        }
        if (!Schema::hasColumn('orders', 'reject_reason')) {
            $table->text('reject_reason')->nullable()->after('approved_by');
            echo "- Kolom reject_reason ditambahkan\n";
        }
    });
    
    // Pastikan user_id bisa null
    DB::statement("ALTER TABLE orders MODIFY user_id BIGINT UNSIGNED NULL");
}

// 2. Update Tabel Borrowings
if (Schema::hasTable('borrowings')) {
    Schema::table('borrowings', function (Blueprint $table) {
        if (!Schema::hasColumn('borrowings', 'kondisi_kembali')) {
            $table->text('kondisi_kembali')->nullable();
            echo "- Kolom kondisi_kembali ditambahkan\n";
        }
        if (!Schema::hasColumn('borrowings', 'catatan_kembali')) {
            $table->text('catatan_kembali')->nullable();
            echo "- Kolom catatan_kembali ditambahkan\n";
        }
        if (!Schema::hasColumn('borrowings', 'qty_kembali')) {
            $table->integer('qty_kembali')->nullable();
            echo "- Kolom qty_kembali ditambahkan\n";
        }
    });
}

// 3. Daftarkan Migrasi agar sinkron
DB::table('migrations')->insertOrIgnore([
    ['migration' => '2026_05_08_010000_enhance_orders_table', 'batch' => 1],
    ['migration' => '2026_05_08_010001_enhance_rents_table', 'batch' => 1],
    ['migration' => '2026_05_08_020000_add_public_fields_to_orders', 'batch' => 1]
]);

echo "Database Berhasil Disinkronkan!\n";

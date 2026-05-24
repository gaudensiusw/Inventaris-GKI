<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'id_barang')) {
                    $table->foreignId('id_barang')->after('user_id')->constrained('items');
                }
                if (!Schema::hasColumn('orders', 'qty')) {
                    $table->integer('qty')->default(1)->after('id_barang');
                }
                if (!Schema::hasColumn('orders', 'catatan')) {
                    $table->text('catatan')->nullable()->after('status');
                }
                if (!Schema::hasColumn('orders', 'approved_by')) {
                    $table->foreignId('approved_by')->nullable()->after('catatan')->constrained('users');
                }
                if (!Schema::hasColumn('orders', 'reject_reason')) {
                    $table->text('reject_reason')->nullable()->after('approved_by');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['id_barang']);
                $table->dropForeign(['approved_by']);
                $table->dropColumn(['id_barang', 'qty', 'catatan', 'approved_by', 'reject_reason']);
            });
        }
    }
};

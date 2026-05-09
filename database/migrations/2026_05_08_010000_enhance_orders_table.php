<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('id_barang')->after('user_id')->constrained('items');
            $table->integer('qty')->default(1)->after('id_barang');
            $table->text('catatan')->nullable()->after('status');
            $table->foreignId('approved_by')->nullable()->after('catatan')->constrained('users');
            $table->text('reject_reason')->nullable()->after('approved_by');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['id_barang']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['id_barang', 'qty', 'catatan', 'approved_by', 'reject_reason']);
        });
    }
};

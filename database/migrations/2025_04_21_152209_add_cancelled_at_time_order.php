<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dateTime('cancelled_at')->nullable()->after('order_sent_at');
            $table->dropColumn('order_completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dateTime('order_completed_at')->nullable()->after('order_sent_at');
            $table->dropColumn('cancelled_at');
        });
    }
};

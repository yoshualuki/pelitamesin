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
        Schema::table(
            'orders',
            function (Blueprint $table) {
                $table->dateTime('waiting_payment_at')->nullable();
                $table->dateTime('order_processed_at')->nullable();
                $table->dateTime('order_sent_at')->nullable();
                $table->dateTime('order_completed_at')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('waiting_payment_at');
            $table->dropColumn('order_processed_at');
            $table->dropColumn('order_sent_at');
            $table->dropColumn('order_completed_at');
        });
    }
};

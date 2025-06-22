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
        Schema::create('refund_inventory_db', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('refund_id');
            $table->string('order_id');
            $table->unsignedBigInteger('order_detail_id');
            $table->string('product_id');

            $table->integer('quantity');
            $table->integer('status');

            // Foreign keys
            $table->foreign('refund_id')
                ->references('refund_id')
                ->on('order_refunds')
                ->onDelete('cascade');

            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('order_detail_id')
                ->references('id')
                ->on('order_details')
                ->onDelete('cascade');
            // Indexes
            $table->index('refund_id');
            $table->index('order_detail_id');
            $table->index('product_id');
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_inventory_db');
    }
};

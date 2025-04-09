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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('method');
            $table->string('status');
            $table->decimal('amount', 12, 2);
            $table->string('transaction_id')->nullable();
            $table->string('payment_code')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->string('receipt_url')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->onDelete('cascade');
            
            // Indexes
            $table->index('order_id');
            $table->index('transaction_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

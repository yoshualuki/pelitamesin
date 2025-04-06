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
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->id();
            $table->string('refund_id')->unique();
            $table->string('order_id');
            $table->string('user_id'); 
            
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending');
            $table->text('reason');
            $table->text('admin_notes')->nullable();
            $table->string('refund_method');
            
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            
            $table->timestamp('processed_at')->nullable();
            $table->string('receipt_image')->nullable();
            
            $table->timestamps();

            // Foreign keys
            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->onDelete('cascade');
                
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            // Indexes
            $table->index('order_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_refund');
    }
};

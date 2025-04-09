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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('order_id')->primary();
            $table->string('user_id');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('final_amount', 12, 2);
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->string('recipient_email')->nullable();
            $table->string('courier')->nullable();
            $table->string('service')->nullable();
            $table->integer('estimated_delivery')->nullable();
            $table->string('tracking_number')->nullable();
            $table->integer('weight');
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_code')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->string('voucher_code')->nullable();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

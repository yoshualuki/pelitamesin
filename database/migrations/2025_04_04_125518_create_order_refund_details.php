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
        Schema::create('order_refund_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('refund_id');
            $table->unsignedBigInteger('order_detail_id');
            
            $table->integer('quantity');
            $table->decimal('refund_amount', 12, 2);
            $table->text('reason');
            $table->string('condition');
            $table->json('images')->nullable();
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('refund_id')
                ->references('id')
                ->on('order_refunds')
                ->onDelete('cascade');
                
            $table->foreign('order_detail_id')
                ->references('id')
                ->on('order_details')
                ->onDelete('cascade');
            // Indexes
            $table->index('refund_id');
            $table->index('order_detail_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_refund_details');
    }
};

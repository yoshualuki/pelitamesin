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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('order_detail_id');
            $table->string('product_id');
            $table->unsignedTinyInteger('rating');
            $table->text('review')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('order_details')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('order_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};

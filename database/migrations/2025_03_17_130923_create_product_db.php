<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('id')->primary(); // Menetapkan id sebagai primary key
            $table->string('name');
            $table->decimal('price', 25, 2); // Menyimpan harga dalam format desimal
            $table->integer('stock');
            $table->decimal('weight', 25, 0);
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('image')->nullable();
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};

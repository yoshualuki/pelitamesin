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
        Schema::table('users', function (Blueprint $table) {
            $table->string('province');
            $table->string('province_id');
            $table->string('city_id');
            $table->string('city');
            $table->string('district_id');
            $table->string('district');
            $table->string('postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('province');
            $table->dropColumn('province_id');
            $table->dropColumn('city_id');
            $table->dropColumn('city');
            $table->dropColumn('district_id');
            $table->dropColumn('district');
            $table->dropColumn('postal_code');
        });
    }
};

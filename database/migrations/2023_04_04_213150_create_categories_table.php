<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->foreignId('father_id')->default(null)->nullable()->references('id')->on('categories');
        });

        Schema::create('category_properties', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->references('id')->on('categories');
            $table->foreignId('property_id')->nullable()->references('id')->on('properties');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_properties');

        Schema::table('categories',function (Blueprint $table) {
            $table->dropConstrainedForeignId('father_id');
        });
        Schema::dropIfExists('categories');
    }
};

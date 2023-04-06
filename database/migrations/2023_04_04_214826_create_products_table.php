<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->integer('count');
            $table->decimal('price', 12, 2, true);
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->foreignId('store_id')->references('id')->on('stores');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('product_properties', function (Blueprint $table) {
            $table->foreignId('product_id')->references('id')->on('products');
            $table->foreignId('property_id')->references('id')->on('properties');
            $table->string('value', 200);
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->foreignId('product_id')->references('id')->on('products');
            $table->foreignId('image_id')->references('id')->on('images');
        });

        Schema::create('product_complains', function (Blueprint $table) {
            $table->foreignId('product_id')->references('id')->on('products');
            $table->foreignId('complain_id')->references('id')->on('complains');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_complains');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_properties');
        Schema::dropIfExists('products');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->references('id')->on('products');
            $table->foreignId('author_id')->references('id')->on('users');
            $table->text('content')->nullable();
            $table->integer('elevation');
            $table->foreignId('image_id')->nullable()->references('id')->on('images');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('review_complains', function (Blueprint $table) {
            $table->foreignId('review_id')->references('id')->on('reviews');
            $table->foreignId('complain_id')->references('id')->on('complains');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_complains');
        Schema::dropIfExists('reviews');
    }
};

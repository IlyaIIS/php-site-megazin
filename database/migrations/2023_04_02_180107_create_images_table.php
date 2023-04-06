<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('path', 300);
            $table->char('hash', 32);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('image_id')->nullable()->references('id')->on('images');
        });
    }

    public function down(): void
    {
        Schema::table('users',function (Blueprint $table) {
            $table->dropConstrainedForeignId('image_id');
        });

        Schema::dropIfExists('images');
    }
};


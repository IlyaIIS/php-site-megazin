<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nickname', 200)->unique();
            $table->string('email', 200)->unique();
            $table->string('first_name', 200);
            $table->string('last_name', 200);
            $table->date('birthday');
            $table->char('password_hash', 60);
            $table->string('city', 200);
            $table->string('street', 200);
            $table->string('house', 200);
            $table->string('apartment', 200)->nullable();
            //$table->foreignId('image_id')->nullable()->references('id')->on('images');
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_seller')->default(false);
            $table->boolean('is_banned')->default(false);
            $table->boolean('is_email_confirmed')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Users');
    }
};

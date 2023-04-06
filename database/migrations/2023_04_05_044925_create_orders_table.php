<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_states', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->unique();
        });

        DB::table('order_states')->insert([
            ['name' => 'У продавца'],
            ['name' => 'Оформляется'],
            ['name' => 'Доставляется'],
            ['name' => 'Доставлен'],
            ['name' => 'Подтверждён'],
            ['name' => 'Отменён'],
        ]);

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->references('id')->on('products');
            $table->foreignId('customer_id')->references('id')->on('users');
            $table->text('note')->nullable();
            $table->decimal('price', 12, 2, true);
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('state_id')->default(0)->references('id')->on('order_states');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('Order_states');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Электроника', 'father_id' => null],
            ['name' => 'Для компьютера', 'father_id' => 1],
            ['name' => 'Для дома', 'father_id' => 1],
            ['name' => 'Мыши', 'father_id' => 2],
            ['name' => 'Клавиатуры', 'father_id' => 2],
            ['name' => 'Утюги', 'father_id' => 3],
        ]);

        DB::table('properties')->insert([
            ['name' => 'Вес'],
            ['name' => 'Цвет'],
            ['name' => 'Разъём'],
        ]);

        DB::table('category_properties')->insert([
            ['category_id' => 4, 'property_id' => 1],
            ['category_id' => 4, 'property_id' => 2],
            ['category_id' => 4, 'property_id' => 3],
            ['category_id' => 5, 'property_id' => 1],
            ['category_id' => 5, 'property_id' => 2],
            ['category_id' => 5, 'property_id' => 3],
            ['category_id' => 6, 'property_id' => 1],
            ['category_id' => 6, 'property_id' => 2],
        ]);
    }

    public function down(): void
    {

    }
};

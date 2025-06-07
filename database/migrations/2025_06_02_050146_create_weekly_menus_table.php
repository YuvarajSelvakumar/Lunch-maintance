<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('weekly_menus', function (Blueprint $table) {
            $table->id();
            $table->date('month'); // store month as YYYY-MM-01 date
            $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->enum('meal_type', ['Veg', 'Egg', 'Chicken']);
            $table->decimal('meal_price', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('weekly_menus');
    }
};

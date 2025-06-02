<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('weekly_menus', function (Blueprint $table) {
        $table->id();
        $table->date('month');
        $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
        $table->enum('meal_type', ['Veg', 'Egg', 'Chicken']);
        $table->integer('version');
        $table->date('effective_from');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_menus');
    }
};

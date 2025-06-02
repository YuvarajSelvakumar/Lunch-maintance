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
    Schema::create('daily_lunch_entries', function (Blueprint $table) {
        $table->id();
        $table->date('entry_date');
        $table->enum('meal_type', ['Veg', 'Egg', 'Chicken']);
        $table->integer('veg_count');
        $table->integer('egg_count');
        $table->integer('chicken_count');
        $table->decimal('cost_calculated', 8, 2);
        $table->foreignId('pricing_version_id')->constrained('menu_pricings');
        $table->foreignId('menu_version_id')->constrained('weekly_menus');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_lunch_entries');
    }
};

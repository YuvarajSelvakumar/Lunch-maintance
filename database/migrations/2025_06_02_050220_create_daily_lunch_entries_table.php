<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyLunchEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('daily_lunch_entries', function (Blueprint $table) {
         $table->id();
$table->date('entry_date');
$table->string('day_name');
$table->enum('meal_type', ['veg', 'egg', 'chicken']);
$table->integer('count')->default(0);  // count of meals taken
$table->decimal('meal_price', 8, 2);
$table->decimal('total_cost', 8, 2);
$table->timestamps();


            $table->foreign('pricing_version_id')->references('id')->on('menu_pricings');
            $table->foreign('menu_version_id')->references('id')->on('weekly_menus');
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_lunch_entries');
    }
}

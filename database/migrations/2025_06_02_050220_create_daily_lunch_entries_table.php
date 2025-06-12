<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_lunch_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->string('meal_type'); // 'veg', 'egg', 'chicken'
            $table->integer('meal_count'); // simplified to single count field
            $table->decimal('meal_price', 8, 2); // store the price per meal
            $table->decimal('total_cost', 8, 2); // meal_count * meal_price
            $table->unsignedBigInteger('pricing_version_id')->nullable();
            $table->unsignedBigInteger('menu_version_id')->nullable();
            $table->timestamps();

            // Add indexes for better performance
            $table->index('entry_date');
            $table->index(['entry_date', 'meal_type']);
            
            // Add foreign key constraints if needed
            $table->foreign('pricing_version_id')->references('id')->on('menu_pricings')->onDelete('set null');
            $table->foreign('menu_version_id')->references('id')->on('weekly_menus')->onDelete('set null');
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
//{$table->enum('meal_type', ['veg', 'egg', 'chicken']);
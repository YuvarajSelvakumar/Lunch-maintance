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
    Schema::create('menu_pricings', function (Blueprint $table) {
        $table->id();
        $table->date('month'); // The month this pricing is for
        $table->decimal('veg_price', 8, 2);
        $table->decimal('egg_price', 8, 2);
        $table->decimal('chicken_price', 8, 2);
        $table->integer('version');
        $table->date('effective_from');
        $table->timestamps(); // Adds created_at and updated_at
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_pricings');
    }
};

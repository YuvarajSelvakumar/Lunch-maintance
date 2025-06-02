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
    Schema::create('vendor_payments', function (Blueprint $table) {
        $table->id();
        $table->date('month');
        $table->decimal('total_amount', 8, 2);
        $table->decimal('amount_paid', 8, 2);
        $table->decimal('arrears', 8, 2);
        $table->date('payment_date')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_payments');
    }
};

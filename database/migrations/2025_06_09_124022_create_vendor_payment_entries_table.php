<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_payment_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_payment_id')->constrained()->onDelete('cascade');
            $table->decimal('paid_amount', 10, 2);
            $table->date('payment_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_payment_entries');
    }
};

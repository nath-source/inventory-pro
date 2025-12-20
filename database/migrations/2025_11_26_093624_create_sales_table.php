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
    Schema::create('sales', function (Blueprint $table) {
        $table->id();
        // Who sold it? (The cashier)
        $table->foreignId('user_id')->constrained(); 
        // Customer is optional for Quick Sales
        $table->unsignedBigInteger('customer_id')->nullable(); 

        $table->string('invoice_no');
        $table->decimal('total_amount', 10, 2);
        $table->decimal('received_amount', 10, 2); // Amount customer gave
        $table->string('payment_method')->default('cash'); // Cash, Card, Mobile Money
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

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
    Schema::create('products', function (Blueprint $table) {
        $table->id();

        // Basic Info
        $table->string('name');
        $table->string('sku')->unique(); // Stock Keeping Unit (Barcode)
        $table->text('description')->nullable();

        // Foreign Key: Links to the 'categories' table we created in Step 1
        $table->foreignId('category_id')->constrained()->onDelete('cascade');

        // Pricing & Inventory
        $table->decimal('cost_price', 10, 2);    // How much you bought it for
        $table->decimal('selling_price', 10, 2); // How much you sell it for
        $table->integer('stock')->default(0);    // Current Quantity
        $table->integer('reorder_level')->default(10); // Alert threshold

        $table->string('image')->nullable(); // Optional: to show product image later
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

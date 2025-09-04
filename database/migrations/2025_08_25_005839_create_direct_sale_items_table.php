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
        Schema::create('direct_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('direct_sales')->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 4);
            $table->decimal('weight', 10, 4);
            $table->decimal('unit_price', 15, 4);
            $table->decimal('total_price', 15, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direct_sale_items');
    }
};

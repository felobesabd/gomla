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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('container_id')->nullable();
            $table->tinyInteger('transaction_type')->default(0)->comment('0=IN, 1=OUT');
            $table->integer('quantity');
            $table->decimal('weight', 12, 2)->nullable();
            $table->string('source_type')->nullable(); // Supplier, Warehouse, Representative
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('destination_type')->nullable(); // Warehouse, Customer, Representative
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->decimal('qty_before', 12, 4)->nullable();
            $table->decimal('qty_after', 12, 4)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

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
        Schema::create('representative_deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('representative_id');
            $table->string('document_no');
            $table->dateTime('delivered_at')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=confirmed, 2=cancelled');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('representative_deliveries');
    }
};

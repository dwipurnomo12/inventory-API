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
        Schema::create('products_out', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code');
            $table->date('date');
            $table->string('product_name');
            $table->integer('stock_out');
            $table->foreignId('customer_id')->constrained('customers');
            $table->timestamps();;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_out');
    }
};
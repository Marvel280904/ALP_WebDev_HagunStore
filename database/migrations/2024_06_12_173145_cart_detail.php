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
        Schema::create('cart_details', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('productId');
            $table->uuid('cartId');
            $table->integer('qty');
            $table->decimal('subTotal', 10, 2);
            $table->timestamps();

            // Adding foreign key constraints
            $table->string('productId')->references('ID_Produk')->on('product')->onDelete('cascade');
            $table->foreign('cartId')->references('uuid')->on('cart')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_details');
    }
};

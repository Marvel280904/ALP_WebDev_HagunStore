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
        Schema::create('product', function (Blueprint $table) {
            $table->string('ID_Produk')->primary();
            $table->string('Nama_Produk', 255);
            $table->string('Merek_Sepatu');
            $table->string('Kategori');
            $table->string('Gender');
            $table->string('Warna');
            $table->decimal('Harga', 10, 2);
            $table->string('Image', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};

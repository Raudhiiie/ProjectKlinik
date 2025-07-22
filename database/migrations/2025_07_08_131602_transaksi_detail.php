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
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
            

            $table->enum('jenis', ['layanan', 'produk']);
            $table->unsignedBigInteger('layanan_id')->nullable();
            $table->unsignedBigInteger('produk_id')->nullable();

            $table->integer('jumlah');
            $table->integer('harga_satuan');
            $table->integer('subtotal');

            $table->timestamps();

            $table->foreign('layanan_id')->references('id')->on('sublayanans')->nullOnDelete();
            $table->foreign('produk_id')->references('id')->on('produks')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};

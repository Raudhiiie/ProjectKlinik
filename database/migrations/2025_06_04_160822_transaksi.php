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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('no_rm');
            $table->date('tanggal');
            $table->string('metode_pembayaran');

            $table->string('keterangan')->nullable();
            $table->integer('total_harga')->nullable();
            $table->timestamps();

            $table->foreign('no_rm')->references('no_rm')->on('pasiens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};

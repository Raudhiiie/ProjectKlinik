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
            $table->unsignedBigInteger('sub_layanan_id');
            $table->date('tanggal');
            $table->string('jenis');
            $table->string('metode_pembayaran');
            $table->integer('jumlah');
            $table->integer('total_harga');
            $table->string('keterangan');
            $table->timestamps();

            $table->foreign('no_rm')->references('no_rm')->on('pasiens')->onDelete('cascade');
            $table->foreign('sub_layanan_id')->references('id')->on('sublayanans')->onDelete('cascade');
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

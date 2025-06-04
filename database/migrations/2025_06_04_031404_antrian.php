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
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->string('no_rm');
            $table->integer('no_antrian');
            $table->date('tanggal');
            $table->string('status')->default('menunggu');
            $table->timestamp('waktu_daftar')->useCurrent();
            $table->timestamps();

            $table->foreign('no_rm')->references('no_rm')->on('pasiens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};

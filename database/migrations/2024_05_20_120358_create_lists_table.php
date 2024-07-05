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
        Schema::create('lists', function (Blueprint $table) {
            $table->id();
            $table->string('nopd');
            $table->date('tglemail');
            $table->string('uraian');
            $table->string('rig');
            $table->string('departement');
            $table->integer('jlh');
            $table->integer('realisasi');
            $table->integer('selisih');
            $table->string('status');
            $table->date('tglpembayaran');
            $table->date('tglpelunasan');
            $table->string('rekening');
            $table->text('evidence');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lists');
    }
};

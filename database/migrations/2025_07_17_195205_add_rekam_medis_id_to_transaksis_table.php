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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->unsignedBigInteger('rekam_medis_id')->nullable()->after('id');
            $table->foreign('rekam_medis_id')->references('id')->on('rekam_medis')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['rekam_medis_id']);
            $table->dropColumn('rekam_medis_id');
        });
    }
};

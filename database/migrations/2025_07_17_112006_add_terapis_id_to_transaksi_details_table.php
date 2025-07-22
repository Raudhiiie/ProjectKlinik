<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('transaksi_details', function (Blueprint $table) {
            $table->foreignId('terapis_id')->nullable()->constrained('terapis')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('transaksi_details', function (Blueprint $table) {
            $table->dropForeign(['terapis_id']);
            $table->dropColumn('terapis_id');
        });
    }
};

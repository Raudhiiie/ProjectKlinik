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
        $table->foreignId('terapis_id')->nullable()->after('no_rm')->constrained()->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('transaksis', function (Blueprint $table) {
        $table->dropForeign(['terapis_id']);
        $table->dropColumn('terapis_id');
    });
}

};

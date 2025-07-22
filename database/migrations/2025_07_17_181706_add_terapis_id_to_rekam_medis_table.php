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
    Schema::table('rekam_medis', function (Blueprint $table) {
        $table->foreignId('terapis_id')->constrained()->onDelete('cascade')->after('tindakan');
    });
}

public function down(): void
{
    Schema::table('rekam_medis', function (Blueprint $table) {
        $table->dropForeign(['terapis_id']);
        $table->dropColumn('terapis_id');
    });
}

};

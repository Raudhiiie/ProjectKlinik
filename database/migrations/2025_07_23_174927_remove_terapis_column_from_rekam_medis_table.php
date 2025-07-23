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
            $table->dropColumn('terapis');
        });
    }

    public function down(): void {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->string('terapis')->nullable(); // kalau rollback
        });
    }};

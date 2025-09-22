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
        Schema::table('atg_discharges', function (Blueprint $table) {
            $table->double('started_volume')->change();
            $table->double('ended_volume')->change();
        });

        Schema::table('atgs', function (Blueprint $table) {
            $table->double('factor_correction')->change();
            $table->double('temperature')->change();
        });

        Schema::table('density_tables', function (Blueprint $table) {
            $table->double('density')->change();
            $table->double('fk')->change();
        });

        Schema::table('stock_cpos', function (Blueprint $table) {
            $table->double('level')->change();
            $table->double('temp_avg')->change();
            $table->double('density')->change();
            $table->double('fk')->change();
            $table->double('volume')->change();
            $table->double('cpo')->change();
        });

        Schema::table('stock_cpos', function (Blueprint $table) {
            $table->double('level')->change();
            $table->double('temp_avg')->change();
            $table->double('density')->change();
            $table->double('fk')->change();
            $table->double('volume')->change();
            $table->double('cpo')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 
    }
};

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
        Schema::table('atgs', function(Blueprint $table){
            $table->double('factor_correction')->after('capacity')->default(0);
            $table->double('temperature')->after('factor_correction')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atgs', function(Blueprint $table){
            $table->dropColumn('factor_correction', 'temperature');
        });
    }
};

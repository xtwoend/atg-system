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
            $table->time('sounding_time')->after('capacity')->nullable();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atgs', function(Blueprint $table){
            $table->dropColumn('sounding_time');
        });
    }
};

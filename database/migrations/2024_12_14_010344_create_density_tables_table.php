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
        Schema::create('density_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atg_id');
            $table->integer('temperature')->default(0);
            $table->double('density', 7, 5)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('density_tables');
    }
};

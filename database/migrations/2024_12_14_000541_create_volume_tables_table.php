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
        Schema::create('volume_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atg_id');
            $table->integer('height')->default(0);
            $table->double('different', 6, 1)->default(0);
            $table->double('volume', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volume_tables');
    }
};

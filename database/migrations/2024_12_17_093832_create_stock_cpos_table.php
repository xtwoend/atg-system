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
        Schema::create('stock_cpos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atg_id');
            $table->date('stock_date');
            $table->time('stock_time')->nullable();
            $table->double('level')->default(0);
            $table->double('temp_avg')->default(0);
            $table->double('density')->default(0);
            $table->double('fk')->default(0);
            $table->double('volume')->default(0);
            $table->double('cpo')->default(0);
            $table->text('data_log')->nullable();
            $table->timestamps();

            $table->unique(['atg_id', 'stock_date', 'stock_time'], 'stock_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_cpos');
    }
};

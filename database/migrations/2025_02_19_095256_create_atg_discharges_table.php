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
        Schema::create('atg_discharges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atg_id')->index();
            $table->datetime('started_at')->nullable();
            $table->datetime('ended_at')->nullable();
            $table->double('started_volume', 3)->default(0);
            $table->double('ended_volume', 3)->default(0);
            $table->double('sp_volume')->default(0);
            $table->double('sp_level')->default(0);
            $table->boolean('on_counter')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atg_discharges');
    }
};

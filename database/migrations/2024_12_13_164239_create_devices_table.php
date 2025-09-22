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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->nullable();
            $table->string('manufacture')->nullable();
            $table->string('model')->nullable();
            $table->boolean('status')->default(0);
            $table->unsignedBigInteger('connection_id');
            $table->string('topic');
            $table->string('handler');
            $table->datetime('last_connected')->nullable();
            $table->text('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};

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
        Schema::create('monsters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('map')->nullable();
            $table->string('element')->nullable();
            $table->integer('max_hp')->nullable();
            $table->integer('respawn_time')->nullable();
            $table->string('skill')->nullable();
            $table->json('drops')->nullable();
            $table->string('exp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monsters');
    }
};

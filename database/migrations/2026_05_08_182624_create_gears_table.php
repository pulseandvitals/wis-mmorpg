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
        Schema::create('gears', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->integer('attack_bonus')->default(0);
            $table->integer('defense_bonus')->default(0);
            $table->integer('speed_bonus')->default(0);
            $table->integer('health_bonus')->default(0);
            $table->integer('mana_bonus')->default(0);
            $table->integer('evasion_bonus')->default(0);
            $table->integer('critical_bonus')->default(0);
            $table->integer('required_level')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gears');
    }
};

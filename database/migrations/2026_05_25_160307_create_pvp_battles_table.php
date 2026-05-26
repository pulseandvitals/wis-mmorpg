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
        Schema::create('pvp_battles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenger_id');
            $table->foreignId('opponent_id');

            $table->integer('round')->default(1);
            $table->boolean('round_processing')->default(false);
            $table->json('challenger_events')->nullable();
            $table->json('opponent_events')->nullable();
            $table->enum('status', [
                'active',
                'finished'
            ])->default('active');
            $table->foreignId('winner_id')->nullable();
            $table->integer('challenger_skill_id')->nullable();
            $table->integer('opponent_skill_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pvp_battles');
    }
};

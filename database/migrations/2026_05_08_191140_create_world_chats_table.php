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
        Schema::create('world_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->text('message');
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->string('channel')->nullable(); //local, world, party
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('world_chats');
    }
};

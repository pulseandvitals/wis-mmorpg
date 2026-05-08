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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('class_type_id');
            $table->integer('current_level')->default(1);
            $table->integer('current_experience')->default(0);
            $table->integer('max_health')->default(100);
            $table->integer('current_health')->default(100);
            $table->integer('max_mana')->default(50);
            $table->integer('current_mana')->default(50);
            $table->integer('current_gold')->default(10);
            $table->integer('current_diamond')->default(0);
            $table->integer('total_attack')->default(10);
            $table->integer('total_defense')->default(5);
            $table->integer('total_speed')->default(5);
            $table->integer('total_evasion_percentage')->default(2);
            $table->integer('total_critical_percentage')->default(2);
            $table->foreignId('current_map_id')->nullable();
            $table->integer('x')->default(10);
            $table->integer('y')->default(2);
            $table->integer('helm_id')->unsigned()->nullable();
            $table->integer('armor_id')->unsigned()->nullable();
            $table->integer('pants_id')->unsigned()->nullable();
            $table->integer('boots_id')->unsigned()->nullable();
            $table->integer('gloves_id')->unsigned()->nullable();
            $table->integer('weapon_id')->unsigned()->nullable();
            $table->integer('shield_id')->unsigned()->nullable();
            $table->integer('accessory_id')->unsigned()->nullable();
            $table->boolean('is_online')->default(true);
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};

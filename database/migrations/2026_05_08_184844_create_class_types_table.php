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
        Schema::create('class_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('base_health')->default(100);
            $table->integer('base_mana')->default(50);
            $table->integer('base_attack')->default(10);
            $table->integer('base_defense')->default(5);
            $table->integer('base_speed')->default(5);
            $table->integer('base_evasion')->default(0);
            $table->integer('base_critical')->default(0);
            $table->string('wields'); // e.g. "sword, shield"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_types');
    }
};

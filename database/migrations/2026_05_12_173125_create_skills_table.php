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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('class')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('target')->default(1);
            $table->string('element')->nullable();
            $table->integer('requirement_level')->default(1);
            $table->integer('damage')->default(0);
            $table->integer('mana_cost')->default(0);
            $table->string('icon_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};

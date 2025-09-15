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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable()->unique();
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->string('question_text');
            $table->enum('difficulty', ['mudah', 'sedang', 'sulit'])->default('mudah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};

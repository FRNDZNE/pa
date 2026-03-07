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
        Schema::create('student_difficulty_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
            $table->enum('difficulty', ['mudah', 'sedang', 'sulit']);
            $table->integer('total_questions')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->decimal('score_percentage', 5, 2)->default(0);
            $table->timestamps();

            // Satu student hanya punya 1 record per difficulty per lesson
            $table->unique(['student_id', 'lesson_id', 'difficulty'], 'student_lesson_difficulty_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_difficulty_scores');
    }
};

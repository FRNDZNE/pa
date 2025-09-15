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
        Schema::create('student_scores', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->nullable()->unique();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->enum('grade', ['A', 'B', 'C', 'D', 'F'])->default('F');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->boolean('is_passed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_scores');
    }
};

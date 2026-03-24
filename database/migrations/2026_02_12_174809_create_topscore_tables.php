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
    // 1. Add 'role' to users table (Admin, Teacher, Student)
    Schema::table('users', function (Blueprint $table) {
        $table->string('role')->default('student'); // admin, teacher, student
    });

    // 2. Quizzes Table
    Schema::create('quizzes', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('unique_code')->unique(); // The code students use to join
        $table->integer('duration_minutes');
        $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });

    // 3. Questions Table
    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
        $table->text('question_text');
        $table->string('type'); // 'mcq' or 'true_false'
        $table->json('options')->nullable(); // Stores options for MCQ
        $table->string('correct_answer');
        $table->integer('marks')->default(1);
        $table->timestamps();
    });

    // 4. Results/Attempts Table
    Schema::create('results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
        $table->integer('score');
        $table->integer('total_marks');
        $table->string('grade')->nullable(); // A, B, C, etc.
        $table->timestamps();
    });
}
};

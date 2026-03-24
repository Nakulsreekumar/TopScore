<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES ---
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication
Route::get('/login', [QuizController::class, 'loginView'])->name('login');
Route::post('/login', [QuizController::class, 'login'])->name('login.submit');

Route::get('/register', [QuizController::class, 'registerView'])->name('register');
Route::post('/register', [QuizController::class, 'register'])->name('register.submit');

// Allow anyone who is logged in to log out
Route::match(['get', 'post'], '/logout', [QuizController::class, 'logout'])->name('logout');


// --- PROTECTED ROUTES (Secured with Middleware) ---

// 1. ADMIN ROUTES
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [QuizController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::delete('/admin/user/{id}', [QuizController::class, 'deleteUser'])->name('admin.deleteUser');
});

// 2. TEACHER ROUTES
Route::middleware(['auth', 'role:teacher'])->group(function () {
    // Dashboard
    Route::get('/teacher', [QuizController::class, 'teacherDashboard'])->name('teacher.dashboard');
    
    // Quiz Creation
    Route::get('/quiz/create', [QuizController::class, 'showCreateForm'])->name('quiz.create.form');
    Route::post('/quiz/create', [QuizController::class, 'createQuiz'])->name('quiz.create');
    
    // Quiz Results & Review
    Route::get('/teacher/quiz/{id}/results', [QuizController::class, 'viewResults'])->name('quiz.results');
    Route::get('/teacher/quiz/{quiz_id}/review/{user_id}', [QuizController::class, 'teacherReviewStudent'])->name('teacher.student_review'); 
    
    // Quiz Management (Share & Delete)
    Route::post('/quiz/{id}/share', [QuizController::class, 'shareQuiz'])->name('quiz.share');
    Route::delete('/quiz/{id}', [QuizController::class, 'destroy'])->name('quiz.destroy');

    // Question Management
    Route::get('/quiz/{id}/add-questions', [QuizController::class, 'addQuestionsView'])->name('teacher.add_questions');
    Route::post('/quiz/{id}/store-question', [QuizController::class, 'storeQuestions'])->name('questions.store');
    Route::post('/quiz/{id}/upload-csv', [QuizController::class, 'uploadCsv'])->name('questions.upload');
    // Update Question Marks
    Route::put('/question/{id}/update', [QuizController::class, 'updateQuestion'])->name('question.update');

    // Update Quiz Duration
    Route::put('/quiz/{id}/update-duration', [QuizController::class, 'updateDuration'])->name('quiz.update_duration');
});

// 3. STUDENT ROUTES
Route::middleware(['auth', 'role:student'])->group(function () {
    // Dashboard
    Route::get('/student', [QuizController::class, 'studentDashboard'])->name('student.dashboard');
    
    // Joining and Taking Quiz
    Route::post('/quiz/join', [QuizController::class, 'joinQuiz'])->name('quiz.join');
    Route::get('/quiz/take/{id}', [QuizController::class, 'takeQuiz'])->name('quiz.take');
    Route::post('/quiz/submit/{id}', [QuizController::class, 'submitQuiz'])->name('quiz.submit');
    
    // Reviewing Completed Quizzes
    Route::get('/student/quiz/{id}/review', [QuizController::class, 'studentQuizReview'])->name('student.quiz_review');
});
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use App\Models\StudentAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class QuizController extends Controller
{
    // ==========================================
    //          1. AUTHENTICATION
    // ==========================================

    public function loginView() {
        return view('login');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $role = Auth::user()->role;
            if ($role == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role == 'teacher') {
                return redirect()->route('teacher.dashboard');
            } else {
                return redirect()->route('student.dashboard');
            }
        }
        return back()->with('error', 'Invalid credentials');
    }

    public function logout() {
        Auth::logout();
        return redirect('/');
    }

    public function registerView() {
        return view('register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:teacher,student'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    // ==========================================
    //          2. DASHBOARDS
    // ==========================================
    
    public function adminDashboard() {
        return view('admin.dashboard', [
            'teachers' => User::where('role', 'teacher')->orderBy('created_at', 'desc')->get(),
            'students' => User::where('role', 'student')->with(['results' => function($query) {
                $query->latest();
            }])->orderBy('created_at', 'desc')->get(),
            'quizzes' => Quiz::all()
        ]);
    }

    public function teacherDashboard() {
        $quizzes = Quiz::where('teacher_id', Auth::id())->with('questions')->get();
        return view('teacher.dashboard', compact('quizzes'));
    }

    public function studentDashboard() {
        $results = Result::where('user_id', Auth::id())->with('quiz')->orderBy('created_at', 'desc')->get();
        return view('student.dashboard', compact('results'));
    }

    // ==========================================
    //          3. TEACHER ACTIONS
    // ==========================================

    public function showCreateForm() {
        $quizzes = Quiz::where('teacher_id', Auth::id())->with('questions')->orderBy('created_at', 'desc')->get();
        return view('teacher.create_quiz', compact('quizzes'));
    }

    public function createQuiz(Request $request) {
        $request->validate([
            'title' => 'required',
            'duration' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time'
        ]);

        $code = Str::upper(Str::random(6)); 

        Quiz::create([
            'title' => $request->title,
            'duration_minutes' => $request->duration,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'unique_code' => $code,
            'teacher_id' => Auth::id()
        ]);

        return redirect()->route('teacher.dashboard')->with('success', "Quiz created successfully! Code: $code");
    }

    public function addQuestionsView($id) {
        $quiz = Quiz::findOrFail($id);
        
        if($quiz->teacher_id != Auth::id()) {
            return abort(403, 'Unauthorized action.');
        }
        
        return view('teacher.add_questions', compact('quiz'));
    }

    public function storeQuestions(Request $request, $id) {
        $quiz = Quiz::findOrFail($id);
        
        Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => $request->question_text,
            'type' => $request->type,
            'options' => $request->options,
            'correct_answer' => $request->correct_answer,
            'marks' => $request->marks
        ]);

        return back()->with('success', 'Question added successfully!');
    }

    public function uploadCsv(Request $request, $id) {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $quiz = Quiz::findOrFail($id);
        $file = fopen($request->file('csv_file'), 'r');
        fgetcsv($file); // Skip Header

        while (($line = fgetcsv($file)) !== FALSE) {
            if(count($line) < 4) continue; 

            Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => $line[0],
                'type' => $line[1], 
                'options' => $line[2],
                'correct_answer' => $line[3],
                'marks' => $line[4] ?? 1
            ]);
        }
        
        fclose($file);
        return back()->with('success', 'Questions imported successfully!');
    }
    public function updateQuestion(Request $request, $id) {
        $request->validate([
            'marks' => 'required|integer|min:1'
        ]);

        $question = Question::findOrFail($id);
        $quiz = Quiz::findOrFail($question->quiz_id);
        
        // Security check: only the owner of the quiz can edit its questions
        if ($quiz->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Update the marks and save
        $question->marks = $request->marks;
        $question->save();

        return back()->with('success', 'Question marks updated successfully!');
    }

    public function destroy($id) {
        $quiz = Quiz::findOrFail($id);
        if ($quiz->teacher_id !== Auth::id()) { abort(403); }
        $quiz->delete();
        return redirect()->back()->with('success', 'Quiz deleted successfully!');
    }

    public function viewResults($id) {
        $quiz = Quiz::findOrFail($id);
        if ($quiz->teacher_id !== Auth::id()) { abort(403); }

        $results = Result::with('user')
            ->where('quiz_id', $quiz->id)
            ->orderBy('score', 'desc')
            ->get();

        $chartData = [
            'A' => $results->filter(fn($r) => str_contains($r->grade, 'A'))->count(),
            'B' => $results->filter(fn($r) => str_contains($r->grade, 'B'))->count(),
            'C' => $results->filter(fn($r) => str_contains($r->grade, 'C'))->count(),
            'F' => $results->filter(fn($r) => str_contains($r->grade, 'F'))->count(),
        ];

        return view('teacher.quiz_results', compact('quiz', 'results', 'chartData'));
    }
    public function updateDuration(Request $request, $id) {
        $request->validate([
            'duration' => 'required|integer|min:1'
        ]);

        $quiz = Quiz::findOrFail($id);
        
        // Security check: only the owner can edit the quiz
        if ($quiz->teacher_id !== Auth::id()) { 
            abort(403, 'Unauthorized action.'); 
        }

        // Update the duration and save
        $quiz->duration_minutes = $request->duration;
        $quiz->save();

        return back()->with('success', 'Quiz duration updated successfully!');
    }

    public function teacherReviewStudent($quiz_id, $user_id) {
        $quiz = Quiz::with('questions')->findOrFail($quiz_id);
        if ($quiz->teacher_id !== Auth::id()) { abort(403); }

        $student = User::findOrFail($user_id);
        $userAnswers = StudentAnswer::where('user_id', $user_id)
                                ->where('quiz_id', $quiz_id)
                                ->get()
                                ->keyBy('question_id');

        return view('teacher.student_review', compact('quiz', 'student', 'userAnswers'));
    }

    public function shareQuiz(Request $request, $id) {
        $request->validate(['email' => 'required|email']);
        $originalQuiz = Quiz::with('questions')->findOrFail($id);
        $recipientTeacher = User::where('email', $request->email)->where('role', 'teacher')->first();

        if (!$recipientTeacher) { return back()->with('error', 'Teacher not found.'); }

        $newQuiz = $originalQuiz->replicate(); 
        $newQuiz->teacher_id = $recipientTeacher->id;
        $newQuiz->unique_code = strtoupper(Str::random(6));
        $newQuiz->save();

        foreach ($originalQuiz->questions as $question) {
            $newQ = $question->replicate();
            $newQ->quiz_id = $newQuiz->id;
            $newQ->save();
        }

        return back()->with('success', 'Quiz shared successfully!');
    }

    // ==========================================
    //          4. ADMIN ACTIONS
    // ==========================================

    public function deleteUser($id) {
        $user = User::findOrFail($id);
        if ($user->id == Auth::id()) { return back()->with('error', 'Cannot delete self.'); }
        $user->delete();
        return back()->with('success', 'User removed.');
    }

    // ==========================================
    //          5. STUDENT ACTIONS
    // ==========================================

    public function joinQuiz(Request $request) {
        $request->validate(['code' => 'required']);
        $quiz = Quiz::where('unique_code', $request->code)->first();
        if (!$quiz) { return back()->with('error', 'Invalid Quiz Code.'); }
        
        // This passes the actual database ID to the take route
        return redirect()->route('quiz.take', $quiz->id);
    }

    // FIXED: Parameter is now $id to match the route and joinQuiz redirect
    public function takeQuiz($id) 
    {
        // 1. Fetch the quiz using the ID
        $quiz = Quiz::findOrFail($id);
        
        // 2. TIMING LOGIC (For the Waiting Room)
        $now = now(); 
        $has_started = $now->greaterThanOrEqualTo($quiz->start_time);
        $has_ended = $now->greaterThan($quiz->end_time);
        $is_active = $has_started && !$has_ended;

        // 3. SCRAMBLE QUESTIONS
        $questions = $quiz->questions()->inRandomOrder()->get();

        // 4. SCRAMBLE OPTIONS
        foreach ($questions as $question) {
            $optionsArray = [];

            // Check if options are stored as a string separated by pipes (from CSV) or commas
            if (is_string($question->options)) {
                if (str_contains($question->options, '|')) {
                    $optionsArray = explode('|', $question->options);
                } elseif (str_contains($question->options, ',')) {
                    $optionsArray = explode(',', $question->options);
                } else {
                    // Fallback to json decode if it happens to be JSON
                    $decoded = json_decode($question->options, true);
                    $optionsArray = is_array($decoded) ? $decoded : [$question->options];
                }
            } elseif (is_array($question->options)) {
                $optionsArray = $question->options;
            }

            // Shuffle the array and assign it
            if (!empty($optionsArray)) {
                shuffle($optionsArray); 
                $question->shuffled_options = $optionsArray; 
            }
        }

        // Pass everything, including the timer variables, to the view
        return view('student.take_quiz', compact('quiz', 'questions', 'has_started', 'has_ended', 'is_active'));
    }

    public function submitQuiz(Request $request, $id) {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $user = Auth::user();
        $score = 0; $total_marks = 0;

        foreach ($quiz->questions as $question) {
            $total_marks += $question->marks;
            $submittedAnswer = $request->input('q_' . $question->id);
            $isCorrect = ($submittedAnswer == $question->correct_answer);

            if ($isCorrect) { $score += $question->marks; }

            StudentAnswer::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'question_id' => $question->id,
                'submitted_answer' => $submittedAnswer,
                'is_correct' => $isCorrect
            ]);
        }

        $percentage = ($total_marks > 0) ? ($score / $total_marks) * 100 : 0;
        $grade = match(true) {
            $percentage >= 90 => 'A (Excellent)',
            $percentage >= 75 => 'B (Very Good)',
            $percentage >= 50 => 'C (Pass)',
            default => 'F (Fail)',
        };

        Result::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'total_marks' => $total_marks,
            'grade' => $grade,
            'tab_switches' => $request->input('tab_switches', 0)
        ]);
        
        return redirect()->route('student.dashboard')->with('success', "Quiz Submitted!");
    }

    public function studentQuizReview($id) {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $result = Result::where('quiz_id', $id)->where('user_id', Auth::id())->first();

        if (!$result) { return redirect()->route('student.dashboard')->with('error', 'No result found.'); }

        $studentAnswers = StudentAnswer::where('quiz_id', $id)
            ->where('user_id', Auth::id())
            ->get()
            ->keyBy('question_id'); 

        return view('student.quiz_review', compact('quiz', 'result', 'studentAnswers'));
    }
}
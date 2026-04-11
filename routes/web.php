<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('templating', function () {
    return view('layouts.app');
});
Route::get('/', function () {
    return view('welcome');
})->middleware('guest')->name('welcome');
// Auth tanpa register
Auth::routes([
    'register' => false,
]);
// Redirect berdasarkan role
Route::get('/home', function () {
    if (Auth::user()->role->name == 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::user()->role->name == 'lecturer') {
        return redirect()->route('lecturer.dashboard');
    } elseif (Auth::user()->role->name == 'student') {
        return redirect()->route('student.dashboard');
    }
})->middleware('auth')->name('home');

Route::get('/home', function () {
    if (Auth::user()->role->name == 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::user()->role->name == 'lecturer') {
        return redirect()->route('lecturer.dashboard');
    } elseif (Auth::user()->role->name == 'student') {
        return redirect()->route('student.dashboard');
    }
})->middleware('auth')->name('home');

Route::middleware('auth')->group(function(){
    Route::get('/notifications',[NotificationController::class,'index'])->name("notifications.index");
    Route::get('mark-read-all',[NotificationController::class,'markAsRead'])->name('markAll');
    Route::get('mark-read-by-id/{id}',[NotificationController::class,'markAsReadById'])->name('markId');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

    Route::prefix('data-dosen')->group(function () {
        Route::get('/', [LecturerController::class, 'index'])->name('lecturer.index');
        Route::post('/store', [LecturerController::class, 'store'])->name('lecturer.store');
        Route::patch('/update/{user}', [LecturerController::class, 'update'])->name('lecturer.update');
        Route::delete('/delete/{user}', [LecturerController::class,'destroy'])->name('lecturer.destroy');
    });

    Route::prefix('data-mahasiswa')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('student.index');
        Route::post('/store', [StudentController::class, 'store'])->name('student.store');
        Route::patch('/update/{user}', [StudentController::class, 'update'])->name('student.update');
        Route::delete('/delete/{user}', [StudentController::class,'destroy'])->name('student.destroy');
    }); 
});

Route::post('lessons/{lesson}/generate-questions',[LessonController::class,'generate_questions'])->name('lessons.generate')->middleware(['auth', 'role:admin,lecturer']);
Route::get('lessons/{lesson}/result',[LessonController::class,'result'])->name('lessons.result')->middleware(['auth', 'role:admin,lecturer']);
Route::resource('lessons',LessonController::class)->only([
    'index','show','store','update','destroy'
])->middleware(['auth', 'role:admin,lecturer']);


Route::prefix('lecturer')->middleware(['auth', 'role:lecturer'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'lecturer'])->name('lecturer.dashboard');
});

Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'student'])->name('student.dashboard');
    
    // Quiz routes
    Route::get('/quiz', [\App\Http\Controllers\QuizController::class, 'index'])->name('student.quiz.index');
    Route::get('/lessons/{lesson}/quiz', [\App\Http\Controllers\QuizController::class, 'show'])->name('student.quiz.show');
    Route::post('/lessons/{lesson}/quiz', [\App\Http\Controllers\QuizController::class, 'submit'])->name('student.quiz.submit');
    Route::get('/lessons/{lesson}/quiz/result', [\App\Http\Controllers\QuizController::class, 'result'])->name('student.quiz.result');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentQuizController;

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
        Route::get('/', [LecturerController::class, 'index'])->name('admin.lecturer.index');
        Route::post('/store', [LecturerController::class, 'store'])->name('admin.lecturer.store');
        Route::patch('/update/{uuid}', [LecturerController::class, 'update'])->name('admin.lecturer.update');
        Route::delete('delete/{uuid}', [LecturerController::class,'destroy'])->name('admin.lecturer.destroy');
    });

    Route::prefix('data-kelas')->group(function () {
        Route::get('/', [KelasController::class, 'index'])->name('admin.kelas.index');
    });

    Route::prefix('data-mahasiswa')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('admin.student.index');
    });
});

Route::prefix('lecturer')->middleware(['auth', 'role:lecturer'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'lecturer'])->name('lecturer.dashboard');

    Route::prefix('data-kelas')->group(function () {
        Route::get('/', [KelasController::class, 'index'])->name('lecturer.kelas.index');
    });

    Route::prefix('data-mahasiswa')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('lecturer.student.index');
    });
});

Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'student'])->name('student.dashboard');

    Route::get('/quiz-not-taken', [StudentQuizController::class, 'index'])->name('student.quiz.not_taken');
    Route::get('/quiz-on-progress', [StudentQuizController::class, 'index2'])->name('student.quiz.on_progress');
    Route::get('/quiz-ended', [StudentQuizController::class, 'index3'])->name('student.quiz.ended');
});

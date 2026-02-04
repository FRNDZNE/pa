<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\DashboardController;
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
        Route::post('/upload', [LecturerController::class, 'upload'])->name('lecturer.upload');
        Route::patch('/update/{user:uuid}', [LecturerController::class, 'update'])->name('lecturer.update');
        Route::delete('/delete/{user:uuid}', [LecturerController::class,'destroy'])->name('lecturer.destroy');
    });

    Route::prefix('data-mahasiswa')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('student.index');
        Route::post('/store', [StudentController::class, 'store'])->name('student.store');
        Route::post('/upload', [StudentController::class, 'upload'])->name('student.upload');
        Route::patch('/update/{user:uuid}', [StudentController::class, 'update'])->name('student.update');
        Route::delete('/delete/{user:uuid}', [StudentController::class,'destroy'])->name('student.destroy');
    });

    Route::prefix('lessons')->group(function () {
        
    });
});

Route::prefix('lecturer')->middleware(['auth', 'role:lecturer'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'lecturer'])->name('lecturer.dashboard');
    
});

Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'student'])->name('student.dashboard'); 
});

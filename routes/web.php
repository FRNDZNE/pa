<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDosenController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware('guest')->name('welcome');

// Menggunakan Semua Route yang ada di Auth Kecuali Register
// Auth::routes();
Auth::routes([
    'register' => false,
]);


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', function () {
    if(Auth::user()->role->name == 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif(Auth::user()->role->name == 'lecturer') {
        return redirect()->route('lecturer.dashboard');
    } elseif(Auth::user()->role->name == 'student') {
        return redirect()->route('student.dashboard');
    }
})->middleware('auth')->name('home');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

    Route::prefix('data-dosen')->group(function () {
        Route::get('/', [AdminDosenController::class, 'index'])->name('admin.dosen.index');
        
    });
});

Route::prefix('lecturer')->middleware(['auth', 'role:lecturer'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'lecturer'])->name('lecturer.dashboard');
});

Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'student'])->name('student.dashboard');

    Route::get('/quiz-not-taken', [StudentQuizController::class, 'index'])->name('student.index');
    Route::get('/quiz-on-progress', [StudentQuizController::class, 'index2'])->name('student.index2');
    Route::get('/quiz-ended', [StudentQuizController::class, 'index3'])->name('student.index3');
});


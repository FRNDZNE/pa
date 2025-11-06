<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\StudentController;


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
        Route::get('/', [LecturerController::class, 'index'])->name('admin.dosen.index');
        Route::post('/store', [LecturerController::class, 'store'])->name('admin.dosen.store');
        Route::put('/update/{uuid}', [LecturerController::class, 'update'])->name('admin.dosen.update');
        Route::delete('/destroy/{uuid}}', [LecturerController::class, 'destroy'])->name('admin.dosen.destroy');
        Route::delete('/destroy-all', [LecturerController::class, 'destroy_all'])->name('admin.dosen.destroy_all');   
    });

    Route::prefix('data-kelas')->group(function () {
        Route::get('/', [KelasController::class, 'index'])->name('admin.class.index');
        Route::post('/store', [KelasController::class, 'store'])->name('admin.class.store');
        Route::put('/update/{uuid}', [KelasController::class, 'update'])->name('admin.class.update');
        Route::delete('/destroy/{uuid}}', [KelasController::class, 'destroy'])->name('admin.class.destroy');
        Route::delete('/destroy-all', [KelasController::class, 'destroy_all'])->name('admin.class.destroy_all');   
    });
    
    Route::prefix('data-mahasiswa')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('admin.student.kelas');
        Route::get('/{kelas_uuid}', [StudentController::class, 'show'])->name('admin.student.index');
        Route::post('/{kelas_uuid}/store', [StudentController::class, 'store'])->name('admin.student.store');
        Route::put('/{kelas_uuid}/update/{student_uuid}', [StudentController::class, 'update'])->name('admin.student.update');
        Route::delete('/{kelas_uuid}/destroy/{student_uuid}', [StudentController::class, 'destroy'])->name('admin.student.destroy');
        Route::delete('/{kelas_uuid}/destroy-all', [StudentController::class, 'destroy_all'])->name('admin.student.destroy_all');  
    });
});

Route::prefix('lecturer')->middleware(['auth', 'role:lecturer'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'lecturer'])->name('lecturer.dashboard');

    Route::prefix('data-kelas')->group(function () {
        Route::get('/', [KelasController::class, 'index'])->name('lecturer.class.index');
        Route::post('/store', [KelasController::class, 'store'])->name('lecturer.class.store');
        Route::put('/update/{uuid}', [KelasController::class, 'update'])->name('lecturer.class.update');
        Route::delete('/destroy/{uuid}}', [KelasController::class, 'destroy'])->name('lecturer.class.destroy');
        Route::delete('/destroy-all', [KelasController::class, 'destroy_all'])->name('lecturer.class.destroy_all');   
    });
    
    Route::prefix('data-mahasiswa')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('lecturer.student.kelas');
        Route::get('/{kelas_uuid}', [StudentController::class, 'show'])->name('lecturer.student.index');
        Route::post('/{kelas_uuid}/store', [StudentController::class, 'store'])->name('lecturer.student.store');
        Route::put('/{kelas_uuid}/update/{student_uuid}', [StudentController::class, 'update'])->name('lecturer.student.update');
        Route::delete('/{kelas_uuid}/destroy/{student_uuid}', [StudentController::class, 'destroy'])->name('lecturer.student.destroy');
        Route::delete('/{kelas_uuid}/destroy-all', [StudentController::class, 'destroy_all'])->name('lecturer.student.destroy_all');  
    });
});

Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'student'])->name('student.dashboard');
    Route::get('/quiz-not-taken', [StudentQuizController::class, 'index'])->name('student.index');
    Route::get('/quiz-on-progress', [StudentQuizController::class, 'index2'])->name('student.index2');
    Route::get('/quiz-ended', [StudentQuizController::class, 'index3'])->name('student.index3');
});

Route::get('/new', function() {
    return view('layouts.new');
})->name('new');




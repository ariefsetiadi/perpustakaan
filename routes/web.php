<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\{LoginController, HomeController};
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('postLogin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Profil
    Route::get('/profil', [LoginController::class, 'profil'])->name('profil');
    Route::post('/profil', [LoginController::class, 'updateProfil'])->name('updateProfil');

    // Password
    Route::get('/password', [LoginController::class, 'password'])->name('change.password');
    Route::post('/password', [LoginController::class, 'updatePassword'])->name('updatePassword');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware(['isAdmin'])->group(function () {
        Route::prefix('data')->group(function () {
            Route::prefix('petugas')->group(function () {
                Route::get('/', [AdminController::class, 'index'])->name('petugas.index');
                Route::get('/create', [AdminController::class, 'create'])->name('petugas.create');
                Route::post('/store', [AdminController::class, 'store'])->name('petugas.store');
                Route::get('/detail/{id}', [AdminController::class, 'show'])->name('petugas.show');
                Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('petugas.edit');
                Route::post('/update', [AdminController::class, 'update'])->name('petugas.update');
                Route::get('/delete/{id}', [AdminController::class, 'destroy'])->name('petugas.delete');
                Route::get('/trash', [AdminController::class, 'trash'])->name('petugas.trash');
                Route::get('/restore/{id}', [AdminController::class, 'restore'])->name('petugas.restore');
                Route::get('/reset/{id}', [AdminController::class, 'reset'])->name('petugas.reset');
            });
        });
    });
});

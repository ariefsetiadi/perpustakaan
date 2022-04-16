<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\{LoginController, HomeController};
use App\Http\Controllers\Data\{OfficerController, MemberController, PenaltyController, CategoryController, CollectionController};
use App\Http\Controllers\Transaction\LoanController;

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
            Route::prefix('officer')->group(function () {
                Route::get('/', [OfficerController::class, 'index'])->name('officer.index');
                Route::get('/create', [OfficerController::class, 'create'])->name('officer.create');
                Route::post('/store', [OfficerController::class, 'store'])->name('officer.store');
                Route::get('/detail/{id}', [OfficerController::class, 'show'])->name('officer.show');
                Route::get('/edit/{id}', [OfficerController::class, 'edit'])->name('officer.edit');
                Route::post('/update', [OfficerController::class, 'update'])->name('officer.update');
                Route::get('/delete/{id}', [OfficerController::class, 'destroy'])->name('officer.delete');
                Route::get('/trash', [OfficerController::class, 'trash'])->name('officer.trash');
                Route::get('/restore/{id}', [OfficerController::class, 'restore'])->name('officer.restore');
                Route::get('/reset/{id}', [OfficerController::class, 'reset'])->name('officer.reset');
            });

            Route::prefix('penalty')->group(function () {
                Route::get('/', [PenaltyController::class, 'index'])->name('penalty.index');
                Route::get('/edit/{id}', [PenaltyController::class, 'edit'])->name('penalty.edit');
                Route::post('/update', [PenaltyController::class, 'update'])->name('penalty.update');
            });

            Route::prefix('category')->group(function () {
                Route::get('/', [CategoryController::class, 'index'])->name('category.index');
                Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
                Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
                Route::post('/update', [CategoryController::class, 'update'])->name('category.update');
                Route::get('/delete/{id}', [CategoryController::class, 'destroy'])->name('category.delete');
                Route::get('/trash', [CategoryController::class, 'trash'])->name('category.trash');
                Route::get('/restore/{id}', [CategoryController::class, 'restore'])->name('category.restore');
            });

            Route::prefix('collection')->group(function () {
                Route::get('/', [CollectionController::class, 'index'])->name('collection.index');
                Route::get('/create', [CollectionController::class, 'create'])->name('collection.create');
                Route::post('/store', [CollectionController::class, 'store'])->name('collection.store');
                Route::get('/edit-stock/{id}', [CollectionController::class, 'editStock'])->name('collection.editStock');
                Route::post('/update-stock', [CollectionController::class, 'updateStock'])->name('collection.updateStock');
                Route::get('/detail/{id}', [CollectionController::class, 'show'])->name('collection.show');
                Route::get('/edit/{id}', [CollectionController::class, 'edit'])->name('collection.edit');
                Route::post('/update', [CollectionController::class, 'update'])->name('collection.update');
                Route::get('/delete/{id}', [CollectionController::class, 'destroy'])->name('collection.delete');
                Route::get('/trash', [CollectionController::class, 'trash'])->name('collection.trash');
                Route::get('/restore/{id}', [CollectionController::class, 'restore'])->name('collection.restore');
            });
        });

        Route::prefix('transaction')->group(function () {
            Route::prefix('loan')->group(function () {
                Route::get('/', [LoanController::class, 'index'])->name('loan.index');
                Route::get('/create', [LoanController::class, 'create'])->name('loan.create');
                Route::post('/store', [LoanController::class, 'store'])->name('loan.store');
                Route::get('/detail/{id}', [LoanController::class, 'show'])->name('loan.show');

                // Cart
                Route::get('/getCollection', [LoanController::class, 'getCollection'])->name('cart.getCollection');
                Route::post('/cart/store', [LoanController::class, 'addCart'])->name('cart.store');
                Route::get('/deleteCart/{id}', [LoanController::class, 'deleteCart'])->name('cart.deleteCart');
            });
        });
    });

    Route::prefix('data')->group(function () {
        Route::prefix('member')->group(function () {
            Route::get('/', [MemberController::class, 'index'])->name('member.index');
            Route::get('/create', [MemberController::class, 'create'])->name('member.create');
            Route::post('/store', [MemberController::class, 'store'])->name('member.store');
            Route::get('/detail/{id}', [MemberController::class, 'show'])->name('member.show');
            Route::get('/edit/{id}', [MemberController::class, 'edit'])->name('member.edit');
            Route::post('/update', [MemberController::class, 'update'])->name('member.update');
            Route::get('/delete/{id}', [MemberController::class, 'destroy'])->name('member.delete');
            Route::get('/trash', [MemberController::class, 'trash'])->name('member.trash');
            Route::get('/restore/{id}', [MemberController::class, 'restore'])->name('member.restore');
            Route::get('/print/{id}', [MemberController::class, 'print'])->name('member.print');
        });
    });
});

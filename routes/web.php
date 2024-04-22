<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RateController;
use Illuminate\Support\Facades\Route;

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
//route login
Route::get('/', [DashboardController::class, 'index']);
Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'process']);

// route download
Route::post('/download/book/{id}', [BookController::class, 'download']);

// route rate
Route::get('/rate/book/{id}', [BookController::class, 'setRate']);

Route::group(['middleware' => ['auth']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // add rate
    Route::post('/rate/create', [RateController::class, 'createRate']);

    Route::group(['middleware' => 'adminRole'], function () {
        // route book
        Route::resource('/book', BookController::class);

        // route category
        Route::resource('/category', CategoryController::class);

        // route rate
//        Route::resource('/rate', RateController::class);
    });
});



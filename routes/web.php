<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;

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

// Root route
Route::get('/', function () {
    return view('welcome');
});

// API routes with middleware
Route::prefix('api')->middleware('ensure.token.is.valid')->group(function () {
    // User Register and Login
    Route::get('login', [UserController::class, 'login']);
    Route::post('users', [UserController::class, 'addUser']);

    Route::put('password', [UserController::class, 'updatePassword']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    // Email route
    Route::post('email', [EmailController::class, 'generateMail']);
    Route::post('forgetPassword', [UserController::class, 'generateMail']);
});

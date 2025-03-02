<?php

use App\Http\Controllers\UserCategoryAccessController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoCategoryController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/user-access', [UserCategoryAccessController::class, 'index'])->name('access.management');

Route::get('/user-management', [UserController::class, 'index'])->name('user.management');

Route::get('/video-management', [VideoController::class, 'index'])->name('video.management');

Route::get('/category-management', [VideoCategoryController::class, 'index'])->name('category.management');


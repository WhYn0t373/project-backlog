<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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

// Existing routes...
// Route::get('/', function () {
//     return view('welcome');
// });

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit');

// Root route
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Home route
Route::get('/home', function () {
    return view('home');
})->name('home');

// Dummy routes for navigation links
Route::get('/register', function () {
    return 'register';
})->name('register');

Route::get('/password/reset', function () {
    return 'password reset';
})->name('password.request');

Route::get('/profile/show', function () {
    return 'profile';
})->name('profile.show');

Route::post('/logout', function () {
    return 'logout';
})->name('logout');
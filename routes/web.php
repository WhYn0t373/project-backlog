<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Enjoy building your
| application!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| The following line registers all authentication routes including the
| email‑verification routes. The 'verify' option ensures routes for
| email verification are created.
|
*/

Auth::routes(['verify' => true]);

/*
|--------------------------------------------------------------------------
| Home Route
|--------------------------------------------------------------------------
|
| A simple home page that authenticated users are redirected to after
| successful login. The route is protected by the 'auth' middleware.
|
*/

Route::get('/home', function () {
    return view('home');
})->middleware('auth');

// Add any additional application routes below.
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt');

Route::get('/profile', function (Illuminate\Http\Request $request) {
    $user = $request->attributes->get('user');
    return response()->json(['user' => $user]);
})->middleware('jwt');
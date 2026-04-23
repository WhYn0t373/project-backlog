<?php

use Illuminate\Support\Facades\Route;

// This file is automatically scanned by the framework and does not need to be included directly.
Route::get('/', function () {
    return view('home');
})->name('home');
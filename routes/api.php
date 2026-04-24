<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\ConversionController;

// Feature deletion – only admins
Route::middleware('role:admin')
    ->delete('/features/{feature}', [FeatureController::class, 'destroy']);

// Conversion endpoint – accessible by admins and standard users
Route::middleware('role:admin,user')
    ->post('/conversion', [ConversionController::class, 'convert']);
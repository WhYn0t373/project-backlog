<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you may register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/conversion/upload', [ConversionController::class, 'upload'])
    ->name('conversion.upload');

Route::get('/conversion/download/{file}', [ConversionController::class, 'download'])
    ->name('conversion.download');
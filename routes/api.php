<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Existing API routes...

/**
 * Health check endpoint.
 *
 * This simple endpoint returns a 200 OK status with a JSON payload.
 * It can be used by monitoring tools or load test scripts to verify
 * that the API is reachable and functioning.
 */
Route::get('/health', function () {
    return response()->json(['status' => 'ok'], 200);
});
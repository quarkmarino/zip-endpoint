<?php

use App\Http\Controllers\ZipCodeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('zip-codes/{zip_code}', [ZipCodeController::class, 'viaEloquentAndPowerJoins'])->where('zip_code', '[0-9]{4,5}');

Route::get('zip-codes/{zip_code}/pure-eloquent', [ZipCodeController::class, 'viaPureEloquent'])->where('zip_code', '[0-9]{4,5}');

Route::get('zip-codes/{zip_code}/query-builder', [ZipCodeController::class, 'viaQueryBuilder'])->where('zip_code', '[0-9]{4,5}');

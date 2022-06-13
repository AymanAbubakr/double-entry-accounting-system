<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserController;
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
    echo 'hello there';
    return $request->user();
});


Route::get('/accounts', [AccountController::class, 'index']);
Route::post('/accounts', [AccountController::class, 'store']);
Route::put('/accounts/{account}', [AccountController::class, 'update']);
Route::delete('/accounts/{account}', [AccountController::class, 'destroy']);

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);


Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to our api',
    ]);
});

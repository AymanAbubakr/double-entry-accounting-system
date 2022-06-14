<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
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



Route::get('/accounts', [AccountController::class, 'index'])->middleware('auth:sanctum');
Route::post('/accounts', [AccountController::class, 'store'])->middleware('auth:sanctum');
Route::put('/accounts/{account}', [AccountController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/accounts/{account}', [AccountController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::post('/users', [UserController::class, 'store'])->middleware('auth:sanctum');
Route::put('/users/{user}', [UserController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('auth:sanctum');


Route::get('/transactions', [TransactionController::class, 'index'])->middleware('auth:sanctum');
Route::post('/transactions', [TransactionController::class, 'store'])->middleware('auth:sanctum');
Route::put('/transactions/revert/{journalId}', [TransactionController::class, 'revertTransaction'])->middleware('auth:sanctum');
Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->middleware('auth:sanctum');



Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);


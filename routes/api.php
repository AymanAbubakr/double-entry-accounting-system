<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactTypeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TypeAccountController;
use App\Http\Controllers\UserController;
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
Route::post('/transactions/contact', [TransactionController::class, 'contactTransaction'])->middleware('auth:sanctum');
Route::put('/transactions/revert/{journalId}', [TransactionController::class, 'revertTransaction'])->middleware('auth:sanctum');
Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/contacts', [ContactController::class, 'index'])->middleware('auth:sanctum');
Route::post('/contacts', [ContactController::class, 'store'])->middleware('auth:sanctum');
Route::put('/contacts/{contact}', [ContactController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/contactTypes', [ContactTypeController::class, 'index'])->middleware('auth:sanctum');
Route::post('/contactTypes', [ContactTypeController::class, 'store'])->middleware('auth:sanctum');
Route::put('/contactTypes/{contactType}', [ContactTypeController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/contactTypes/{contactType}', [ContactTypeController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/typeAccount', [TypeAccountController::class, 'index'])->middleware('auth:sanctum');
Route::post('/typeAccount', [TypeAccountController::class, 'store'])->middleware('auth:sanctum');
Route::put('/typeAccount/{typeAccount}', [TypeAccountController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/typeAccount/{typeAccount}', [TypeAccountController::class, 'destroy'])->middleware('auth:sanctum');



Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

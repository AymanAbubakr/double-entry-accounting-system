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

Route::group(
  [
    'middleware' => 'auth:sanctum',
  ],
  function ($router) {
    Route::get('/accounts', [AccountController::class, 'index']);
    Route::post('/accounts', [AccountController::class, 'store']);
    Route::put('/accounts/{account}', [AccountController::class, 'update']);
    Route::delete('/accounts/{account}', [AccountController::class, 'destroy']);
  }
);

Route::group(
  [
    'middleware' => 'auth:sanctum',
  ],
  function ($router) {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
  }
);


Route::group(
  [
    'middleware' => 'auth:sanctum',
  ],
  function ($router) {
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::post('/transactions/contact', [TransactionController::class, 'contactTransaction']);
    Route::put('/transactions/revert/{journalId}', [TransactionController::class, 'revertTransaction']);
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy']);
  }
);


Route::group(
  [
    'middleware' => 'auth:sanctum',
  ],
  function ($router) {
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::put('/contacts/{contact}', [ContactController::class, 'update']);
    Route::put('/contacts/assign/{contact}', [ContactController::class, 'assignTypes']);
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);
  }
);

Route::group(
  [
    'middleware' => 'auth:sanctum',
  ],
  function ($router) {
    Route::get('/contactTypes', [ContactTypeController::class, 'index']);
    Route::post('/contactTypes', [ContactTypeController::class, 'store']);
    Route::put('/contactTypes/{contactType}', [ContactTypeController::class, 'update']);
    Route::delete('/contactTypes/{contactType}', [ContactTypeController::class, 'destroy']);
  }
);


Route::group(
  [
    'middleware' => 'auth:sanctum',
  ],
  function ($router) {
    Route::get('/typeAccount', [TypeAccountController::class, 'index']);
    Route::post('/typeAccount', [TypeAccountController::class, 'store']);
    Route::put('/typeAccount/{typeAccount}', [TypeAccountController::class, 'update']);
    Route::delete('/typeAccount/{typeAccount}', [TypeAccountController::class, 'destroy']);
  }
);



Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

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
    //! Accounts
    Route::resource('accounts', AccountController::class);

    //! Users
    Route::resource('users', UserController::class);

    //! Transactions
    Route::resource('transactions', TransactionController::class);
    Route::get('/transactions/balance/{accountId}',  [TransactionController::class, 'getBalance']);
    Route::post('/transactions/contact', [TransactionController::class, 'contactTransaction']);
    Route::put('/transactions/revert/{journalId}', [TransactionController::class, 'revertTransaction']);

    //! Contacts
    Route::resource('contacts', ContactController::class);
    Route::put('/contacts/assign/{contact}', [ContactController::class, 'assignTypes']);

    //! Contact Types
    Route::resource('contactTypes', ContactTypeController::class);

    //! Type Accounts
    Route::resource('typeAccount', TypeAccountController::class);
  }
);




Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\Account;



class AccountController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::getAll();

        return $this->sendResponse($accounts, 'Accounts retrieved successfully.');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountRequest $accountRequest)
    {
        $account = Account::create($accountRequest->all());

        return $this->sendResponse($account, 'Account created successfully.');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AccountRequest $accountRequest
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(AccountRequest $accountRequest, Account $account)
    {
        $account->update($accountRequest->all());

        return $this->sendResponse($account, 'Account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        $account->update(['deleted' => 1]);

        return $this->sendResponse($account, 'Account deleted successfully.');
    }
}

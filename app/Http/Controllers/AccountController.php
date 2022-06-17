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

        $parent_tree_ids = [];
        if ($accountRequest->parent_id) {
            $parent_parent_tree_ids = Account::find($accountRequest->parent_id)->parent_tree_ids;
            if ($parent_parent_tree_ids) {
                $parent_tree_ids = $parent_parent_tree_ids;
                array_unshift($parent_tree_ids, $accountRequest->parent_id);
            }
        }

        $account = Account::create(
            [
                'name' => $accountRequest->name,
                'parent_id' => $accountRequest->parent_id,
                'parent_tree_ids' => $parent_tree_ids,
            ]
        );

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

        $parent_tree_ids = [];
        if ($accountRequest->parent_id) {
            $parent_parent_tree_ids = Account::find($accountRequest->parent_id)->parent_tree_ids;
            if ($parent_parent_tree_ids) {
                $parent_tree_ids = $parent_parent_tree_ids;
                array_unshift($parent_tree_ids, $accountRequest->parent_id);
            }
        }

        $account->update(
            [
                'name' => $accountRequest->name,
                'parent_id' => $accountRequest->parent_id,
                'parent_tree_ids' => $parent_tree_ids,
            ]
        );

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

        $childAccounts =  Account::where([
            ['parent_id', $account->id],
            ['deleted', 0]
        ])->first();

        if ($childAccounts) {
            return $this->sendError(
                'Account has child accounts! please remove the child then process the action.',
                [],
                400
            );
        }

        $account->update(['deleted' => 1]);

        return $this->sendResponse($account, 'Account deleted successfully.');
    }
}

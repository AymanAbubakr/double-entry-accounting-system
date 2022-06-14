<?php

namespace App\Http\Controllers;

use App\Models\TypeAccount;
use Illuminate\Http\Request;

class TypeAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $typeAccounts = TypeAccount::getAll();

        return $this->sendResponse($typeAccounts, 'TypeAccounts retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $typeAccount = TypeAccount::create(
            $request->only(['name', 'type_id'])
        );

        return $this->sendResponse($typeAccount, 'TypeAccount created successfully.');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TypeAccountRequest $typeAccountRequest
     * @param  \App\Models\TypeAccount  $typeAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeAccount $typeAccount)
    {
        $typeAccount->update(
            $request->only(['name', 'type_id'])
        );

        return $this->sendResponse($typeAccount, 'TypeAccount updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeAccount $typeAccount)
    {
        $typeAccount->update(['deleted' => 1]);

        return $this->sendResponse($typeAccount, 'TypeAccount deleted successfully.');
    }
}

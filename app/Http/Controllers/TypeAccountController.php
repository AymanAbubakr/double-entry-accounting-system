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
        $typeAccounts = TypeAccount::all()->where('deleted', 0);

        return response()->json([
            'status' => true,
            'typeAccounts' => $typeAccounts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        return response()->json([
            'message' => "TypeAccount Created successfully!",
            'typeAccount' => $typeAccount
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TypeAccount  $typeAccount
     * @return \Illuminate\Http\Response
     */
    public function show(TypeAccount $typeAccount)
    {
        
    }


     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TypeAccount  $typeAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(TypeAccount $typeAccount)
    {
        //
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

        return response()->json([
            'message' => "TypeAccount Updated successfully!",
            'typeAccount' => $typeAccount,
        ], 200);
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

        return response()->json([
            'message' => "TypeAccount Deleted successfully!",
        ], 200);
    }
}

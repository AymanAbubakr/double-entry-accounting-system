<?php

namespace App\Http\Controllers;

use App\Models\ContactType;
use Illuminate\Http\Request;

class ContactTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contactType = ContactType::all()->where('deleted', 0);

        return response()->json([
            'status' => true,
            'contactType' => $contactType
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
        $contactType = ContactType::create(
            $request->only(['name'])
        );

        return response()->json([
            'message' => "Contact Type Created successfully!",
            'contactType' => $contactType
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactType  $contactType
     * @return \Illuminate\Http\Response
     */
    public function show(ContactType $contactType)
    {
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactType  $contactType
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactType $contactType)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ContactTypeRequest $contactTypeRequest
     * @param  \App\Models\ContactType  $contactType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactType $contactType)
    {
        $contactType->update(
            $request->only(['name'])
        );

        return response()->json([
            'message' => "Contact Type Updated successfully!",
            'contactType' => $contactType,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactType $contactType)
    {
        $contactType->update(['deleted' => 1]);

        return response()->json([
            'message' => "Contact Type Deleted successfully!",
        ], 200);
    }
}

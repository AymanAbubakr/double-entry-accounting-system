<?php

namespace App\Http\Controllers;

use App\Models\ContactType;
use Illuminate\Http\Request;

class ContactTypeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contactType = ContactType::getAll();

        return $this->sendResponse($contactType, 'ContactType retrieved successfully.');
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

        return $this->sendResponse($contactType, 'ContactType created successfully.');
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

        return $this->sendResponse($contactType, 'ContactType updated successfully.');
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

        return $this->sendResponse($contactType, 'ContactType deleted successfully.');
    }
}

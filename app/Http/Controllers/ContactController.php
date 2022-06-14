<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::getAll();

        return $this->sendResponse($contacts, 'Contacts retrieved successfully.');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contact = Contact::create(
            $request->only(['name', 'type_id'])
        );

        return $this->sendResponse($contact, 'Contact created successfully.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ContactRequest $contactRequest
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $contact->update(
            $request->only(['name', 'type_id'])
        );

        return $this->sendResponse($contact, 'Contact updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->update(['deleted' => 1]);

        return $this->sendResponse($contact, 'Contact deleted successfully.');
    }
}

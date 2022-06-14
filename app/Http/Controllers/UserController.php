<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::getAll();

        return $this->sendResponse($users, 'Users retrieved successfully.');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\UserRequest  $userRequest
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $userRequest)
    {
        $user = User::create([
            'name' => $userRequest->name,
            'email' => $userRequest->email,
            'password' =>  Hash::make($userRequest->password),
        ]);

        return $this->sendResponse($user, 'User created successfully.');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UserRequest  $userRequest
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $userRequest, User $user)
    {
        $user->update($userRequest->all());

        return $this->sendResponse($user, 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->update(['deleted' => 1]);

        return $this->sendResponse($user, 'User deleted successfully.');
    }
}

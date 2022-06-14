<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all()->where('deleted', 0);

        return response()->json([
            'users' => $users
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

        return response()->json([
            'message' => "User Created successfully!",
            'user' => $user
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
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

        return response()->json([
            'message' => "User Updated successfully!",
            'user' => $user,

        ], 200);
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

        return response()->json([
            'message' => "User Deleted successfully!",
        ], 200);
    }
}

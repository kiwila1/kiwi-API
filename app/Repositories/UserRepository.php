<?php

namespace App\Repositories;

use App\Models\User;
use App\Http\Resources\UserResource;

class UserRepository
{
    public function __construct()
    {
        //
    }

    public function all() {

       return  UserResource::collection(User::with(['role', 'account'])->get());
    }

    public function register(Request $request)
    {
    	//Validate data
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|email|unique:users',
            'pass' => 'required|string|min:6|max:50',
            'rpass' => 'required|confirmed|min:6|max:50',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $user = User::create($request->except(['rpass']));

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

}

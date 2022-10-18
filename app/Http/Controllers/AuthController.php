<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register
     * @param Request $request
     * @return User
     */
    public function register(Request $request)
    {
        try {
            //validated
            $validate = Validator::make($request->all(),
            [
                 'name' => 'required',
                 'email' => 'required|email|unique:users,email',
                 'password' => 'required'
            ]);

            if($validate->fails()){
                 return response()->json([
                     'status' => false,
                     'message' => 'validation error',
                     'error' => $validate->errors()
                 ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

    }
}

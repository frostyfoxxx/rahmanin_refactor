<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Validated;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function signUp(Request $request)
    {
        $validated = Validated::globalValidation($request->all());
        

        if($validated->fails()) {
            return Validated::errorResponse($validated);
        }

        $users = User::checkCreateUser($request);
        if($users) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => "The user is already registered"
                ]
            ], 422);
        }

        $user = User::createUser($request);

        if($user) {
            return response()->json([
                'data' => [
                    'code' => 201,
                    'message' => "Users has been created"
                ]
            ], 201);
        }
    }
}

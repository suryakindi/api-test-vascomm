<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            if(Auth::user()->role == "admin")
            {
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(
                [
                'access_token' => $token,
                'message'=> 'Success',
                'code' => 200,
                ]
            );
            }else{
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}

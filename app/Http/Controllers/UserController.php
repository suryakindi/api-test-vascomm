<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function user(Request $request)
    {
        $take = $request->input('take', 10); 
        $skip = $request->input('skip', 0); 
        $search = $request->input('search', ''); 
        
        $validator = Validator::make($request->all(), [
            'take' => 'integer|min:1|max:100',
            'skip' => 'integer|min:0',
            'search' => 'string|max:255', 
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }
        
        $users = User::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%');
        })
        ->skip($skip)
        ->take($take)
        ->get();
        if($users == NULL)
        {
            return response()->json([
                'message' => 'Not Found',
                'data' => $users,
            ], 404); 
        }
        return response()->json([
            'message' => 'Success Get Data User',
            'data' => $users,
            'code'=> 200,
        ], 200); 
    }
    public function createuser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user,
            'role'=>'user',
            'code'=> 201,
        ], 201); 
    }
    
    public function edituser(Request $request, $id)
    {
        
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email',
            'password' => 'string|min:5',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
            'code'=> 200,
        ], 200);

    }
    public function deleteuser($id)
    {
        $user = User::find($id);
        if($user){
            $user->delete();
            return response()->json([
                'message' => 'Success Delete User',
                'user_id'=> $id,
                'code'=> 200,
            ], 200); 
        }else{
            return response()->json([
                'message' => 'User Not Found',
                'code'=> 404,
            ], 404);    
        }
    }
    public function restoreuser($id)
    {
        $user = User::withTrashed()->where('id', $id)->first();
        if ($user) {
            $user->restore();
            return response()->json([
                'message' => 'Success Restore User',
                'user_id' => $id,
                'code'=> 200,
            ], 200);
        }else{
            return response()->json([
                'message' => 'User Not Found',
                'code'=> 404,
            ], 404);    
        }
      
    }
}

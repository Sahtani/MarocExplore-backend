<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{ 
    public function register(AuthRequest $request)
    {
        $fields = $request->validated();
        $image = explode('/', $fields['image']->store('public/usersImages'));
        $user = User::create([
            'name' => $fields['name'],
            'image' => $image[2],
            'email' => $fields['email'],
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('API Token')->plainTextToken;

    return response()->json([
        'status' => 'success',
        'message' => 'User created successfully',
        'user' => $user,
        'authorization' => [
            'token' => $token,
            'type' => 'Bearer',
        ]
    ]);
    }
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|',
            'password' => 'required|string'
        ]);
        $user = User::where('email', $fields['email'])->first();
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'Incorrect Credentials!'], 401);
        }
        if(!$token = auth()->attempt($fields)) {
            return response(['error' => 'Unauthorized'], 401);
        }
        $token = $user->createToken('API Token')->plainTextToken;

    return response()->json([
        'status' => 'success',
        'message' => 'User created successfully',
        'user' => $user,
        'authorization' => [
            'token' => $token,
            'type' => 'Bearer',
        ]
    ]);
    }
    public function logout(Request $request) : Response
    {
        auth()->user()->tokens()->delete();
        return response('You Logged Out!', 200);
    }
    
}
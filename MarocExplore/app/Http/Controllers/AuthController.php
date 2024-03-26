<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\FlareClient\Http\Response;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        $fields = $request->validated();
        $image = explode('/', $fields['image']->store('public/usersImages'));
        $user = User::create([
            'name' => $fields['name'],
            'username' => $fields['username'],
            'image' => $image[2],
            'email' => $fields['email'],
            'password' => Hash::make($request->password),
        ]);
        // $token = $this->createToken($user);
        return response([$user], 201);
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
        $jwt = $this->createToken($token);
        return response([$user, $jwt], 200);
    }
 
}
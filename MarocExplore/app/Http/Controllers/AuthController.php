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
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\Response(response=201, description="User registered successfully"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error"),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="User Name"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     description="User Email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     description="User Password"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                     description="User Image"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string",
     *                     format="password",
     *                     description="User Password Confirmation"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request) : Response
    {
        $fields = $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'required|image',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);
        $image = explode('/', $fields['image']->store('public/usersImages'));
        $user = User::create([
            'name' => $fields['name'],
            'image' => $image[2],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])]
        );

        return $this->login($request);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Authenticate user and generate JWT token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful login"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function login(Request $request) : Response
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

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout the authenticated user",
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\MediaType(
     *             mediaType="text/plain",
     *             example="You Logged Out!"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function logout(Request $request) 
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'You Logged Out!'], 200);
    }
    

    protected function createToken($token) : Response
    {
        return response([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 2880,
            'user' => auth()->user()
        ], 200);
    }
    
}
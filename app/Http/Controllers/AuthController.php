<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function update($request)
    {
        // Validate the new password length...

        $request->user()->fill([
            'password' => Hash::make($request->newPassword)
        ])->save();
    }


    // public function register(RegisterRequest $request)
    // {
    //     $validatedData = $request->validated();

    //     $user = User::create([
    //         "first_name" => $validatedData["first_name"],
    //         "last_name" => $validatedData["last_name"],
    //         "email" => $validatedData["email"],
    //         "password" => Hash::make($validatedData["password"])
    //     ]);

    //     $token = auth()->login($user);

    //     return response()->json([
    //         "status" => "success",
    //         "user" => $user,
    //         "authorization" => [
    //             "token" => $token
    //         ]
    //     ]);
    // }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        $credentials = [
            "email" => $validatedData["email"],
            "password" => $validatedData["password"]
        ];

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthorized"
            ], 401);
        }

        return response()->json(
            [
                "status" => "success",
                "user" => Auth::user(),
                "authorization" => [
                    "token" => $token,
                ]
            ]
        );
    }

    public function register()
    {
        $credentials = request(['first_name','last_name', 'email', 'password']);
        $credentials['password'] = bcrypt($credentials['password']);

        User::create($credentials);

        return response()->json('success');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        return response()->json([
            "status" => "success",
            "user" => Auth::user(),
            "authorization" => [
                "token" => Auth::refresh()
            ]
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->Auth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}

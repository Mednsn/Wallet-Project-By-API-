<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'you are not login check your informations'
        ], 403);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);
        $token = $user->createToken('token_api')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'bienvenue dans notre plateform.',
            'data' => ['user' => $user, 'token' => $token]

        ], 201);
    }

    public function login(LoginRequest $request)
    {

        $user = User::where('email', $request->validated('email'))->first();
        if (!$user || !Hash::check($request->validated('password'), $user->password)) {
            return response()->json([
                "success" => false,
                "message" => "Identifiants incorrects."

            ], 401);
        }
        $token = $user->createToken($user->name);
        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'votre avez connecter avec success.',
            'data' => ['user' => $user, 'token' => $token->plainTextToken]
        ]);
       
    }

    public function logout(Request $request)
    {
        // dd("arrived");
        // $user_id = $request->user()->id;
        $request->user()->tokens()->delete();

        // 'user_id'=> $user_id,
        return response()->json([
            'success' => true,
            'message' => 'you are logout now '
        ], 200);
    }
}

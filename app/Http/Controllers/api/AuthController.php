<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'status' => 'success',
            'data' => $user,
            'token' => $token
        ], 201);
    }

    public function login(LoginRequest $request)
    {

        $login = $request->validated();

        if (Auth::attempt($login)) {

            $token = $request->user()->createToken('token_api')->plainTextToken;
            return response()->json([
                'status' => 'you are login right now success',
                'user' => Auth::user(),
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'status' => 'errur'
            ], 401);
        }
      
        // return response()->json([
        //     'message'=>$messges,
        // ],401);
    }

    public function logout(Request $request)
    {
        // dd("arrived");
        // $user_id = $request->user()->id;
        $request->user()->tokens()->delete();

        return response()->json([
            // 'user_id'=> $user_id,
            'status' => 'success',
            'message' => 'you are logout now '
        ], 200);
    }
}

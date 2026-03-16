<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWalletRequest;
use App\Models\Wallet;
 use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallets = Wallet::with('transactions','users')->where('user_id',Auth::id())->get();
        // dd($wallet);
        return response()->json([
          'success'=>true,
          'message'=> 'voici la list des wallets.',
          'data'=>['wallets' => $wallets]
        ],200);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( StoreWalletRequest $request):  JsonResponse
    {
        $validated = $request->validated();

        $validated['user_id'] = Auth::id();
        $validated['balance'] = 0;
        $wallet = Wallet::create($validated);
        $wallet->load('transactions');
        // dd("arriver iici");
        return response()->json([
        'success' => true,
        'message' => 'wallet créé avec succes',
        'data' => ['wallet' => $wallet]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $id)
    {
        $transactions = Transaction::where('wallet_id', $id)->get();

        return response()->json([
            "success" => true,
            "message" => "Historique des transactions récupéré.",
            'data' => ['transactions' => $transactions]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */


    public function deposit(StoreTransactionRequest $request, int $id)
    {
        // dd("arrived deposit transaction");
        if ($request->validated('amount') <= 0) {
            return response()->json([
                "success" => false,
                "message" => "Erreur de validation.",
                "errors" => [
                    "amount" => ["Le montant doit être supérieur à 0"]
                ]

            ]);
        }

        $wallet = Wallet::find($id);
        $wallet->balance += $request->validated('amount');
        $wallet->save();
        $transaction = Transaction::create([
            'type'          => 'deposit',
            'amount'        => $request->validated('amount'),
            'description'   => $request->validated('description'),
            'balance_after' => $wallet->balance,
            'wallet_id' => $wallet->id
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Dépôt effectué avec succès.',
            'data'    => ['transaction' => $transaction, 'wallet' => $wallet]
        ]);
    }


    public function withdraw(StoreTransactionRequest $request, int $id): JsonResponse
    {
        // dd("arrived widhraw");
        $wallet = Wallet::find($id);

        if ($request->validated('amount') > $wallet->balance) {
            return response()->json([
                "success" => false,
                "message" => "essayer un autre montant s'il vous plait."
            ]);
        }

        $wallet->balance -= $request->validated('amount');
        $wallet->save();

        $transaction = Transaction::create([
            'type'          => 'withdraw',
            'amount'        => $request->validated('amount'),
            'description'   => $request->validated('description'),
            'balance_after' => $wallet->balance,
            'wallet_id' => $wallet->id
        ]);

        return response()->json([
            "success" => true,
            "message" => "vous avez reterer avec success.",
            'data' => ['transaction' => $transaction, 'wallet' => $wallet]

        ]);
    }

    public function transfer(StoreTransactionRequest $request, int $id)
    {
        // dd("arrived transfer");
        $wallet = Wallet::find($id);
        $recevoir = Wallet::find($request->validated('recevoir_wallet_id'));

        if ($wallet->currency !== $recevoir->currency) {
            return response()->json([
                'success' => false,
                'message' => 'Transfert impossible : les deux wallets doivent avoir la même devise.'
            ], 400);
        }

        if ($wallet->balance < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => "Solde insuffisant. Solde actuel : {$wallet->balance} {$wallet->currency}."
            ], 400);
        }

        $wallet->balance -= $request->validated('amount');
        $wallet->save();

        $recevoir->balance += $request->validated('amount');
        $recevoir->save();

        $transaction_out = Transaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'transfer_out',
            'amount' => $request->validated('amount'),
            'description' => $request->validated('description'),
            'recevoir_wallet_id' => $recevoir->id,
            'balance_after' => $wallet->balance
        ]);


        $transaction_in = Transaction::create([
            'wallet_id' => $recevoir->id,
            'type' => 'transfer_in',
            'amount' => $request->validated('amount'),
            'description' => $request->validated('description'),
            'sender_wallet_id' => $wallet->id,
            'balance_after' => $recevoir->balance
        ]);


        return response()->json([
            "success" => true,
            "message" => "Transfert effectué avec succès.",
            'data' => [
                'transaction_out' => $transaction_out,
                'transaction_in' => $transaction_in,
                'wallet' => $wallet
            ]

        ]);
    }
}

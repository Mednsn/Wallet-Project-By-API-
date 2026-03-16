<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->float('amount');
            $table->string('description');
            $table->string('type');/*['deposit', 'withdraw', 'transfer_in', 'transfer_out'] */
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->decimal('balance_after', 15, 2);
            $table->foreignId('recevoir_wallet_id')->nullable()->constrained('wallets')->nullOnDelete();
            $table->foreignId('sender_wallet_id')->nullable()->constrained('wallets')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

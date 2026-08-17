<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    protected function walletTypes()
    {
        return [User::WALLET_MAIN, User::WALLET_REFERRAL, User::WALLET_REWARD];
    }

    protected function activeWalletType(Request $request)
    {
        $walletType = $request->wallet_type ?? User::WALLET_MAIN;
        return in_array($walletType, $this->walletTypes()) ? $walletType : User::WALLET_MAIN;
    }

    public function index(Request $request)
    {
        $pageTitle = 'My Wallet';
        $user      = auth()->user();

        $wallets = [
            User::WALLET_MAIN     => $user->balance,
            User::WALLET_REFERRAL => $user->referral_balance,
            User::WALLET_REWARD   => $user->reward_balance,
        ];

        $walletType   = $this->activeWalletType($request);
        $transactions = Transaction::where('user_id', $user->id)
            ->where('wallet_type', $walletType)
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        return view('Template::user.wallet', compact('pageTitle', 'wallets', 'walletType', 'transactions'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class WalletController extends Controller
{
    public function index()
    {
        $pageTitle = 'Wallet Management';
        $users     = User::searchable(['username', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());

        $totalMain     = User::sum('balance');
        $totalReferral = User::sum('referral_balance');
        $totalReward   = User::sum('reward_balance');

        return view('admin.wallet.index', compact('pageTitle', 'users', 'totalMain', 'totalReferral', 'totalReward'));
    }
}

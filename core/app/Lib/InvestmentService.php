<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Investment;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

/**
 * Places an investment for a user. Shared by the "invest from wallet" screen and
 * by the deposit flow, where the plan is chosen up front and the investment is
 * placed automatically once the payment lands.
 */
class InvestmentService
{
    /**
     * Debits the main wallet and opens the plan. The caller is responsible for
     * checking the amount fits the plan and that the balance covers it.
     */
    public static function invest(User $user, Plan $plan, $amount)
    {
        $interest = $plan->interest_type == Status::FIXED
            ? $plan->interest
            : ($amount * $plan->interest) / 100;

        $user->balance -= $amount;
        $user->save();

        $investment                   = new Investment();
        $investment->trx              = getTrx();
        $investment->plan_id          = $plan->id;
        $investment->user_id          = $user->id;
        $investment->amount           = $amount;
        $investment->interest_type    = $plan->interest_type;
        $investment->interest_amount  = $interest;
        $investment->total_return     = $plan->total_return;
        $investment->next_return_date = Carbon::now()->addDay(1);
        $investment->status           = Status::RUNNING;
        $investment->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->remark       = 'invest';
        $transaction->details      = 'Invest on ' . $plan->name;
        $transaction->trx          = $investment->trx;
        $transaction->wallet_type  = 'main';
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New Investment In ' . $plan->name . ' from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.users.investment', $user->id);
        $adminNotification->save();

        notify($user, 'INVESTMENT', [
            'currency'     => gs('cur_text'),
            'trx'          => $transaction->trx,
            'plan'         => $plan->name,
            'amount'       => showAmount($amount, currencyFormat: false),
            'details'      => $transaction->details,
            'post_balance' => $user->balance,
            'interest'     => $interest,
            'total_return' => $investment->total_return,
        ]);

        return $investment;
    }
}

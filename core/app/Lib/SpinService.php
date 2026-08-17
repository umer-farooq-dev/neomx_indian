<?php

namespace App\Lib;

use App\Models\SpinReward;
use App\Models\SpinRule;
use App\Models\SpinRuleProgress;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserSpin;

class SpinService
{
    public static function grantSpins(User $user, int $count, $ruleId = null)
    {
        if ($count <= 0) {
            return;
        }
        for ($i = 0; $i < $count; $i++) {
            $spin           = new UserSpin();
            $spin->user_id  = $user->id;
            $spin->rule_id  = $ruleId;
            $spin->status   = 0;
            $spin->save();
        }
    }

    public static function availableSpins(User $user)
    {
        return UserSpin::where('user_id', $user->id)->where('status', 0)->count();
    }

    // Called once, right after a new account is created (any registration path).
    public static function evaluateSignupRule(User $user)
    {
        $rule = SpinRule::where('trigger_type', SpinRule::SIGNUP)->active()->first();
        if ($rule) {
            self::grantSpins($user, $rule->spins_granted, $rule->id);
        }
    }

    // Called whenever a referred user's deposit succeeds - re-evaluates the direct referrer's
    // referral-based spin rules against their current active-referral count.
    public static function evaluateReferralRules(User $referrer)
    {
        $activeCount = $referrer->activeReferralsCount();
        $rules       = SpinRule::where('trigger_type', SpinRule::REFERRAL_COUNT)->active()->get();

        foreach ($rules as $rule) {
            if ($rule->trigger_value <= 0) {
                continue;
            }

            $shouldHaveTriggered = intdiv($activeCount, $rule->trigger_value);

            $progress = SpinRuleProgress::firstOrCreate(
                ['user_id' => $referrer->id, 'rule_id' => $rule->id],
                ['triggered_count' => 0]
            );

            if ($shouldHaveTriggered > $progress->triggered_count) {
                $newTriggers = $shouldHaveTriggered - $progress->triggered_count;
                self::grantSpins($referrer, $newTriggers * $rule->spins_granted, $rule->id);
                $progress->triggered_count = $shouldHaveTriggered;
                $progress->save();
            }
        }
    }

    protected static function pickWeightedReward()
    {
        $rewards = SpinReward::active()->get();
        if ($rewards->isEmpty()) {
            return null;
        }

        $totalWeight = $rewards->sum('weight');
        if ($totalWeight <= 0) {
            return $rewards->first();
        }

        $rand       = random_int(1, $totalWeight);
        $cumulative = 0;
        foreach ($rewards as $reward) {
            $cumulative += $reward->weight;
            if ($rand <= $cumulative) {
                return $reward;
            }
        }
        return $rewards->last();
    }

    // Consumes one available spin and returns the won SpinReward, or null if no spin/reward available.
    public static function spin(User $user)
    {
        $availableSpin = UserSpin::where('user_id', $user->id)->where('status', 0)->orderBy('id')->first();
        if (!$availableSpin) {
            return null;
        }

        $reward = self::pickWeightedReward();
        if (!$reward) {
            return null;
        }

        $availableSpin->status     = 1;
        $availableSpin->reward_id  = $reward->id;
        $availableSpin->won_amount = $reward->amount;
        $availableSpin->used_at    = now();
        $availableSpin->save();

        if ($reward->amount > 0) {
            $user->reward_balance += $reward->amount;
            $user->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->amount       = $reward->amount;
            $transaction->post_balance = $user->reward_balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '+';
            $transaction->details      = 'Spin reward: ' . $reward->name;
            $transaction->remark       = 'spin_reward';
            $transaction->trx          = getTrx();
            $transaction->wallet_type  = 'reward';
            $transaction->save();
        }

        return $reward;
    }
}

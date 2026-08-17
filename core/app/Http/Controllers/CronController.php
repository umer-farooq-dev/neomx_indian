<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Lib\CurlRequest;
use App\Models\CronJob;
use App\Models\CronJobLog;
use App\Models\Investment;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;

class CronController extends Controller
{
    public function cron()
    {
        if (!auth('admin')->check() && request()->token !== env('CRON_SECRET')) {
            abort(403);
        }

        $general            = gs();
        $general->last_cron = now();
        $general->save();

        $crons = CronJob::with('schedule');

        if (request()->alias) {
            $crons->where('alias', request()->alias);
        } else {
            $crons->where('next_run', '<', now())->where('is_running', Status::YES);
        }
        $crons = $crons->get();
        foreach ($crons as $cron) {
            $cronLog              = new CronJobLog();
            $cronLog->cron_job_id = $cron->id;
            $cronLog->start_at    = now();
            if ($cron->is_default) {
                $controller = new $cron->action[0];
                try {
                    $method = $cron->action[1];
                    $controller->$method();
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            } else {
                try {
                    CurlRequest::curlContent($cron->url);
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            }
            $cron->last_run = now();
            $cron->next_run = now()->addSeconds($cron->schedule->interval);
            $cron->save();

            $cronLog->end_at = $cron->last_run;

            $startTime         = Carbon::parse($cronLog->start_at);
            $endTime           = Carbon::parse($cronLog->end_at);
            $diffInSeconds     = $startTime->diffInSeconds($endTime);
            $cronLog->duration = $diffInSeconds;
            $cronLog->save();
        }
        if (request()->target == 'all') {
            $notify[] = ['success', 'Cron executed successfully'];
            return back()->withNotify($notify);
        }
        if (request()->alias) {
            $notify[] = ['success', keyToTitle(request()->alias) . ' executed successfully'];
            return back()->withNotify($notify);
        }
    }

    public function getInterest()
    {
        try {
            $now = Carbon::now();
            $general = gs();
            $general->last_cron = $now;
            $general->save();

            $investments = Investment::where('status', Status::RUNNING)  // Status: 2=>Running, 1=>Completed
                ->where('next_return_date', '<=', $now)->with('plan', 'user')->get();

            foreach ($investments as $data) {

                $user = $data->user;
                $plan = $data->plan;

                // Referral-linked return boost: re-checked every payout, not fixed at investment time.
                $rate    = $plan->interest;
                $boosted = false;
                if ($plan->referral_threshold > 0 && $user->activeReferralsCount() >= $plan->referral_threshold) {
                    $rate    = $plan->boost_interest;
                    $boosted = true;
                }

                $interestAmount = $data->interest_type == Status::PERCENT
                    ? ($data->amount * $rate) / 100
                    : $rate;

                $data->interest_amount = $interestAmount;

                $user->balance += $interestAmount;
                $user->save();

                $data->next_return_date  = Carbon::now()->addDay();
                $data->total_paid       += 1;                        //times increment

                if ($data->total_paid >= $data->total_return) {
                    $data->status = Status::COMPLETED;  //1
                }

                $data->save();

                $transaction               = new Transaction();
                $transaction->user_id      = $data->user_id;
                $transaction->amount       = $interestAmount;
                $transaction->charge       = 0;
                $transaction->post_balance = $user->balance;
                $transaction->trx_type     = '+';
                $transaction->remark       = 'interest';
                $transaction->trx          = getTrx();
                $transaction->details      = ($boosted ? 'Boosted interest' : 'Interest') . ' from ' . $plan->name;
                $transaction->wallet_type  = 'main';
                $transaction->save();
            }
        } catch (\Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}

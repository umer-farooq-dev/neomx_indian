<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Lib\SpinService;
use App\Models\SpinReward;
use App\Models\UserSpin;
use Illuminate\Http\Request;

class SpinController extends Controller
{
    public function index()
    {
        $pageTitle      = 'Spin & Win';
        $user           = auth()->user();
        $rewards        = SpinReward::active()->orderBy('id')->get();
        $availableSpins = SpinService::availableSpins($user);
        $history        = UserSpin::where('user_id', $user->id)->where('status', 1)
            ->with('reward')->orderBy('id', 'desc')->paginate(getPaginate());

        return view('Template::user.spin', compact('pageTitle', 'rewards', 'availableSpins', 'history'));
    }

    public function play(Request $request)
    {
        $user   = auth()->user();
        $reward = SpinService::spin($user);

        if (!$reward) {
            return response()->json([
                'status'  => 'error',
                'message' => SpinReward::active()->count() ? 'You have no spins available right now' : 'Spin rewards are not configured yet',
            ]);
        }

        $rewards = SpinReward::active()->orderBy('id')->get();
        $index   = $rewards->search(fn ($r) => $r->id == $reward->id);

        return response()->json([
            'status'          => 'success',
            'index'           => $index,
            'segments'        => $rewards->count(),
            'reward'          => [
                'name'   => $reward->name,
                'amount' => showAmount($reward->amount),
            ],
            'remaining_spins' => SpinService::availableSpins($user),
            'reward_balance'  => showAmount($user->fresh()->reward_balance),
        ]);
    }
}

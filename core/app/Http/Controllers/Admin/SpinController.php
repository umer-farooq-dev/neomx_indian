<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpinReward;
use App\Models\SpinRule;
use Illuminate\Http\Request;

class SpinController extends Controller
{
    // Rewards

    public function rewards()
    {
        $pageTitle = 'Spin Rewards';
        $rewards   = SpinReward::orderBy('id', 'desc')->get();
        return view('admin.spin.rewards', compact('pageTitle', 'rewards'));
    }

    public function rewardStore(Request $request, $id = 0)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'amount' => 'required|numeric|gte:0',
            'weight' => 'required|integer|gt:0',
            'color'  => 'required|string|max:20',
        ]);

        if ($id) {
            $reward       = SpinReward::findOrFail($id);
            $notification = 'Reward updated successfully';
        } else {
            $reward       = new SpinReward();
            $notification = 'Reward added successfully';
        }

        $reward->name   = $request->name;
        $reward->amount = $request->amount;
        $reward->weight = $request->weight;
        $reward->color  = $request->color;
        $reward->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function rewardStatus($id)
    {
        return SpinReward::changeStatus($id);
    }

    public function rewardDelete($id)
    {
        SpinReward::findOrFail($id)->delete();
        $notify[] = ['success', 'Reward removed successfully'];
        return back()->withNotify($notify);
    }

    // Rules

    public function rules()
    {
        $pageTitle = 'Spin Rules';
        $rules     = SpinRule::orderBy('id', 'desc')->get();
        return view('admin.spin.rules', compact('pageTitle', 'rules'));
    }

    public function ruleStore(Request $request, $id = 0)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'trigger_type'   => 'required|in:signup,referral_count',
            'trigger_value'  => 'nullable|integer|gte:0',
            'spins_granted'  => 'required|integer|gt:0',
        ]);

        if ($request->trigger_type == SpinRule::REFERRAL_COUNT && $request->trigger_value <= 0) {
            $notify[] = ['error', 'Referral count rules need a referral count greater than 0'];
            return back()->withNotify($notify);
        }

        if ($id) {
            $rule         = SpinRule::findOrFail($id);
            $notification = 'Rule updated successfully';
        } else {
            $rule         = new SpinRule();
            $notification = 'Rule added successfully';
        }

        $rule->name           = $request->name;
        $rule->trigger_type   = $request->trigger_type;
        $rule->trigger_value  = $request->trigger_type == SpinRule::REFERRAL_COUNT ? $request->trigger_value : 0;
        $rule->spins_granted  = $request->spins_granted;
        $rule->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function ruleStatus($id)
    {
        return SpinRule::changeStatus($id);
    }

    public function ruleDelete($id)
    {
        SpinRule::findOrFail($id)->delete();
        $notify[] = ['success', 'Rule removed successfully'];
        return back()->withNotify($notify);
    }
}

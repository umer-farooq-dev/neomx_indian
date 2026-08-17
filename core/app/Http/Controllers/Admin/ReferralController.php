<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralLevel;
use App\Models\User;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function overview()
    {
        $pageTitle = 'Referral Overview';
        $users = User::searchable(['username', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());

        foreach ($users as $u) {
            $u->total_referrals_count  = $u->referrals()->count();
            $u->active_referrals_count = $u->activeReferralsCount();
            $u->team_size_count        = $u->teamSize();
        }

        return view('admin.referral.overview', compact('pageTitle', 'users'));
    }

    public function levels()
    {
        $pageTitle = 'Referral Levels';
        $levels    = ReferralLevel::orderBy('level')->get();
        return view('admin.referral.levels', compact('pageTitle', 'levels'));
    }

    public function levelStore(Request $request, $id = 0)
    {
        $request->validate([
            'level'   => 'required|integer|gt:0',
            'percent' => 'required|numeric|gt:0|max:100',
        ]);

        if ($id) {
            $level        = ReferralLevel::findOrFail($id);
            $notification = 'Referral level updated successfully';
        } else {
            $exists = ReferralLevel::where('level', $request->level)->exists();
            if ($exists) {
                $notify[] = ['error', 'This level already exists'];
                return back()->withNotify($notify);
            }
            $level        = new ReferralLevel();
            $notification = 'Referral level added successfully';
        }

        $level->level   = $request->level;
        $level->percent = $request->percent;
        $level->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function levelStatus($id)
    {
        return ReferralLevel::changeStatus($id);
    }

    public function levelDelete($id)
    {
        $level = ReferralLevel::findOrFail($id);
        $level->delete();
        $notify[] = ['success', 'Referral level removed successfully'];
        return back()->withNotify($notify);
    }

    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'referral_min_deposit' => 'required|numeric|gte:0',
        ]);

        $general                        = gs();
        $general->referral_min_deposit  = $request->referral_min_deposit;
        $general->save();

        $notify[] = ['success', 'Referral settings updated successfully'];
        return back()->withNotify($notify);
    }
}

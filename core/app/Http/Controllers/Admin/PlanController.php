<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{

    function index()
    {
        $pageTitle = "All Plans";
        $plans     = Plan::searchable(['name'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.plan.index', compact('plans', 'pageTitle'));
    }

    function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'min_amount'         => 'required|numeric|gt:0',
            'max_amount'         => 'required|numeric|gt:min_amount',
            'total_return'       => 'required|integer|gt:0',
            'interest_type'      => 'required|in:1,2',
            'interest'           => 'required|numeric|gt:0',
            'referral_threshold' => 'nullable|integer|gte:0',
            'boost_interest'     => 'nullable|numeric|gte:0',
        ]);
        if ($id) {
            $plan         = Plan::findOrFail($id);
            $notification = 'Plan updated successfully';
        } else {
            $plan         = new Plan();
            $notification = 'Plan added successfully';
        }
        $plan->name               = $request->name;
        $plan->min_amount         = $request->min_amount;
        $plan->max_amount         = $request->max_amount;
        $plan->total_return       = $request->total_return;
        $plan->interest_type      = $request->interest_type;
        $plan->interest           = $request->interest;
        $plan->referral_threshold = $request->referral_threshold ?: 0;
        $plan->boost_interest     = $request->referral_threshold ? $request->boost_interest : 0;
        $plan->save();
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Plan::changeStatus($id);
    }
}

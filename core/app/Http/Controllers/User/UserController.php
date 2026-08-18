<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\DeviceToken;
use App\Models\Form;
use App\Models\Investment;
use App\Models\NotificationLog;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle     = 'Dashboard';
        $user          = auth()->user();
        $totalDeposit  = Deposit::where('user_id', $user->id)->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
        $totalWithdraw = Withdrawal::where('user_id', $user->id)->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
        $latestTrx     = Transaction::where('user_id', $user->id)->latest()->limit(10)->get();
        $totalInvest   = Investment::where('user_id', $user->id)->sum('amount');
        $plans         = Plan::where('status', 1)->get();
        $teamSize      = $user->teamSize();
        $availableSpins = \App\Lib\SpinService::availableSpins($user);

        return view('Template::user.dashboard', compact('pageTitle', 'user', 'totalDeposit', 'totalWithdraw', 'latestTrx', 'totalInvest', 'plans', 'teamSize', 'availableSpins'));
    }
    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Security';
        return view('Template::user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = Status::ENABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = Status::DISABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function notifications()
    {
        $pageTitle     = 'Notifications';
        $notifications = NotificationLog::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate());

        NotificationLog::where('user_id', auth()->id())->where('user_read', Status::NO)->update(['user_read' => Status::YES]);

        return view('Template::user.notifications', compact('pageTitle', 'notifications'));
    }

    public function transactions()
    {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->whereNotNull('remark')->get('remark');
        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == Status::KYC_PENDING) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == Status::KYC_VERIFIED) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form = Form::where('act', 'kyc')->first();
        return view('Template::user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user = auth()->user();
        $pageTitle = 'KYC Data';
        return view('Template::user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act', 'kyc')->firstOrFail();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $user = auth()->user();
        foreach (@$user->kyc_data ?? [] as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $userData = $formProcessor->processFormData($request, $formData);
        $user->kyc_data = $userData;
        $user->kyc_rejection_reason = null;
        $user->kv = Status::KYC_PENDING;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function userData()
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        if ($user->provider === 'mobile_otp') {
            $pageTitle = 'Complete Your Profile';
            return view('Template::user.user_data_otp', compact('pageTitle', 'user'));
        }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::user.user_data', compact('pageTitle', 'user', 'countries', 'mobileCode'));
    }

    public function userDataSubmit(Request $request)
    {

        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        if ($user->provider === 'mobile_otp') {
            return $this->otpUserDataSubmit($request, $user);
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:users|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
        ]);


        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $user->country_code = $request->country_code;
        $user->mobile       = $request->mobile;
        $user->username     = $request->username;


        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->country_name = @$request->country;
        $user->dial_code = $request->mobile_code;

        $user->profile_complete = Status::YES;
        $user->save();

        return to_route('user.home');
    }

    protected function otpUserDataSubmit(Request $request, $user)
    {
        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'fullname' => 'required|string|max:80',
            'email'    => 'required|string|email|unique:users,email,' . $user->id,
            'password' => ['required', 'confirmed', $passwordValidation],
            'dob'      => 'required|date|before:' . now()->subYears(18)->toDateString(),
            'referBy'  => 'nullable|string|exists:users,username',
        ], [
            'dob.before'     => 'You must be at least 18 years old to register',
            'referBy.exists' => 'This referral code does not belong to any member',
        ]);

        $fullname = trim(preg_replace('/\s+/', ' ', $request->fullname));
        $parts    = explode(' ', $fullname, 2);

        // a referrer captured at OTP time (from a referral link) is never overwritten
        if (!$user->ref_by && $request->referBy) {
            $referUser = User::where('username', $request->referBy)->first();
            if ($referUser && $referUser->id != $user->id) {
                $user->ref_by = $referUser->id;
            }
        }

        $user->firstname = $parts[0];
        $user->lastname  = $parts[1] ?? '';
        $user->email     = strtolower($request->email);
        $user->password  = Hash::make($request->password);
        $user->dob       = $request->dob;
        $user->ev        = gs('ev') ? Status::NO : Status::YES;
        $user->profile_complete = Status::YES;
        $user->save();

        $notify[] = ['success', 'Profile completed successfully'];
        return to_route('user.home')->withNotify($notify);
    }


    public function addDeviceToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function referrals(Request $request)
    {
        $user      = auth()->user();
        $pageTitle = 'My Referrals';

        $totalReferrals  = $user->referrals()->count();
        $activeReferrals = $user->activeReferralsCount();
        $teamSize        = $user->teamSize();
        $referralEarned  = $user->totalReferralEarnings();

        $tree = $user->downlineTree();

        $level = (int) ($request->level ?? 1);
        if ($level < 1) {
            $level = 1;
        }

        $levelUserIds = $tree->where('lvl', $level)->pluck('id');
        $referrals    = User::whereIn('id', $levelUserIds)->with('deposits')->paginate(getPaginate());

        $levelCounts = $tree->groupBy('lvl')->map->count();

        return view('Template::user.referrals', compact(
            'pageTitle', 'referrals', 'totalReferrals', 'activeReferrals',
            'teamSize', 'referralEarned', 'level', 'levelCounts'
        ));
    }


    public function plans()
    {
        $pageTitle = "All Plans";
        $plans     = Plan::where('status', Status::ENABLE)->orderBy("min_amount","ASC")->paginate(getPaginate());
        return view('Template::user.plans', compact('pageTitle', 'plans', 'pageTitle'));
    }

    public function investment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'id'     => 'required|integer',
        ]);

        $plan = Plan::where('id', $request->id)->where('status', Status::ENABLE)->firstOrFail();

        if ($plan->min_amount > $request->amount || $plan->max_amount < $request->amount) {
            $notify[] = ['error', 'Please follow the investment limit'];
            return back()->withNotify($notify);
        }

        $user = auth()->user();
        if ($user->balance < $request->amount) {
            $notify[] = ['error', 'Sorry, You have not sufficient balance'];
            return to_route('user.deposit.index')->withNotify($notify);
        }

        $interest   = 0;
        $nextReturn = Carbon::now()->addDay(1);

        if ($plan->interest_type == Status::FIXED) {
            $interest = $plan->interest;
        } else {
            $interest = ($request->amount * $plan->interest) / 100;
        }

        $user->balance -= $request->amount;
        $user->save();


        $newInvest                   = new Investment();
        $newInvest->trx              = getTrx();
        $newInvest->plan_id          = $plan->id;
        $newInvest->user_id          = $user->id;
        $newInvest->amount           = $request->amount;
        $newInvest->interest_type    = $plan->interest_type;
        $newInvest->interest_amount  = $interest;
        $newInvest->total_return     = $plan->total_return;
        $newInvest->next_return_date = $nextReturn;
        $newInvest->status           = Status::RUNNING;
        $newInvest->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $request->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->remark       = 'invest';
        $transaction->details      = 'Invest on ' . $plan->name;
        $transaction->trx          = $newInvest->trx;
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New Investment In ' . $plan->name . ' from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.users.investment', $user->id);
        $adminNotification->save();

        $general = gs();

        notify($user, 'INVESTMENT', [
            'currency'     => $general->cur_text,
            'trx'          => $transaction->trx,
            'plan'         => $plan->name,
            'amount'       => showAmount($request->amount, currencyFormat: false),
            'details'      => $transaction->details,
            'post_balance' => $user->balance,
            'interest'     => $interest,
            'total_return' => $newInvest->total_return
        ]);

        $notify[] = ['success', 'Invested successfully'];
        return redirect()->route('user.investment.log')->withNotify($notify);
    }

    public function investmentLog()
    {
        $pageTitle = 'Investments';
        $user      = auth()->user();
        $activeReferrals = $user->activeReferralsCount();
        $investments = Investment::where('user_id', auth()->id())->with('plan')->searchable(['trx'])->filter(['interest_type', 'status'])->orderBy('id', 'desc')->paginate(getPaginate());

        return view('Template::user.investment_log', compact('pageTitle', 'investments', 'activeReferrals'));
    }
}

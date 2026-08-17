<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class MobileOtpController extends Controller
{
    protected function hasValidRecentCode($user, $minutes = 2)
    {
        if (!$user->ver_code_send_at) {
            return false;
        }
        return Carbon::parse($user->ver_code_send_at)->addMinutes($minutes) >= Carbon::now();
    }

    protected function generateUsername()
    {
        do {
            $username = 'user' . getNumber(6);
        } while (User::where('username', $username)->exists());
        return $username;
    }

    protected function generatePlaceholderEmail($dialCode, $mobile)
    {
        do {
            $email = strtolower('m' . $dialCode . $mobile . random_int(10, 99)) . '@mobileuser.otp';
        } while (User::where('email', $email)->exists());
        return $email;
    }

    public function sendOtp(Request $request)
    {
        $countryData  = (array) json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $dialCodes    = implode(',', array_column($countryData, 'dial_code'));

        $request->validate([
            'country_code' => 'required|in:' . $countryCodes,
            'dial_code'    => 'required|in:' . $dialCodes,
            'mobile'       => 'required|regex:/^([0-9]*)$/',
        ]);

        $user = User::where('mobile', $request->mobile)->where('dial_code', $request->dial_code)->first();
        $isNewUser = false;

        if (!$user) {
            if (!gs('registration')) {
                return response()->json(['status' => 'error', 'message' => 'New account registration is currently disabled']);
            }

            $isNewUser = true;

            $referBy   = session()->get('reference');
            $referUser = $referBy ? User::where('username', $referBy)->first() : null;

            $user               = new User();
            $user->firstname    = 'Member';
            $user->lastname     = '';
            $user->username     = $this->generateUsername();
            $user->email        = $this->generatePlaceholderEmail($request->dial_code, $request->mobile);
            $user->password     = Hash::make(getTrx(16));
            $user->dial_code    = $request->dial_code;
            $user->country_code = $request->country_code;
            $user->mobile       = $request->mobile;
            $user->ref_by       = $referUser ? $referUser->id : 0;
            $user->provider     = 'mobile_otp';
            $user->status       = Status::USER_ACTIVE;
            $user->profile_complete = Status::NO;
            $user->kv = gs('kv') ? Status::NO : Status::YES;
            $user->ev = Status::YES;
            $user->sv = Status::NO;
            $user->ts = Status::DISABLE;
            $user->tv = Status::ENABLE;
            $user->save();
        }

        if (!$user->status) {
            return response()->json(['status' => 'error', 'message' => 'Your account has been banned. Reason: ' . $user->ban_reason]);
        }

        if ($this->hasValidRecentCode($user)) {
            $targetTime = Carbon::parse($user->ver_code_send_at)->addMinutes(2)->timestamp;
            $delay = $targetTime - time();
            return response()->json(['status' => 'error', 'message' => 'Please try again after ' . $delay . ' seconds']);
        }

        $user->ver_code         = verificationCode(6);
        $user->ver_code_send_at = Carbon::now();
        $user->save();

        notify($user, 'SVER_CODE', [
            'code' => $user->ver_code,
        ], ['sms']);

        if ($isNewUser) {
            $adminNotification            = new AdminNotification();
            $adminNotification->user_id   = $user->id;
            $adminNotification->title     = 'New member registered';
            $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
            $adminNotification->save();
        }

        return response()->json([
            'status'   => 'success',
            'message'  => 'Verification code sent to your mobile number',
            'new_user' => $isNewUser,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'dial_code' => 'required',
            'mobile'    => 'required',
            'code'      => 'required',
        ]);

        $throttleKey = 'otp-verify:' . $request->ip() . ':' . $request->dial_code . $request->mobile;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json(['status' => 'error', 'message' => "Too many attempts. Please try again after $seconds seconds"]);
        }

        $user = User::where('mobile', $request->mobile)->where('dial_code', $request->dial_code)->first();

        if (!$user || !$user->ver_code || $user->ver_code != $request->code) {
            RateLimiter::hit($throttleKey, 600);
            return response()->json(['status' => 'error', 'message' => 'Verification code didn\'t match']);
        }

        RateLimiter::clear($throttleKey);

        if (!$user->status) {
            return response()->json(['status' => 'error', 'message' => 'Your account has been banned. Reason: ' . $user->ban_reason]);
        }

        $user->sv               = Status::VERIFIED;
        $user->ver_code         = null;
        $user->ver_code_send_at = null;
        $user->save();

        Auth::login($user);

        $ip        = getRealIP();
        $exist     = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        if ($exist) {
            $userLogin->longitude    = $exist->longitude;
            $userLogin->latitude     = $exist->latitude;
            $userLogin->city         = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country      = $exist->country;
        } else {
            $info                    = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude    = @implode(',', $info['long']);
            $userLogin->latitude     = @implode(',', $info['lat']);
            $userLogin->city         = @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country      = @implode(',', $info['country']);
        }

        $userAgent          = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;
        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os      = @$userAgent['os_platform'];
        $userLogin->save();

        return response()->json([
            'status'   => 'success',
            'message'  => 'Login successful',
            'redirect' => route('user.home'),
        ]);
    }
}

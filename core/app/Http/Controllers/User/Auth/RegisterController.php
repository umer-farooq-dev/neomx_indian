<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\Intended;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    use RegistersUsers;

    public function __construct()
    {
        parent::__construct();
    }

    public function showRegistrationForm()
    {

        \Session::flash('modalType', '#registerModal');
        \Session::flash('modal', '#registerModal');
        Intended::identifyRoute();
        return to_route('home');
    }


    protected function validator(array $data)
    {

        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $countryData  = (array) json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $agree = 'nullable';
        if (gs('agree')) {
            // one box per published policy page, so every consent is explicit.
            // "required" matters: without it an absent field skips the rules entirely.
            $policyCount = getContent('policy_pages.element', false, null, true)->count();
            $agree = 'required|array|size:' . $policyCount;
        }
        \Session::flash('modal', '#registerModal');

        $validate = Validator::make($data, [
            'fullname'     => 'required|string|max:80',
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', @$data['mobile_code'])],
            'email'        => 'required|string|email|unique:users',
            'password'     => ['required', 'confirmed', $passwordValidation],
            'referBy'      => 'nullable|string|exists:users,username',
            'captcha'      => 'sometimes|required',
            'agree'        => $agree,
        ], [
            'fullname.required' => 'The full name field is required',
            'referBy.exists'    => 'This referral code does not belong to any member',
            'agree.size'        => 'You must accept all the policies to continue',
        ]);

        return $validate;
    }

    public function register(Request $request)
    {

        if (!gs('registration')){
            $notify[]=['error', 'Registration is currently disabled'];
            return back()->withNotify($notify);
        }

        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }



    protected function generateUsername()
    {
        do {
            $username = 'user' . getNumber(6);
        } while (User::where('username', $username)->exists());
        return $username;
    }

    protected function create(array $data)
    {
        // a typed referral code wins over one carried in from a referral link
        $referBy   = $data['referBy'] ?? session()->get('reference');
        $referUser = $referBy ? User::where('username', $referBy)->first() : null;

        $fullname = trim(preg_replace('/\s+/', ' ', $data['fullname']));
        $parts    = explode(' ', $fullname, 2);

        //User Create
        $user               = new User();
        $user->email        = strtolower($data['email']);
        $user->firstname    = $parts[0];
        $user->lastname     = $parts[1] ?? '';
        $user->username     = $this->generateUsername();
        $user->password     = Hash::make($data['password']);
        $user->mobile       = $data['mobile'];
        $user->dial_code    = $data['mobile_code'];
        $user->country_code = $data['country_code'];
        $user->country_name = $data['country'];
        $user->ref_by       = $referUser ? $referUser->id : 0;
        $user->kv = gs('kv') ? Status::NO : Status::YES;
        $user->ev = gs('ev') ? Status::NO : Status::YES;
        // the number is captured on the form, so it always has to be proven by OTP
        $user->sv = Status::NO;
        $user->ts = Status::DISABLE;
        $user->tv = Status::ENABLE;
        // every detail is collected here, so there is no follow-up profile step
        $user->profile_complete = Status::YES;
        $user->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        $adminNotification->save();


        //Login Log Create
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


        return $user;
    }

    public function checkUser(Request $request){
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = User::where('email',$request->email)->exists();
            $exist['type'] = 'email';
            $exist['field'] = 'Email';
        }
        if ($request->mobile) {
            $exist['data'] = User::where('mobile',$request->mobile)->where('dial_code',$request->mobile_code)->exists();
            $exist['type'] = 'mobile';
            $exist['field'] = 'Mobile';
        }
        if ($request->username) {
            $exist['data'] = User::where('username',$request->username)->exists();
            $exist['type'] = 'username';
            $exist['field'] = 'Username';
        }
        return response($exist);
    }

    public function registered()
    {
        return to_route('user.home');
    }

}

@php
    $info = json_decode(json_encode(getIpInfo()), true);
    $mobileCode = @implode(',', $info['code']);
    $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
    $policyPages = getContent('policy_pages.element', false, null, true);

    // Without this the browser falls back to the first option alphabetically
    // (Afghanistan). Prefer the visitor's own country when the IP lookup knows
    // it, otherwise India, which is where the platform operates.
    $defaultCountryCode = $mobileCode && isset($countries->$mobileCode) ? $mobileCode : 'IN';
    $defaultCountry = $countries->$defaultCountryCode->country ?? null;
@endphp

<!-- Login -->
<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loginModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">@lang('Login your account')</h3>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body">

                @include($activeTemplate . 'partials.social_login')

                <div class="text-center mb-3">
                    <a href="#0" class="text--base otp-modal-trigger" data-bs-toggle="modal" data-bs-target="#otpModal" data-bs-dismiss="modal">
                        <i class="las la-sms"></i> @lang('Login with Mobile OTP')
                    </a>
                </div>
                <div class="auth-devide">
                    <span>@lang('OR')</span>
                </div>

                <form id="loginForm" class="account-form verify-gcaptcha1" action="{{ route('user.login') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label>@lang('Username or Email')</label>
                        <input type="text" name="username" value="{{ old('username') }}" class="form--control" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Password')</label>
                        <input id="password" type="password" class="form--control" name="password" required required>
                    </div>

                    <div class="mt-3">
                        <x-captcha />
                    </div>

                    <div class="form-group d-flex justify-content-between">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                @lang('Remember Me')
                            </label>
                        </div>
                        <a href="#" class="text-white" data-bs-toggle="modal" data-bs-target="#resetModal"
                            data-bs-dismiss="modal">@lang('Forgot Password')?</a>
                    </div>
                    <button type="submit" class="btn btn--base w-100 login-submit-btn">@lang('Login')</button>
                    <p class="text-center mt-3"><span class="text-white">@lang('Don\'t have an account')?
                        </span> <a href="#0" class="text--base" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">
                            @lang('Register')</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Register --}}
<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="registerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">@lang('Create an account')</h3>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <i class="la la-times"></i>
                </button>
            </div>
            <div class="modal-body  @if (!gs('registration')) form-disabled @endif">

                @if (!gs('registration'))
                    <span class="form-disabled-text">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="80"
                            height="80" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"
                            class="">
                            <g>
                                <path
                                    d="M255.999 0c-79.044 0-143.352 64.308-143.352 143.353v70.193c0 4.78 3.879 8.656 8.659 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193c0-42.998 34.981-77.98 77.979-77.98s77.979 34.982 77.979 77.98v70.193c0 4.78 3.88 8.656 8.661 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193C399.352 64.308 335.044 0 255.999 0zM382.04 204.89h-30.748v-61.537c0-52.544-42.748-95.292-95.291-95.292s-95.291 42.748-95.291 95.292v61.537h-30.748v-61.537c0-69.499 56.54-126.04 126.038-126.04 69.499 0 126.04 56.541 126.04 126.04v61.537z"
                                    fill="#ff7149" opacity="1" data-original="#ff7149" class=""></path>
                                <path
                                    d="M410.63 204.89H101.371c-20.505 0-37.188 16.683-37.188 37.188v232.734c0 20.505 16.683 37.188 37.188 37.188H410.63c20.505 0 37.187-16.683 37.187-37.189V242.078c0-20.505-16.682-37.188-37.187-37.188zm19.875 269.921c0 10.96-8.916 19.876-19.875 19.876H101.371c-10.96 0-19.876-8.916-19.876-19.876V242.078c0-10.96 8.916-19.876 19.876-19.876H410.63c10.959 0 19.875 8.916 19.875 19.876v232.733z"
                                    fill="#ff7149" opacity="1" data-original="#ff7149" class=""></path>
                                <path
                                    d="M285.11 369.781c10.113-8.521 15.998-20.978 15.998-34.365 0-24.873-20.236-45.109-45.109-45.109-24.874 0-45.11 20.236-45.11 45.109 0 13.387 5.885 25.844 16 34.367l-9.731 46.362a8.66 8.66 0 0 0 8.472 10.436h60.738a8.654 8.654 0 0 0 8.47-10.434l-9.728-46.366zm-14.259-10.961a8.658 8.658 0 0 0-3.824 9.081l8.68 41.366h-39.415l8.682-41.363a8.655 8.655 0 0 0-3.824-9.081c-8.108-5.16-12.948-13.911-12.948-23.406 0-15.327 12.469-27.796 27.797-27.796 15.327 0 27.796 12.469 27.796 27.796.002 9.497-4.838 18.246-12.944 23.403z"
                                    fill="#ff7149" opacity="1" data-original="#ff7149" class=""></path>
                            </g>
                        </svg>
                    </span>
                @endif

                @include($activeTemplate . 'partials.social_login', [($register = 'true')])

                <div class="text-center mb-3">
                    <a href="#0" class="text--base otp-modal-trigger" data-bs-toggle="modal" data-bs-target="#otpModal" data-bs-dismiss="modal">
                        <i class="las la-sms"></i> @lang('Sign up with Mobile OTP')
                    </a>
                </div>
                <div class="auth-devide">
                    <span>@lang('OR')</span>
                </div>

                <form class="account-form registration-form verify-gcaptcha2" action="{{ route('user.register') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>@lang('Full Name')</label>
                                <input type="text" class="form--control" name="fullname" value="{{ old('fullname') }}" required>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('Mobile Number')</label>
                                <div class="input-group">
                                    <span class="input-group-text reg-mobile-code">+</span>
                                    <input type="hidden" name="mobile_code" value="{{ old('mobile_code') }}">
                                    <input type="hidden" name="country_code" value="{{ old('country_code') }}">
                                    <input type="number" name="mobile" value="{{ old('mobile') }}" class="form--control checkUser" required>
                                </div>
                                <small class="text--danger mobileExist"></small>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group select2-parent">
                                <label>@lang('Country')</label>
                                <select name="country" class="form--control reg-country" required>
                                    @foreach ($countries as $key => $country)
                                        <option data-mobile_code="{{ $country->dial_code }}" data-code="{{ $key }}"
                                            value="{{ $country->country }}"
                                            @selected(old('country', $defaultCountry) == $country->country)>
                                            {{ __($country->country) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>@lang('E-Mail Address')</label>
                                <input id="email" type="email" class="form--control checkUser" name="email" value="{{ old('email') }}"
                                    required>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('Password')</label>

                                <input type="password" class="form-control form--control @if (gs('secure_password')) secure-password @endif"
                                    name="password" required>


                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('Confirm Password')</label>
                                <input id="password-confirm" type="password" class="form--control" name="password_confirmation" required
                                    autocomplete="new-password">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>@lang('Referral Code') <span class="text-white-50">(@lang('optional'))</span></label>
                                <input type="text" name="referBy" id="referenceBy" class="form--control"
                                    value="{{ old('referBy', session()->get('reference')) }}"
                                    placeholder="@lang('Enter referral code')">
                            </div>
                        </div>


                        <x-captcha />


                        @if (gs('agree'))
                            @php
                                // the two consents are recorded separately, so each policy page
                                // the admin has published gets its own checkbox
                                $policyList = $policyPages->values();
                            @endphp
                            <div class="col-lg-12">
                                @foreach ($policyList as $i => $policy)
                                    <div class="form-group mb-2">
                                        <input type="checkbox" id="agree{{ $i }}" name="agree[]"
                                            value="{{ $policy->slug }}" @checked(is_array(old('agree')) && in_array($policy->slug, old('agree'))) required>
                                        <label for="agree{{ $i }}">@lang('I agree with the')</label>
                                        <a href="{{ route('policy.pages', $policy->slug) }}" target="_blank">{{ __($policy->data_values->title) }}</a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                    <button type="submit" id="recaptcha" class="btn btn--base w-100">@lang('Register')</button>
                    <p class="text-center mt-3"><span class="text-white"> @lang('Have an account')? </span> <a href="#0" class="text--base"
                            data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">@lang('Login')</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Exist-User-Credential --}}
<div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <i class="la la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <h6 class="text-center">@lang('You already have an account please Sign in ')</h6>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn--danger text-white" data-bs-dismiss="modal">@lang('Close')</button>

                <button type="button" class="btn btn--base ex-email" data-bs-dismiss="modal" data-bs-toggle="modal"
                    data-bs-target="#loginModal">@lang('Login')</button>
            </div>
        </div>
    </div>
</div>

{{-- Password Reset --}}
<div class="modal fade" id="resetModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loginModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">@lang('Reset Password')</h3>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <i class="la la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="account-form verify-gcaptcha3" method="POST" action="{{ route('user.password.email') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">@lang('Email or Username')</label>
                        <input type="text" class="form-control form--control" name="value" value="{{ old('value') }}" required
                            autofocus="off">
                    </div>

                    <x-captcha />


                    <button type="submit" class="btn btn--base w-100">@lang('Send Password Code')</button>
                    <p class="text-center mt-3"><span class="text-white">@lang('Have been remembering')?</span> <a href="#0" class="text--base"
                            data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">@lang('Login')</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Mobile OTP Login / Register (Firebase Phone Auth) --}}
<div class="modal fade" id="otpModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="otpModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">@lang('Continue with Mobile')</h3>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body">

                {{-- Step 1: Mobile number with flag/dial-code picker --}}
                <form id="otpSendForm" class="account-form">
                    <div class="form-group">
                        <label>@lang('Mobile Number')</label>
                        <div class="input-group otp-phone-group">
                            <div class="dropdown">
                                <button class="btn otp-country-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <span class="otp-flag">🌐</span> <span class="otp-dial">+</span>
                                </button>
                                <ul class="dropdown-menu otp-country-menu">
                                    <li class="px-2 pb-1">
                                        <input type="text" class="form--control form-control-sm otp-country-search"
                                            placeholder="@lang('Search country')">
                                    </li>
                                    @foreach ($countries as $key => $country)
                                        <li>
                                            <a class="dropdown-item otp-country-option" href="#0" data-code="{{ $key }}"
                                                data-dial="{{ $country->dial_code }}" data-name="{{ __($country->country) }}">
                                                <span class="otp-opt-flag"></span> +{{ $country->dial_code }} {{ __($country->country) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <input type="hidden" name="dial_code">
                            <input type="hidden" name="country_code">
                            <input type="tel" name="mobile" class="form--control" placeholder="@lang('Mobile number')" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn--base w-100 otp-send-btn">
                        <span class="otp-send-label">@lang('Send OTP')</span>
                        <span class="otp-send-spinner spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>

                    {{-- Firebase anchors its invisible reCAPTCHA here --}}
                    <div id="otpRecaptcha"></div>
                </form>

                {{-- Step 2: Enter Code --}}
                <form id="otpVerifyForm" class="account-form d-none">
                    <p class="text-white text-center mb-1">@lang('Enter the 6-digit code sent to')</p>
                    <p class="text-center otp-target text--base fw-bold mb-3"></p>

                    <div class="otp-digit-group mb-3">
                        <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-idx="0" autocomplete="one-time-code">
                        <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-idx="1">
                        <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-idx="2">
                        <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-idx="3">
                        <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-idx="4">
                        <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-idx="5">
                    </div>
                    <input type="hidden" name="code">

                    <button type="submit" class="btn btn--base w-100 otp-verify-btn">
                        <span class="otp-verify-label">@lang('Verify & Continue')</span>
                        <span class="otp-verify-spinner spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                    <p class="text-center mt-3">
                        <a href="#0" class="text--base otp-resend-btn">@lang('Resend Code')</a>
                        <span class="otp-resend-timer text-white"></span>
                        <span class="mx-2 text-white">|</span>
                        <a href="#0" class="text--base otp-change-number">@lang('Change Number')</a>
                    </p>
                </form>

            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        (function($) {
            "use strict";

            let otpMobileCode = @json($mobileCode);
            let otpResendTimer = null;

            function flagEmoji(countryCode) {
                if (!countryCode || countryCode.length !== 2) return '🌐';
                return countryCode.toUpperCase().replace(/./g, function(c) {
                    return String.fromCodePoint(c.charCodeAt(0) + 127397);
                });
            }

            function selectOtpCountry($option) {
                let code = $option.data('code');
                let dial = $option.data('dial');
                $('#otpModal input[name=dial_code]').val(dial);
                $('#otpModal input[name=country_code]').val(code);
                $('#otpModal .otp-flag').text(flagEmoji(code));
                $('#otpModal .otp-dial').text('+' + dial);
            }

            function readOtpCode() {
                let code = '';
                $('.otp-digit').each(function() {
                    code += $(this).val();
                });
                return code;
            }

            function startResendCooldown(seconds) {
                $('.otp-resend-btn').addClass('d-none');
                $('.otp-resend-timer').removeClass('d-none').text('(' + seconds + 's)');
                clearInterval(otpResendTimer);
                otpResendTimer = setInterval(function() {
                    seconds--;
                    if (seconds <= 0) {
                        clearInterval(otpResendTimer);
                        $('.otp-resend-timer').text('');
                        $('.otp-resend-btn').removeClass('d-none');
                    } else {
                        $('.otp-resend-timer').text('(' + seconds + 's)');
                    }
                }, 1000);
            }

            // Firebase Phone Auth sends and checks the code itself, from the
            // browser. We prefer it when configured because Google is registered
            // on India's DLT platform, which a direct SMS gateway is not.
            const otpFirebaseConfig = @json(gs('firebase_config'));
            const useFirebaseOtp = !!(otpFirebaseConfig && otpFirebaseConfig.apiKey &&
                otpFirebaseConfig.projectId && typeof firebase !== 'undefined' && firebase.auth);

            let otpConfirmation = null;
            let otpRecaptcha = null;

            function otpFirebaseAuth() {
                if (!firebase.apps.length) {
                    firebase.initializeApp(otpFirebaseConfig);
                }
                if (!otpRecaptcha) {
                    otpRecaptcha = new firebase.auth.RecaptchaVerifier('otpRecaptcha', { size: 'invisible' });
                }
                return firebase.auth();
            }

            function otpSendDone() {
                $('.otp-send-btn').prop('disabled', false);
                $('.otp-send-label').removeClass('d-none');
                $('.otp-send-spinner').addClass('d-none');
            }

            function otpShowCodeStep(dialCode, mobile) {
                $('#otpSendForm').addClass('d-none');
                $('#otpVerifyForm').removeClass('d-none');
                $('.otp-target').text('+' + dialCode + ' ' + mobile);
                $('.otp-digit').val('').first().trigger('focus');
                startResendCooldown(120);
            }

            function sendOtpViaFirebase(dialCode, countryCode, mobile) {
                let auth;
                try {
                    auth = otpFirebaseAuth();
                } catch (err) {
                    otpSendDone();
                    notify('error', 'Could not start phone verification: ' + err.message);
                    return;
                }

                auth.signInWithPhoneNumber('+' + dialCode + mobile, otpRecaptcha)
                    .then(function(confirmation) {
                        otpConfirmation = confirmation;
                        otpSendDone();
                        otpShowCodeStep(dialCode, mobile);
                        notify('success', 'Verification code sent to your mobile number');
                    })
                    .catch(function(err) {
                        otpSendDone();
                        // a spent reCAPTCHA cannot be reused, so drop it and let
                        // the next attempt build a fresh one
                        if (otpRecaptcha) {
                            try { otpRecaptcha.clear(); } catch (e) {}
                            otpRecaptcha = null;
                            $('#otpRecaptcha').empty();
                        }
                        notify('error', err.message || 'Could not send the verification code');
                    });
            }

            function sendOtp() {
                let dialCode = $('#otpSendForm [name=dial_code]').val();
                let countryCode = $('#otpSendForm [name=country_code]').val();
                let mobile = $('#otpSendForm [name=mobile]').val();

                if (!dialCode || !mobile) {
                    notify('error', 'Please select your country and enter a mobile number');
                    return;
                }

                let $btn = $('.otp-send-btn');
                $btn.prop('disabled', true);
                $('.otp-send-label').addClass('d-none');
                $('.otp-send-spinner').removeClass('d-none');

                if (useFirebaseOtp) {
                    sendOtpViaFirebase(dialCode, countryCode, mobile);
                    return;
                }

                $.post('{{ route('user.otp.send') }}', {
                    dial_code: dialCode,
                    country_code: countryCode,
                    mobile: mobile,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    $btn.prop('disabled', false);
                    $('.otp-send-label').removeClass('d-none');
                    $('.otp-send-spinner').addClass('d-none');

                    if (response.status === 'success') {
                        $('#otpSendForm').addClass('d-none');
                        $('#otpVerifyForm').removeClass('d-none');
                        $('.otp-target').text('+' + dialCode + ' ' + mobile);
                        $('.otp-digit').val('').first().trigger('focus');
                        startResendCooldown(120);
                        notify('success', response.message);
                    } else {
                        notify('error', response.message);
                    }
                }).fail(function(xhr) {
                    $btn.prop('disabled', false);
                    $('.otp-send-label').removeClass('d-none');
                    $('.otp-send-spinner').addClass('d-none');
                    let msg = 'Something went wrong';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors)[0][0];
                    }
                    notify('error', msg);
                });
            }

            $('#otpModal .otp-opt-flag').each(function() {
                $(this).text(flagEmoji($(this).closest('.otp-country-option').data('code')));
            });

            $('#otpModal').on('shown.bs.modal', function() {
                let $default = $('#otpModal .otp-country-option[data-code="' + otpMobileCode + '"]').first();
                if (!$default.length) {
                    $default = $('#otpModal .otp-country-option[data-code="IN"]').first();
                }
                if (!$default.length) {
                    $default = $('#otpModal .otp-country-option').first();
                }
                selectOtpCountry($default);
            });

            $('#otpModal').on('hidden.bs.modal', function() {
                clearInterval(otpResendTimer);
                $('#otpVerifyForm').addClass('d-none');
                $('#otpSendForm').removeClass('d-none');
                $('#otpVerifyForm')[0].reset();
                $('.otp-digit').val('');
            });

            $('#otpModal').on('click', '.otp-country-option', function(e) {
                e.preventDefault();
                selectOtpCountry($(this));
            });

            $('#otpModal').on('keyup', '.otp-country-search', function() {
                let term = $(this).val().toLowerCase();
                $('#otpModal .otp-country-option').each(function() {
                    let name = $(this).data('name').toString().toLowerCase();
                    $(this).parent().toggle(name.indexOf(term) !== -1);
                });
            });

            // OTP digit boxes: auto-advance, backspace, and paste support
            $('#otpModal').on('input', '.otp-digit', function() {
                let $this = $(this);
                $this.val($this.val().replace(/[^0-9]/g, ''));
                if ($this.val().length === 1) {
                    $this.next('.otp-digit').trigger('focus');
                }
            });

            $('#otpModal').on('keydown', '.otp-digit', function(e) {
                if (e.key === 'Backspace' && !$(this).val()) {
                    $(this).prev('.otp-digit').trigger('focus');
                }
            });

            $('#otpModal').on('paste', '.otp-digit', function(e) {
                let pasted = (e.originalEvent.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                if (!pasted) return;
                e.preventDefault();
                $('.otp-digit').each(function(i) {
                    $(this).val(pasted[i] || '');
                });
                $('.otp-digit').filter(function() {
                    return !$(this).val();
                }).first().trigger('focus');
            });

            $('#otpSendForm').on('submit', function(e) {
                e.preventDefault();
                sendOtp();
            });

            function otpErrorText(err) {
                if (!err) return 'Verification failed';

                // a Firebase error carries its own message
                if (err.message) return err.message;

                // a rejected jQuery request carries the server's response instead
                let body = err.responseJSON;
                if (body) {
                    if (body.message) return body.message;
                    if (body.errors) return Object.values(body.errors)[0][0];
                }
                if (err.status === 419) return 'Your session expired. Please refresh the page and try again.';
                if (err.status) return 'Server error (' + err.status + '). Please try again.';

                return 'Verification failed';
            }

            function otpVerifyDone() {
                $('.otp-verify-btn').prop('disabled', false);
                $('.otp-verify-label').removeClass('d-none');
                $('.otp-verify-spinner').addClass('d-none');
            }

            // Firebase checks the code, then hands us a signed token naming the
            // proven number. The server re-verifies that token before trusting it.
            function verifyOtpViaFirebase(code) {
                if (!otpConfirmation) {
                    otpVerifyDone();
                    notify('error', 'Please request a new code');
                    return;
                }

                otpConfirmation.confirm(code)
                    .then(function(result) {
                        return result.user.getIdToken();
                    })
                    .then(function(idToken) {
                        return $.post('{{ route('user.otp.firebase.verify') }}', {
                            id_token: idToken,
                            dial_code: $('#otpSendForm [name=dial_code]').val(),
                            country_code: $('#otpSendForm [name=country_code]').val(),
                            mobile: $('#otpSendForm [name=mobile]').val(),
                            _token: '{{ csrf_token() }}'
                        });
                    })
                    .then(function(response) {
                        if (response.status === 'success') {
                            notify('success', response.message);
                            window.location.href = response.redirect;
                        } else {
                            otpVerifyDone();
                            notify('error', response.message);
                            $('.otp-digit').val('').first().trigger('focus');
                        }
                    })
                    .catch(function(err) {
                        otpVerifyDone();
                        notify('error', otpErrorText(err));
                        $('.otp-digit').val('').first().trigger('focus');
                    });
            }

            $('#otpVerifyForm').on('submit', function(e) {
                e.preventDefault();

                let code = readOtpCode();
                if (code.length !== 6) {
                    notify('error', 'Please enter the full 6-digit code');
                    return;
                }

                let $btn = $('.otp-verify-btn');
                $btn.prop('disabled', true);
                $('.otp-verify-label').addClass('d-none');
                $('.otp-verify-spinner').removeClass('d-none');

                if (useFirebaseOtp) {
                    verifyOtpViaFirebase(code);
                    return;
                }

                $.post('{{ route('user.otp.verify') }}', {
                    dial_code: $('#otpSendForm [name=dial_code]').val(),
                    country_code: $('#otpSendForm [name=country_code]').val(),
                    mobile: $('#otpSendForm [name=mobile]').val(),
                    code: code,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if (response.status === 'success') {
                        notify('success', response.message);
                        window.location.href = response.redirect;
                    } else {
                        $btn.prop('disabled', false);
                        $('.otp-verify-label').removeClass('d-none');
                        $('.otp-verify-spinner').addClass('d-none');
                        notify('error', response.message);
                        $('.otp-digit').val('').first().trigger('focus');
                    }
                }).fail(function() {
                    $btn.prop('disabled', false);
                    $('.otp-verify-label').removeClass('d-none');
                    $('.otp-verify-spinner').addClass('d-none');
                    notify('error', 'Something went wrong');
                });
            });

            $('.otp-resend-btn').on('click', function(e) {
                e.preventDefault();
                sendOtp();
            });

            $('.otp-change-number').on('click', function(e) {
                e.preventDefault();
                clearInterval(otpResendTimer);
                $('#otpVerifyForm')[0].reset();
                $('.otp-digit').val('');
                $('#otpVerifyForm').addClass('d-none');
                $('#otpSendForm').removeClass('d-none');
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .otp-phone-group {
            display: flex;
        }

        .otp-country-btn {
            border: 1px solid rgb(229 229 229 / 20%);
            background: transparent;
            color: #fff;
            white-space: nowrap;
        }

        .otp-country-menu {
            max-height: 280px;
            overflow-y: auto;
            min-width: 260px;
        }

        .otp-digit-group {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .otp-digit {
            width: 48px;
            height: 56px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            border-radius: 8px;
            border: 1px solid rgb(229 229 229 / 20%);
            background: transparent;
            color: #fff;
        }

        .otp-digit:focus {
            outline: none;
            border-color: #ACE600;
            box-shadow: 0 0 0 2px rgba(172, 230, 0, 0.25);
        }
    </style>
@endpush

@push('style')
    <style>
        .form-disabled {
            overflow: hidden;
            position: relative;
        }

        .form-disabled-text svg path {
            fill: #ACE600;
        }


        .form-disabled::after {
            content: "";
            position: absolute;
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            top: 0;
            left: 0;
            backdrop-filter: blur(3px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            z-index: 99;
        }

        .form-disabled .account-logo-area {
            z-index: 999;
        }

        .form-disabled-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 991;
            font-size: 24px;
            height: auto;
            width: 100%;
            text-align: center;
            color: hsl(var(--dark-600));
            font-weight: 800;
            line-height: 1.2;
        }
    </style>
@endpush

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif


@push('script')
    <script>
        (function($) {
            "use strict";
            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                var data = {
                    email: value,
                    _token: token
                }

                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $('#existModalCenter').modal('show');
                    }
                });
            });


            $('.ex-email').on('click', function() {
                $('#existModalCenter').modal('hide');
            })


            // keep the hidden dial/country codes in step with the country picker
            function syncRegCountry() {
                let $opt = $('.reg-country option:selected');
                $('input[name=mobile_code]').val($opt.data('mobile_code'));
                $('input[name=country_code]').val($opt.data('code'));
                $('.reg-mobile-code').text('+' + $opt.data('mobile_code'));
            }

            if ($('.reg-country').length) {
                // the correct option is already marked server-side, so this just
                // copies its dial code into the hidden fields on first paint
                syncRegCountry();
                $('.reg-country').on('change', syncRegCountry);
            }

            let anyError = '{{ @$errors->any() }}';

            let modalType = '{{ Session::get('modalType') }}';

            if (anyError || modalType) {
                let errorModal = '{{ Session::get('modal') }}';
                $(errorModal).modal('show');
            }

            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                let $form = $(this);
                let $btn = $form.find('.login-submit-btn');
                let originalLabel = $btn.text();
                $btn.prop('disabled', true).text('@lang('Please wait...')');

                function resetButton() {
                    $btn.prop('disabled', false).text(originalLabel);
                }

                function resetCaptcha() {
                    if (typeof grecaptcha !== 'undefined' && grecaptcha.reset) {
                        try {
                            grecaptcha.reset();
                        } catch (err) {}
                    }
                }

                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    dataType: 'json'
                }).done(function(response) {
                    if (response.status === 'success') {
                        notify('success', response.message || '@lang('Login successful')');
                        window.location.href = response.redirect;
                    } else {
                        resetButton();
                        resetCaptcha();
                        notify('error', response.message || '@lang('Invalid login credentials')');
                    }
                }).fail(function(xhr) {
                    resetButton();
                    resetCaptcha();
                    let response = xhr.responseJSON;
                    if (response && response.errors) {
                        $.each(response.errors, function(field, messages) {
                            messages.forEach(function(msg) {
                                notify('error', msg);
                            });
                        });
                    } else if (response && response.message) {
                        notify('error', response.message);
                    } else {
                        notify('error', '@lang('Something went wrong. Please try again')');
                    }
                });
            });

            var CaptchaCallback = function() {
                grecaptcha.render('verify-gcaptcha1');
                grecaptcha.render('verify-gcaptcha2');
                grecaptcha.render('verify-gcaptcha3');
            };

        })(jQuery);
    </script>
@endpush

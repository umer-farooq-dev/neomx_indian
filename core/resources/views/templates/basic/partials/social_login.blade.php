@php
    $text = request()->routeIs('user.register') ? 'Register' : 'Login';
@endphp

<div class="social-auth">
    @if (@gs('socialite_credentials')->google->status == Status::ENABLE)
        <div class="auth-inner">
            <a href="{{ route('user.social.login', 'google') }}" class="social-login-btn">
                <span class="google-icon">
                    <img src="{{ asset($activeTemplateTrue . 'images/google.svg') }}" alt="Google">
                </span> <span class="text">@lang("Google")</span>
            </a>
        </div>
    @endif

    @if (@gs('socialite_credentials')->facebook->status == Status::ENABLE)
        <div class="auth-inner">
            <a href="{{ route('user.social.login', 'facebook') }}" class="social-login-btn">
                <span class="facebook-icon">
                    <img src="{{ asset($activeTemplateTrue . 'images/facebook.svg') }}" alt="Facebook">
                </span> <span class="text">@lang("Facebook")</span>
            </a>
        </div>
    @endif

    @if (@gs('socialite_credentials')->linkedin->status == Status::ENABLE)
        <div class="auth-inner">
            <a href="{{ route('user.social.login', 'linkedin') }}" class="social-login-btn">
                <span class="linkedin-icon">
                    <img src="{{ asset($activeTemplateTrue . 'images/linkdin.svg') }}" alt="Linkedin">
                </span> <span class="text">@lang("Linkedin")</span>
            </a>
        </div>
    @endif
</div>

@if (
    @gs('socialite_credentials')->linkedin->status ||
        @gs('socialite_credentials')->facebook->status == Status::ENABLE ||
        @gs('socialite_credentials')->google->status == Status::ENABLE)
    <div class="auth-devide">
        <span>@lang('OR')</span>
    </div>
@endif

@push('style')
    <style>
        .social-login-btn {
            border: 1px solid rgb(229 229 229 / 20%);
            padding: 7px 16px;
            border-radius: 4px;
            width: 100%;
            display: flex;
            justify-content: center;
            color: white;
            font-size: 15px;
            transition: all linear 0.3s;
            gap: 10px;
        }


        .social-login-btn:hover {
            color: white;
            border-color: #ACE600;
        }

        .social-login-btn img {
            width: 20px;
        }

        .social-auth {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .auth-inner {
            flex: 1 1 auto;
        }

        .auth-devide {
            text-align: center;
            margin-block: 24px;
            position: relative;
            z-index: 1;
        }

        .auth-devide::after {
            content: '';
            width: 100%;
            height: 1px;
            background: rgb(229 229 229 / 20%);
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            z-index: -1;
        }

        .auth-devide span {
            padding-inline: 6px;
            background: #20204e;
        }
    </style>
@endpush

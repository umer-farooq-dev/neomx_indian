@php
    $mockMenu = ['Dashboard', 'My Plans', 'Deposit', 'Withdraw', 'Referrals', 'Transactions', 'Profile', 'Logout'];
    $mockIcons = ['las la-home', 'las la-project-diagram', 'las la-wallet', 'la la-hand-holding-usd', 'las la-sitemap', 'las la-exchange-alt', 'las la-user', 'las la-sign-out-alt'];
@endphp

<section class="nx-section" id="dashboard-preview">
    <div class="nx-container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-4" data-reveal>
                <div class="nx-eyebrow">@lang('Dashboard Preview')</div>
                <h2 class="nx-title">@lang('Everything You Need, In One Dashboard')</h2>
                <div class="nx-bullets">
                    <div class="nx-bullet"><i class="las la-check-circle"></i> @lang('Real-time Wallet Balance')</div>
                    <div class="nx-bullet"><i class="las la-check-circle"></i> @lang('Daily Earnings & History')</div>
                    <div class="nx-bullet"><i class="las la-check-circle"></i> @lang('Referral Details & Earnings')</div>
                    <div class="nx-bullet"><i class="las la-check-circle"></i> @lang('Deposit & Withdrawal History')</div>
                    <div class="nx-bullet"><i class="las la-check-circle"></i> @lang('24x7 Account Access')</div>
                </div>
                @auth
                    <a href="{{ route('user.home') }}" class="nx-btn nx-btn--solid mt-3">@lang('Explore Dashboard') <i class="las la-arrow-right"></i></a>
                @else
                    <a href="#0" data-bs-toggle="modal" data-bs-target="#loginModal" class="nx-btn nx-btn--solid mt-3">
                        @lang('Explore Dashboard') <i class="las la-arrow-right"></i>
                    </a>
                @endauth
            </div>

            <div class="col-lg-8" data-reveal>
                <div class="nx-mock">
                    <div class="nx-mock__body">
                        <div class="nx-mock__side">
                            <div class="nx-mock__side-logo">
                                <img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}">
                            </div>
                            @foreach ($mockMenu as $i => $item)
                                <span class="nx-mock__side-item @if ($i === 0) is-active @endif">
                                    <i class="{{ $mockIcons[$i] }}"></i> {{ __($item) }}
                                </span>
                            @endforeach
                        </div>

                        <div class="nx-mock__main">
                            <div class="nx-mock__row">
                                <div class="nx-mock__card nx-mock__card--1">
                                    <span>@lang('Wallet Balance')</span><b>{{ gs('cur_sym') }}0.00</b>
                                </div>
                                <div class="nx-mock__card nx-mock__card--2">
                                    <span>@lang('Total Earnings')</span><b>{{ gs('cur_sym') }}0.00</b>
                                </div>
                                <div class="nx-mock__card nx-mock__card--3">
                                    <span>@lang('Referral Earnings')</span><b>{{ gs('cur_sym') }}0.00</b>
                                </div>
                                <div class="nx-mock__card nx-mock__card--4">
                                    <span>@lang('Total Referrals')</span><b>0</b>
                                </div>
                            </div>
                            <div class="row gy-3">
                                <div class="col-md-7">
                                    <div class="nx-mock__panel">
                                        <h6>@lang('Recent Transactions')</h6>
                                        <div class="nx-mock__line"><span>@lang('Daily Earning')</span><b class="text--base">+</b></div>
                                        <div class="nx-mock__line"><span>@lang('Referral Bonus')</span><b class="text--base">+</b></div>
                                        <div class="nx-mock__line"><span>@lang('Deposit')</span><b>—</b></div>
                                        <div class="nx-mock__line"><span>@lang('Withdrawal')</span><b>—</b></div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="nx-mock__panel">
                                        <h6>@lang('Earnings Overview')</h6>
                                        <div class="nx-mock__chart">
                                            <svg viewBox="0 0 200 90" preserveAspectRatio="none">
                                                <polyline fill="none" stroke="#17e0c3" stroke-width="2"
                                                    points="0,70 30,55 60,60 90,35 120,45 150,20 180,30 200,15" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nx-mock-note">@lang('Preview only — your real numbers appear after you sign in')</div>
                </div>
            </div>
        </div>
    </div>
</section>

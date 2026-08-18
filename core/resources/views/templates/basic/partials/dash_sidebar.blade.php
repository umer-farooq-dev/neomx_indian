@php
    $unreadNotifications = \App\Models\NotificationLog::where('user_id', auth()->id())->where('user_read', 0)->count();
@endphp

<aside class="nxd-side" id="nxdSide">
    <div class="nxd-side__logo">
        <a href="{{ route('user.home') }}"><img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}"></a>
    </div>

    <nav class="nxd-side__nav">
        <a href="{{ route('user.home') }}" class="nxd-link {{ menuActive('user.home') }}">
            <i class="las la-home"></i> @lang('Dashboard')
        </a>

        <a href="{{ route('user.wallet') }}" class="nxd-link {{ menuActive('user.wallet') }}">
            <i class="las la-wallet"></i> @lang('Wallet')
        </a>

        <div class="nxd-side__label">@lang('Earn')</div>

        <div class="nxd-group {{ menuActive(['user.plans', 'user.investment.log']) ? 'open' : '' }}">
            <a href="javascript:void(0)" class="nxd-link {{ menuActive(['user.plans', 'user.investment.log']) }}">
                <i class="las la-project-diagram"></i> @lang('Investment')
                <i class="las la-angle-right nxd-link__caret"></i>
            </a>
            <div class="nxd-group__items">
                <a href="{{ route('user.plans') }}" class="nxd-sublink {{ menuActive('user.plans') }}">@lang('Plans')</a>
                <a href="{{ route('user.investment.log') }}" class="nxd-sublink {{ menuActive('user.investment.log') }}">@lang('Investment Log')</a>
            </div>
        </div>

        <a href="{{ route('user.referrals') }}" class="nxd-link {{ menuActive('user.referrals') }}">
            <i class="las la-sitemap"></i> @lang('Referrals')
        </a>

        <a href="{{ route('user.spin') }}" class="nxd-link {{ menuActive('user.spin') }}">
            <i class="las la-dharmachakra"></i> @lang('Spin & Win')
        </a>

        <div class="nxd-side__label">@lang('Money')</div>

        <div class="nxd-group {{ menuActive('user.deposit.*') ? 'open' : '' }}">
            <a href="javascript:void(0)" class="nxd-link {{ menuActive('user.deposit.*') }}">
                <i class="las la-plus-circle"></i> @lang('Deposit')
                <i class="las la-angle-right nxd-link__caret"></i>
            </a>
            <div class="nxd-group__items">
                <a href="{{ route('user.deposit.index') }}" class="nxd-sublink {{ menuActive('user.deposit.index') }}">@lang('Deposit Money')</a>
                <a href="{{ route('user.deposit.history') }}" class="nxd-sublink {{ menuActive('user.deposit.history') }}">@lang('Deposit History')</a>
            </div>
        </div>

        <div class="nxd-group {{ menuActive('user.withdraw.*') ? 'open' : '' }}">
            <a href="javascript:void(0)" class="nxd-link {{ menuActive('user.withdraw.*') }}">
                <i class="la la-hand-holding-usd"></i> @lang('Withdraw')
                <i class="las la-angle-right nxd-link__caret"></i>
            </a>
            <div class="nxd-group__items">
                <a href="{{ route('user.withdraw') }}" class="nxd-sublink {{ menuActive('user.withdraw') }}">@lang('Withdraw Money')</a>
                <a href="{{ route('user.withdraw.history') }}" class="nxd-sublink {{ menuActive('user.withdraw.history') }}">@lang('Withdraw History')</a>
            </div>
        </div>

        <a href="{{ route('user.transactions') }}" class="nxd-link {{ menuActive('user.transactions') }}">
            <i class="las la-exchange-alt"></i> @lang('Transactions')
        </a>

        <div class="nxd-side__label">@lang('Account')</div>

        <a href="{{ route('user.notifications') }}" class="nxd-link {{ menuActive('user.notifications') }}">
            <i class="las la-bell"></i> @lang('Notifications')
            @if ($unreadNotifications)
                <span class="nxd-link__badge">{{ $unreadNotifications }}</span>
            @endif
        </a>

        <div class="nxd-group {{ menuActive('ticket.*') ? 'open' : '' }}">
            <a href="javascript:void(0)" class="nxd-link {{ menuActive('ticket.*') }}">
                <i class="las la-headset"></i> @lang('Support')
                <i class="las la-angle-right nxd-link__caret"></i>
            </a>
            <div class="nxd-group__items">
                <a href="{{ route('ticket.index') }}" class="nxd-sublink {{ menuActive('ticket.index') }}">@lang('My Support Tickets')</a>
                <a href="{{ route('ticket.open') }}" class="nxd-sublink {{ menuActive('ticket.open') }}">@lang('New Support Ticket')</a>
            </div>
        </div>

        <div class="nxd-group {{ menuActive(['user.profile.setting', 'user.change.password', 'user.twofactor']) ? 'open' : '' }}">
            <a href="javascript:void(0)" class="nxd-link {{ menuActive(['user.profile.setting', 'user.change.password', 'user.twofactor']) }}">
                <i class="las la-user-cog"></i> @lang('Settings')
                <i class="las la-angle-right nxd-link__caret"></i>
            </a>
            <div class="nxd-group__items">
                <a href="{{ route('user.profile.setting') }}" class="nxd-sublink {{ menuActive('user.profile.setting') }}">@lang('Profile')</a>
                <a href="{{ route('user.change.password') }}" class="nxd-sublink {{ menuActive('user.change.password') }}">@lang('Change Password')</a>
                <a href="{{ route('user.twofactor') }}" class="nxd-sublink {{ menuActive('user.twofactor') }}">@lang('2FA Security')</a>
            </div>
        </div>

        <a href="#0" class="nxd-link" data-bs-toggle="modal" data-bs-target="#ConfirmationModal">
            <i class="las la-sign-out-alt"></i> @lang('Logout')
        </a>
    </nav>
</aside>

<div class="nxd-backdrop" id="nxdBackdrop"></div>

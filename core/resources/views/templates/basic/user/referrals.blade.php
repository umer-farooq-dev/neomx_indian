@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @include($activeTemplate . 'partials.referral_link')
                </div>

                <div class="col-lg-3 col-sm-6 mt-3">
                    <div class="dashboard-card h-100">
                        <span>@lang('Total Referrals')</span>
                        <h3 class="number">{{ $totalReferrals }}</h3>
                        <i class="las la-user-friends icon"></i>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mt-3">
                    <div class="dashboard-card h-100">
                        <span>@lang('Active Referrals')</span>
                        <h3 class="number">{{ $activeReferrals }}</h3>
                        <i class="las la-user-check icon"></i>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mt-3">
                    <div class="dashboard-card h-100">
                        <span>@lang('Team Size')</span>
                        <h3 class="number">{{ $teamSize }}</h3>
                        <i class="las la-sitemap icon"></i>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mt-3">
                    <a class="d-block" href="{{ route('user.wallet', ['wallet_type' => 'referral']) }}">
                        <div class="dashboard-card h-100">
                            <span>@lang('Referral Earnings')</span>
                            <h3 class="number">{{ gs('cur_sym') }}{{ showAmount($referralEarned) }}</h3>
                            <i class="las la-hand-holding-usd icon"></i>
                        </div>
                    </a>
                </div>

                <div class="col-lg-12 mt-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h5 class="mb-0">@lang('Your Team')</h5>
                        <ul class="nav referral-level-tabs">
                            @forelse ($levelCounts as $lvl => $count)
                                <li class="nav-item">
                                    <a class="nav-link @if ($lvl == $level) active @endif"
                                        href="{{ route('user.referrals', ['level' => $lvl]) }}">
                                        @lang('Level') {{ $lvl }} <span class="badge bg--info">{{ $count }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="nav-item"><span class="text-muted">@lang('No referrals yet')</span></li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('Username')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Phone')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Total Deposit')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($referrals as $user)
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>
                                            @if ($user->deposits->where('status', 1)->count())
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Inactive')</span>
                                            @endif
                                        </td>
                                        <td>{{ showAmount($user->deposits->sum('amount')) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center"> {{ $emptyMessage }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($referrals->hasPages())
                        {{ paginateLinks($referrals) }}
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .referral-level-tabs {
            gap: 8px;
        }

        .referral-level-tabs .nav-link {
            border: 1px solid rgb(229 229 229 / 20%);
            border-radius: 6px;
            padding: 6px 12px;
            color: #fff;
        }

        .referral-level-tabs .nav-link.active {
            border-color: #ACE600;
            background: rgba(172, 230, 0, 0.1);
        }
    </style>
@endpush

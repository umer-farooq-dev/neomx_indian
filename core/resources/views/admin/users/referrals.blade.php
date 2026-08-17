@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-3">
        <div class="col-lg-3 col-sm-6">
            <div class="card b-radius--10 p-3 text-center">
                <span class="text-muted">@lang('Total Referrals')</span>
                <h3 class="mb-0">{{ $totalReferrals }}</h3>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card b-radius--10 p-3 text-center">
                <span class="text-muted">@lang('Active Referrals')</span>
                <h3 class="mb-0">{{ $activeReferrals }}</h3>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card b-radius--10 p-3 text-center">
                <span class="text-muted">@lang('Team Size')</span>
                <h3 class="mb-0">{{ $teamSize }}</h3>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card b-radius--10 p-3 text-center">
                <span class="text-muted">@lang('Referral Earnings')</span>
                <h3 class="mb-0">{{ showAmount($referralEarned) }}</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-header">
                    <ul class="nav nav-pills gap-2">
                        @forelse ($levelCounts as $lvl => $count)
                            <li class="nav-item">
                                <a class="nav-link @if ($lvl == $level) active @endif"
                                    href="{{ route('admin.users.referrals', $user->id) }}?level={{ $lvl }}">
                                    @lang('Level') {{ $lvl }} ({{ $count }})
                                </a>
                            </li>
                        @empty
                            <li class="nav-item"><span class="text-muted">@lang('No referrals yet')</span></li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Phone')</th>
                                    <th>@lang('Total Deposit')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($referrals as $refUser)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.users.detail', $refUser->id) }}">{{ $refUser->username }}</a>
                                        </td>
                                        <td>{{ $refUser->email }}</td>
                                        <td>{{ $refUser->mobile }}</td>
                                        <td>{{ showAmount($refUser->deposits->sum('amount')) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center"> {{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($referrals->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($referrals) }}
                </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush

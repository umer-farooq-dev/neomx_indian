@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-3">
        <div class="col-lg-4 col-sm-6">
            <div class="card b-radius--10 p-3 text-center">
                <span class="text-muted">@lang('Total Main Wallet')</span>
                <h3 class="mb-0">{{ showAmount($totalMain) }}</h3>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card b-radius--10 p-3 text-center">
                <span class="text-muted">@lang('Total Referral Wallet')</span>
                <h3 class="mb-0">{{ showAmount($totalReferral) }}</h3>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card b-radius--10 p-3 text-center">
                <span class="text-muted">@lang('Total Reward Wallet')</span>
                <h3 class="mb-0">{{ showAmount($totalReward) }}</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Main Wallet')</th>
                                    <th>@lang('Referral Wallet')</th>
                                    <th>@lang('Reward Wallet')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $u)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.users.detail', $u->id) }}">{{ $u->username }}</a>
                                            <br><small class="text-muted">{{ $u->email }}</small>
                                        </td>
                                        <td>{{ showAmount($u->balance) }}</td>
                                        <td>{{ showAmount($u->referral_balance) }}</td>
                                        <td>{{ showAmount($u->reward_balance) }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.detail', $u->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="la la-wallet"></i> @lang('Manage')
                                            </a>
                                        </td>
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
                @if ($users->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($users) }}
                </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush

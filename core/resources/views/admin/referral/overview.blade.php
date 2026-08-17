@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Direct Referrals')</th>
                                    <th>@lang('Active Referrals')</th>
                                    <th>@lang('Team Size')</th>
                                    <th>@lang('Referral Wallet')</th>
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
                                        <td>{{ $u->total_referrals_count }}</td>
                                        <td>{{ $u->active_referrals_count }}</td>
                                        <td>{{ $u->team_size_count }}</td>
                                        <td>{{ showAmount($u->referral_balance) }}</td>
                                        <td>
                                            @if ($u->total_referrals_count)
                                                <a href="{{ route('admin.users.referrals', $u->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-sitemap"></i> @lang('View Tree')
                                                </a>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
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

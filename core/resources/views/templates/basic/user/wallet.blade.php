@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="py-5">
        <div class="container">

            <div class="row gy-4">
                @foreach ($wallets as $type => $balance)
                    <div class="col-lg-4 col-sm-6">
                        <a class="d-block wallet-card-link" href="{{ route('user.wallet', ['wallet_type' => $type]) }}">
                            <div class="dashboard-card h-100 @if ($type == $walletType) wallet-card-active @endif">
                                <span>{{ __(\App\Models\User::walletLabel($type)) }}</span>
                                <h3 class="number">
                                    {{ gs('cur_sym') }}{{ showAmount($balance) }}
                                </h3>
                                <i class="las la-wallet icon"></i>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h4 class="mb-0">{{ __(\App\Models\User::walletLabel($walletType)) }} @lang('History')</h4>
                        @if ($walletType == 'main')
                            <a href="{{ route('user.withdraw.money') }}" class="btn btn--base btn-sm">@lang('Withdraw')</a>
                        @else
                            <a href="{{ route('user.withdraw.money') }}" class="btn btn--base btn-sm">@lang('Withdraw from this wallet')</a>
                        @endif
                    </div>

                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('Trx')</th>
                                    <th>@lang('Transacted')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Post Balance')</th>
                                    <th>@lang('Detail')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $trx)
                                    <tr>
                                        <td>{{ $trx->trx }}</td>
                                        <td>{{ showDateTime($trx->created_at) }}<br>{{ diffForHumans($trx->created_at) }}</td>
                                        <td class="budget">
                                            <span class="fw-bold @if ($trx->trx_type == '+') text-success @else text-danger @endif">
                                                {{ $trx->trx_type }} {{ showAmount($trx->amount) }}
                                            </span>
                                        </td>
                                        <td class="budget">{{ showAmount($trx->post_balance) }}</td>
                                        <td>{{ __($trx->details) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($transactions->hasPages())
                        {{ paginateLinks($transactions) }}
                    @endif
                </div>
            </div>

        </div>
    </section>
@endsection

@push('style')
    <style>
        .wallet-card-link {
            text-decoration: none;
        }

        .wallet-card-active {
            border: 1px solid #ACE600;
        }
    </style>
@endpush

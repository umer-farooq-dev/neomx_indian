@extends($activeTemplate . 'layouts.master')
@section('content')
    <section>
        <div class="container-fluid px-0">

            @php
                $walletAccents = ['main' => 'teal', 'referral' => 'green', 'reward' => 'pink'];
                $walletIcons = ['main' => 'las la-wallet', 'referral' => 'las la-sitemap', 'reward' => 'las la-gift'];
            @endphp

            <div class="row gy-4">
                @foreach ($wallets as $type => $balance)
                    <div class="col-lg-4 col-sm-6">
                        <a class="d-block" href="{{ route('user.wallet', ['wallet_type' => $type]) }}">
                            <div class="nxd-card nxd-card--{{ $walletAccents[$type] ?? 'teal' }} @if ($type == $walletType) is-active @endif">
                                <div class="nxd-card__head">
                                    <span class="nxd-card__label">{{ __(\App\Models\User::walletLabel($type)) }}</span>
                                    <span class="nxd-card__icon"><i class="{{ $walletIcons[$type] ?? 'las la-wallet' }}"></i></span>
                                </div>
                                <div class="nxd-card__value">{{ showAmount($balance) }}</div>
                                @if ($type == $walletType)
                                    <span class="nxd-card__link">@lang('Currently viewing')</span>
                                @else
                                    <span class="nxd-card__link">@lang('View history') <i class="las la-arrow-right"></i></span>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="nxd-panel">
                        <div class="nxd-panel__head">
                            <h5 class="nxd-panel__title">{{ __(\App\Models\User::walletLabel($walletType)) }} @lang('History')</h5>
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
                        <div class="mt-3">{{ paginateLinks($transactions) }}</div>
                    @endif
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('style')
    <style>
        .nxd-card.is-active {
            border-color: var(--accent);
            box-shadow: 0 0 0 1px var(--accent), 0 24px 45px -24px color-mix(in srgb, var(--accent) 60%, transparent);
        }
    </style>
@endpush

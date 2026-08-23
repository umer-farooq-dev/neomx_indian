@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $kyc = getContent('kyc.content', true);
    @endphp
    <section>
        <div class="container-fluid px-0">
            <div class="row align-items-center">
                <div class="col-lg-12 mb-30">
                    <div class="notice"></div>
                    @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
                        <div class="alert alert--danger" role="alert">
                            <div class="alert__icon"><i class="fas fa-file-signature"></i></div>
                            <p class="alert__message">
                                <span class="fw-bold">@lang('KYC Documents Rejected')</span><br>
                                <small>
                                    <i>
                                        {{ __(@$kyc->data_values->reject) }}
                                        <a href="javascript::void(0)" class="link-color" data-bs-toggle="modal"
                                            data-bs-target="#kycRejectionReason">@lang('Click here')
                                        </a> @lang('to show the reason').
                                        <a href="{{ route('user.kyc.form') }}" class="link-color">@lang('Click Here')</a>
                                        @lang('to Re-submit Documents').
                                        <a href="{{ route('user.kyc.data') }}" class="link-color">@lang('See KYC Data')</a>
                                    </i>
                                </small>
                            </p>
                        </div>
                    @elseif ($user->kv == Status::KYC_UNVERIFIED)
                        <div class="alert alert--info" role="alert">
                            <div class="alert__icon"><i class="fas fa-file-signature"></i></div>
                            <p class="alert__message">
                                <span class="fw-bold">@lang('KYC Verification Required')</span><br>
                                <small><i>{{ __(@$kyc->data_values->required) }}
                                        <a href="{{ route('user.kyc.form') }}" class="link-color">@lang('Click here')</a>
                                        @lang('to submit KYC information').</i></small>
                            </p>
                        </div>
                    @elseif($user->kv == Status::KYC_PENDING)
                        <div class="alert alert--warning" role="alert">
                            <div class="alert__icon"><i class="fas fa-user-check"></i></div>
                            <p class="alert__message">
                                <span class="fw-bold">@lang('KYC Verification Pending')</span><br>
                                <small><i>{{ __(@$kyc->data_values->pending) }} <a href="{{ route('user.kyc.data') }}"
                                            class="link-color">@lang('Click here')</a> @lang('to see your submitted information')</i></small>
                            </p>
                        </div>
                    @endif

                </div>

                <div class="col-lg-12 mb-30">

                    @include($activeTemplate . 'partials.referral_link')

                </div>

                @php
                    $dashCards = [
                        [
                            'label' => 'Main Wallet',
                            'value' => showAmount($user->balance),
                            'icon' => 'las la-wallet',
                            'accent' => 'teal',
                            'url' => route('user.wallet'),
                            'action' => 'View wallet',
                        ],
                        [
                            'label' => 'Total Deposit',
                            'value' => showAmount($totalDeposit),
                            'icon' => 'las la-plus-circle',
                            'accent' => 'blue',
                            'url' => route('user.deposit.history'),
                            'action' => 'View all',
                        ],
                        [
                            'label' => 'Total Withdraw',
                            'value' => showAmount($totalWithdraw),
                            'icon' => 'la la-hand-holding-usd',
                            'accent' => 'amber',
                            'url' => route('user.withdraw.history'),
                            'action' => 'View all',
                        ],
                        [
                            'label' => 'Total Investment',
                            'value' => showAmount($totalInvest),
                            'icon' => 'las la-project-diagram',
                            'accent' => 'purple',
                            'url' => route('user.investment.log'),
                            'action' => 'View all',
                        ],
                        [
                            'label' => 'Referral Wallet',
                            'value' => showAmount($user->referral_balance),
                            'icon' => 'las la-sitemap',
                            'accent' => 'green',
                            'url' => route('user.wallet', ['wallet_type' => 'referral']),
                            'action' => 'View wallet',
                        ],
                        [
                            'label' => 'Reward Wallet',
                            'value' => showAmount($user->reward_balance),
                            'icon' => 'las la-gift',
                            'accent' => 'pink',
                            'url' => route('user.wallet', ['wallet_type' => 'reward']),
                            'action' => 'View wallet',
                        ],
                        [
                            'label' => 'Team Members',
                            'value' => $teamSize,
                            'icon' => 'las la-user-friends',
                            'accent' => 'blue',
                            'url' => route('user.referrals'),
                            'action' => 'View team',
                        ],
                        [
                            'label' => 'Spin Rewards Available',
                            'value' => $availableSpins,
                            'icon' => 'las la-dharmachakra',
                            'accent' => 'teal',
                            'url' => route('user.spin'),
                            'action' => 'Spin now',
                        ],
                    ];
                @endphp

                @foreach ($dashCards as $card)
                    <div class="col-xxl-3 col-lg-4 col-sm-6 mb-4">
                        <div class="nxd-card nxd-card--{{ $card['accent'] }}">
                            <div class="nxd-card__head">
                                <span class="nxd-card__label">@lang($card['label'])</span>
                                <span class="nxd-card__icon"><i class="{{ $card['icon'] }}"></i></span>
                            </div>
                            <div class="nxd-card__value">{{ $card['value'] }}</div>
                            <a href="{{ $card['url'] }}" class="nxd-card__link">
                                @lang($card['action']) <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div><!-- row end -->
            <div class="nxd-plans-row mt-4">

                <!-- Here Attach Plans cardfrom view partial blade  -->
                @include($activeTemplate . 'partials.plans_card')

            </div>
            <div class="row mt-4">
                <div class="col-lg-12">

                    <div class="nxd-panel">
                        <div class="nxd-panel__head">
                            <h5 class="nxd-panel__title">@lang('Latest Transactions')</h5>
                            <a href="{{ route('user.transactions') }}" class="nxd-panel__action">@lang('View all') <i class="las la-arrow-right"></i></a>
                        </div>
                        <div class="table-responsive--md">
                        <table class="custom--table table">
                            <thead>
                                <tr>
                                    <th>@lang('Trx')</th>
                                    <th>@lang('Transacted')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Charge')</th>
                                    <th>@lang('Post Balance')</th>
                                    <th>@lang('Detail')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($latestTrx as $data)
                                    <tr>
                                        <td>{{ $data->trx }}</td>
                                        <td>
                                            {{ showDateTime($data->created_at) }}</td>

                                        <td class="budget">
                                            <span class="fw-bold @if ($data->trx_type == '+') text-success @else text-danger @endif">
                                                {{ $data->trx_type }} {{ showAmount($data->amount) }}

                                            </span>
                                        </td>

                                        <td>{{ showAmount($data->charge) }}</td>
                                        <td class="budget">
                                            {{ showAmount($data->post_balance) }}
                                        </td>
                                        <td>{{ __($data->details) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-plan-modal />

    </section>

    @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
        <div class="modal fade" id="kycRejectionReason">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"><i
                                class="las la-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ auth()->user()->kyc_rejection_reason }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

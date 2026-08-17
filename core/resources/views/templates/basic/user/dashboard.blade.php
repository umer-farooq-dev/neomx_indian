@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $kyc = getContent('kyc.content', true);
    @endphp
    <section class="py-5">
        <div class="container">
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

                <div class="col-lg-3 col-sm-6">
                    <a class="d-block" href="{{ route('user.wallet') }}">
                        <div class="balance-card">
                            <span class="text--dark">@lang('Main Wallet')</span>
                            <h3 class="number text--dark">
                                {{ showAmount($user->balance) }}
                            </h3>
                        </div><!-- dashboard-card end -->
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="dashboard-card h-100">
                        <span>@lang('Total Deposit')</span>
                        <a class="view--btn" href="{{ route('user.deposit.history') }}">@lang('View all')</a>
                        <h3 class="number">
                            {{ showAmount($totalDeposit) }}
                        </h3>
                        <i class="las la-dollar-sign icon"></i>
                    </div><!-- dashboard-card end -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="dashboard-card">
                        <span>@lang('Total Withdraw')</span>
                        <a class="view--btn" href="{{ route('user.withdraw.history') }}">@lang('View all')</a>
                        <h3 class="number">
                            {{ showAmount($totalWithdraw) }}
                        </h3>
                        <i class="las la-hand-holding-usd icon"></i>
                    </div><!-- dashboard-card end -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="dashboard-card">
                        <span>@lang('Total Investment')</span>
                        <a class="view--btn" href="{{ route('user.investment.log') }}">@lang('View all')</a>
                        <h3 class="number">
                            {{ showAmount($totalInvest) }}
                        </h3>
                        <i class="las la-dollar-sign icon"></i>
                    </div><!-- dashboard-card end -->
                </div>

                <div class="col-lg-3 col-sm-6">
                    <a class="d-block" href="{{ route('user.wallet', ['wallet_type' => 'referral']) }}">
                        <div class="dashboard-card h-100">
                            <span>@lang('Referral Wallet')</span>
                            <h3 class="number">
                                {{ showAmount($user->referral_balance) }}
                            </h3>
                            <i class="las la-sitemap icon"></i>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <a class="d-block" href="{{ route('user.wallet', ['wallet_type' => 'reward']) }}">
                        <div class="dashboard-card h-100">
                            <span>@lang('Reward Wallet')</span>
                            <h3 class="number">
                                {{ showAmount($user->reward_balance) }}
                            </h3>
                            <i class="las la-gift icon"></i>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <a class="d-block" href="{{ route('user.referrals') }}">
                        <div class="dashboard-card h-100">
                            <span>@lang('Team Members')</span>
                            <h3 class="number">
                                {{ $teamSize }}
                            </h3>
                            <i class="las la-user-friends icon"></i>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <a class="d-block" href="{{ route('user.spin') }}">
                        <div class="dashboard-card h-100">
                            <span>@lang('Spin Rewards Available')</span>
                            <h3 class="number">
                                {{ $availableSpins }}
                            </h3>
                            <i class="las la-dharmachakra icon"></i>
                        </div>
                    </a>
                </div>
            </div><!-- row end -->
            <div class="row justify-content-center gx-4 gy-5 mt-5">

                <!-- Here Attach Plans cardfrom view partial blade  -->
                @include($activeTemplate . 'partials.plans_card')

            </div>
            <div class="row mt-5">
                <div class="col-lg-12">

                    <div class="table-responsive--md">
                        <h4 class="mb-3">@lang('Latest Transactions')</h4>
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

    @if ($user->kv == Status::KYC_UNVERIFIED)
        <div class="modal fade" id="kycRequiredModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Complete Your KYC')</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"><i
                                class="las la-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            @if ($user->kyc_rejection_reason)
                                @lang('Your KYC documents were rejected. Please resubmit your information to unlock investments and withdrawals.')
                            @else
                                @lang('Please complete your KYC verification to unlock investments and withdrawals.')
                            @endif
                        </p>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('user.kyc.form') }}" class="btn btn--base w-100">@lang('Complete KYC')</a>
                    </div>
                </div>
            </div>
        </div>

        @push('script')
            <script>
                (function($) {
                    "use strict";
                    $(window).on('load', function() {
                        $('#kycRequiredModal').modal('show');
                    });
                })(jQuery);
            </script>
        @endpush
    @endif
@endsection

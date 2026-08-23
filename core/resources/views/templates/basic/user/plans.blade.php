@extends($activeTemplate . 'layouts.master')
@section('content')
    <section>
        <div class="container-fluid px-0">
            <div class="nxd-plans-row">
                @forelse($plans as $plan)
                    @php
                        $isFixed = $plan->interest_type == Status::FIXED;
                        $unit = $isFixed ? gs('cur_sym') : '';
                        $suffix = $isFixed ? '' : '%';
                        $tier = ($loop->index % 5) + 1;
                    @endphp
                    <div class="nxd-plan nxd-plan--{{ $tier }}">
                        <span class="nxd-plan__tier">@lang('Tier') {{ $loop->iteration }}</span>

                        <div class="nxd-plan__name">{{ __($plan->name) }}</div>
                        <div class="nxd-plan__price">{{ gs('cur_sym') }}{{ showAmount($plan->min_amount, 0, currencyFormat: false) }}</div>

                        <div class="nxd-plan__row">
                            <span>@lang('Daily Return')</span>
                            <b>{{ $unit }}{{ showAmount($plan->interest, 0, currencyFormat: false) }}{{ $suffix }}</b>
                        </div>
                        <div class="nxd-plan__row">
                            <span>@lang('Duration')</span>
                            <b>{{ $plan->total_return }} @lang('Days')</b>
                        </div>
                        @if ($plan->referral_threshold > 0)
                            <div class="nxd-plan__row">
                                <span>@lang('After') {{ $plan->referral_threshold }} @lang('Referrals')</span>
                                <b>{{ $unit }}{{ showAmount($plan->boost_interest, 0, currencyFormat: false) }}{{ $suffix }}</b>
                            </div>
                        @endif
                        <div class="nxd-plan__row">
                            <span>@lang('Invest Range')</span>
                            <b>{{ gs('cur_sym') }}{{ showAmount($plan->min_amount, 0, currencyFormat: false) }}
                                - {{ gs('cur_sym') }}{{ showAmount($plan->max_amount, 0, currencyFormat: false) }}</b>
                        </div>

                        <a href="#0" data-name="{{ __($plan->name) }}" data-id="{{ $plan->id }}"
                        data-min="{{ getAmount($plan->min_amount) }}" data-max="{{ getAmount($plan->max_amount) }}" class="nxd-plan__btn planModal"
                            data-bs-toggle="modal" data-bs-target="{{ Auth::user() ? '#planModal' : '#loginModal' }}">
                            @lang('Invest Now') <i class="las la-arrow-circle-right"></i>
                        </a>
                    </div>
                @empty
                    <div class="nxd-panel text-center" style="grid-column: 1 / -1">
                        @lang('Plan does not found')
                    </div>
                @endforelse
            </div>

            @if ($plans->hasPages())
                <div class="mt-4">{{ paginateLinks($plans) }}</div>
            @endif
        </div>
    </section>
    <!-- Here is Buying Plan Modal Component  -->
    <x-plan-modal />
@endsection

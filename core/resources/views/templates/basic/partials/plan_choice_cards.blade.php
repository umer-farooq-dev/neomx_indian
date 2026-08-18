{{--
    The dashboard plan cards, reused as a picker. Same markup and styling as
    partials.plans_card so the deposit flow shows exactly what the user already
    saw on the homepage and dashboard — only the button differs: it selects the
    plan instead of opening the invest modal.

    Props: $plans
--}}
@foreach ($plans as $plan)
    @php
        $isFixed = $plan->interest_type == Status::FIXED;
        $unit = $isFixed ? gs('cur_sym') : '';
        $suffix = $isFixed ? '' : '%';
        $tier = ($loop->index % 5) + 1;
    @endphp
    <div class="col-xl-4 col-md-6 mb-4">
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

            <button type="button" class="nxd-plan__btn choose-plan"
                data-id="{{ $plan->id }}"
                data-name="{{ __($plan->name) }}"
                data-min="{{ getAmount($plan->min_amount) }}"
                data-max="{{ getAmount($plan->max_amount) }}">
                @lang('Select Plan') <i class="las la-arrow-circle-right"></i>
            </button>
        </div>
    </div>
@endforeach

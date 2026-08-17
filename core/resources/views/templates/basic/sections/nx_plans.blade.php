@php
    $plans = App\Models\Plan::where('status', Status::ENABLE)->orderBy('min_amount', 'ASC')->get();
@endphp

<section class="nx-section nx-plans" id="plan">
    <div class="nx-container">
        <div class="nx-head" data-reveal>
            <div class="nx-eyebrow justify-content-center">@lang('Investment Plans')</div>
            <h2 class="nx-title">@lang('Choose Your Plan & Start Earning Daily')</h2>
            <p class="nx-sub">@lang('Higher investment, higher daily returns')</p>
        </div>

        <div class="nx-plan-grid" data-reveal>
            @foreach ($plans as $plan)
                @php
                    $isFixed = $plan->interest_type == Status::FIXED;
                    $unit = $isFixed ? gs('cur_sym') : '';
                    $suffix = $isFixed ? '' : '%';
                @endphp
                <div class="nx-plan nx-plan--{{ $loop->iteration }}">
                    <span class="nx-plan__tier">@lang('Tier') {{ $loop->iteration }}</span>

                    <div class="nx-plan__name">{{ __($plan->name) }}</div>
                    <div class="nx-plan__price">{{ gs('cur_sym') }}{{ showAmount($plan->min_amount, 0, currencyFormat: false) }}</div>

                    <div class="nx-plan__row">
                        <span>@lang('Base Daily Return')</span>
                        <b>{{ $unit }}{{ showAmount($plan->interest, 0, currencyFormat: false) }}{{ $suffix }}</b>
                    </div>

                    @if ($plan->referral_threshold > 0)
                        <div class="nx-plan__row">
                            <span>@lang('After') {{ $plan->referral_threshold }} @lang('Referrals')</span>
                            <b>{{ $unit }}{{ showAmount($plan->boost_interest, 0, currencyFormat: false) }}{{ $suffix }}</b>
                        </div>
                        <div class="nx-plan__row">
                            <span>@lang('Extra Daily Income')</span>
                            <b>+{{ $unit }}{{ showAmount($plan->boost_interest - $plan->interest, 0, currencyFormat: false) }}{{ $suffix }}</b>
                        </div>
                    @endif

                    <a href="#0" data-name="{{ __($plan->name) }}" data-id="{{ $plan->id }}" class="nx-plan__btn planModal"
                        data-bs-toggle="modal" data-bs-target="{{ Auth::user() ? '#planModal' : '#loginModal' }}">
                        @lang('Invest Now') <i class="las la-arrow-circle-right"></i>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="nx-plans__note-wrap" data-reveal>
            <span class="nx-plans__note">
                * {{ $plans->first()?->referral_threshold ?? 5 }} @lang('Active Referrals required to unlock upgraded daily return')
            </span>
        </div>
    </div>
</section>

<x-plan-modal />

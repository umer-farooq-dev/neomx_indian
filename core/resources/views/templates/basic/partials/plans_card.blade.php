@php
    $plans = App\Models\Plan::where('status', Status::ENABLE)
        ->limit(12)
        ->orderBy("min_amount","ASC")
        ->get();
@endphp

@forelse($plans as $plan)
    @php
        $isFixed = $plan->interest_type == Status::FIXED;
        $unit = $isFixed ? gs('cur_sym') : '';
        $suffix = $isFixed ? '' : '%';
        $tier = ($loop->index % 5) + 1;
    @endphp
    <div class="col-xxl-3 col-lg-4 col-sm-6 mb-4">
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

            <a href="#0" data-name="{{ __($plan->name) }}" data-id="{{ $plan->id }}" class="nxd-plan__btn planModal"
                data-bs-toggle="modal" data-bs-target="{{ Auth::user() ? '#planModal' : '#loginModal' }}">
                @lang('Invest Now') <i class="las la-arrow-circle-right"></i>
            </a>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="nxd-panel text-center">
            @lang('Plan does not found')
        </div>
    </div>
@endforelse

@auth
    @if ($plans->count() > 12)
        <div class="col-12 text-center">
            <a href="{{ route('user.plans') }}" class="btn btn--base">@lang('View All')</a>
        </div>
    @endif
@endauth

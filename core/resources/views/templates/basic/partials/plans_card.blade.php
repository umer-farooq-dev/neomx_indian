@php
    $plans = App\Models\Plan::where('status', Status::ENABLE)
        ->limit(12)
        ->orderBy("min_amount","ASC")
        ->get();
@endphp

@forelse($plans as $plan)
    <div class="col-xl-3 col-lg-4 col-sm-6 mb-70 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.7s">
        <div class="package-card text-center bg_img" style="background-image: url('{{ asset($activeTemplateTrue . 'images/bg/plan.jpg') }}');">
            <h4 class="package-card__title">{{ __($plan->name) }}</h4>
            <div class="package-card__range mt-4 base--color">
                {{ showAmount($plan->min_amount, 0) }}
                -
                {{ showAmount($plan->max_amount, 0) }}
            </div>
            <ul class="package-card__features mt-3">
                <li>
                    @lang('Return')
                    {{ showAmount($plan->interest, 0, currencyFormat: false) }}{{ $plan->interest_type == Status::FIXED ? ' ' . gs('cur_text') : '%' }}
                </li>
                <li>@lang('Every Day')</li>
                <li>@lang('For') {{ $plan->total_return }} @lang('Times')</li>
                @if ($plan->referral_threshold > 0)
                    <li class="text--base">
                        @lang('Refer') {{ $plan->referral_threshold }} @lang('active users, earn')
                        {{ showAmount($plan->boost_interest, 0, currencyFormat: false) }}{{ $plan->interest_type == Status::FIXED ? ' ' . gs('cur_text') : '%' }}
                        /@lang('day instead')
                    </li>
                @endif
            </ul>
            <a href="#0" data-name="{{ __($plan->name) }}" data-id="{{ $plan->id }}" class="btn btn-md btn--base mt-4 planModal"
                data-bs-toggle="modal" data-bs-target="{{ Auth::user() ? '#planModal' : '#loginModal' }}">
                @lang('Invest Now')
            </a>
        </div><!-- package-card end -->
    </div>
@empty
    <h2 class="text-center">
        <div class="alert dashboard-card" role="alert">
            @lang('Plan does not found')
        </div>
    </h2>
@endforelse

@auth
    @if ($plans->count() > 12)
        <div class="text-center">
            <a href="{{ route('user.plans') }}" class="btn btn--base btn--capsule" data-wow-duration="0.5s" data-wow-delay="0.7s">@lang('View All')</a>
        </div>
    @endif
@endauth

@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="pt-5 pb-5 plan-list">
        <div class="container">
            <div class="row g-4 justify-content-center">
                @forelse($plans as $plan)
                    <div class="col-xl-3 col-lg-4 col-sm-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.7s">
                        <div class="package-card text-center bg_img"
                            style="background-image: url('{{ asset($activeTemplateTrue . 'images/bg/plan.jpg') }}');">
                            <h4 class="package-card__title">{{ __($plan->name) }}</h4>
                            <div class="package-card__range mt-4 base--color">
                                {{ gs('cur_sym') }}{{ showAmount($plan->min_amount, currencyFormat: false) }}
                                -
                                {{ gs('cur_sym') }}{{ showAmount($plan->max_amount, currencyFormat: false) }}
                            </div>
                            <ul class="package-card__features mt-3">
                                <li>
                                    @lang('Return')
                                    {{ showAmount($plan->interest, 0, currencyFormat: false) }}{{ $plan->interest_type == Status::FIXED ? ' ' . gs('cur_text') : '%' }}
                                </li>
                                <li>@lang('Every Day')</li>
                                <li>@lang('For') {{ $plan->total_return }} @lang('Times')</li>
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
                @if ($plans->hasPages())
                    {{ paginateLinks($plans) }}
                @endif
            </div>
    </section>
    <!-- Here is Buying Plan Modal Component  -->
    <x-plan-modal />
@endsection

@push('style')
    <style>
        .plan-list {
            margin-top: 50px;
            margin-bottom: 20px;
        }
    </style>
@endpush

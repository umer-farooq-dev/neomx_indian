@extends($activeTemplate . 'layouts.frontend')

@push('style-lib')
    <link href="{{ asset($activeTemplateTrue . 'css/nx-home.css') }}" rel="stylesheet">
@endpush

@section('content')
    @php
        $banner = getContent('banner.content', true);
    @endphp

    <!-- nx hero start -->
    <section class="nx-hero">
        <div class="nx-container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6" data-reveal>
                    @php
                        // Heading supports an optional "|" split so the admin can control which
                        // part renders in the accent gradient. Without it the whole line is plain.
                        $headingParts = array_map('trim', explode('|', __(@$banner->data_values->heading), 2));
                    @endphp
                    <h1 class="nx-hero__title">
                        <span>{{ $headingParts[0] }}</span>
                        @if (!empty($headingParts[1]))
                            <span class="nx-gradient">{{ $headingParts[1] }}</span>
                        @endif
                    </h1>
                    <p class="nx-hero__desc">{{ __(@$banner->data_values->subheading) }}</p>

                    <div class="nx-hero__mini">
                        <div class="nx-hero__mini-item">
                            <span class="nx-hero__mini-icon"><i class="las la-calendar-check"></i></span>
                            <div>
                                <div class="nx-hero__mini-title">@lang('Daily Returns')</div>
                                <div class="nx-hero__mini-sub">@lang('Earn Every Day')</div>
                            </div>
                        </div>
                        <div class="nx-hero__mini-item">
                            <span class="nx-hero__mini-icon"><i class="las la-user-friends"></i></span>
                            <div>
                                <div class="nx-hero__mini-title">@lang('Referral Bonuses')</div>
                                <div class="nx-hero__mini-sub">@lang('Extra Income')</div>
                            </div>
                        </div>
                        <div class="nx-hero__mini-item">
                            <span class="nx-hero__mini-icon"><i class="las la-bolt"></i></span>
                            <div>
                                <div class="nx-hero__mini-title">@lang('Instant Withdrawals')</div>
                                <div class="nx-hero__mini-sub">@lang('24x7 Payouts')</div>
                            </div>
                        </div>
                        <div class="nx-hero__mini-item">
                            <span class="nx-hero__mini-icon"><i class="las la-shield-alt"></i></span>
                            <div>
                                <div class="nx-hero__mini-title">@lang('Secure & Trusted')</div>
                                <div class="nx-hero__mini-sub">@lang('100% Safe Platform')</div>
                            </div>
                        </div>
                    </div>

                    <div class="nx-hero__cta">
                        @auth
                            <a href="{{ route('user.home') }}" class="nx-btn nx-btn--solid">@lang('Get Started Now') <i class="las la-arrow-right"></i></a>
                        @else
                            <a href="#0" class="nx-btn nx-btn--solid" data-bs-toggle="modal" data-bs-target="#registerModal">
                                @lang('Get Started Now') <i class="las la-arrow-right"></i>
                            </a>
                        @endauth
                        <a href="#plan" class="nx-btn nx-btn--outline">@lang('View Plans')</a>
                    </div>
                </div>
                <div class="col-lg-6" data-reveal>
                    <div class="nx-hero__art">
                        @include($activeTemplate . 'partials.hero_scene')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- nx hero end -->

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection

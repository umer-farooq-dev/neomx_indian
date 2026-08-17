@extends($activeTemplate . 'layouts.app')
@section('panel')
    @include($activeTemplate . 'partials.header')

    @guest
        @include($activeTemplate . 'partials.modal')
    @endguest
    <div class="main-wrapper">
        @if (!request()->routeIs('home'))
            @include($activeTemplate . 'partials.breadcrumb')
        @endif
        @yield('content')
    </div>
    @php
        $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    @endphp
    @if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
        <!-- cookies dark version start -->
        <div class="cookies-card hide text-center">
            <div class="cookies-card__icon bg--base">
                <i class="las la-cookie-bite"></i>
            </div>
            <p class="cookies-card__content mt-4">{{ __($cookie->data_values->short_desc) }} <a class="text--base" href="{{ route('cookie.policy') }}"
                    target="_blank">@lang('learn more')</a></p>
            <div class="cookies-card__btn mt-4">
                <a class="btn btn--base w-100 policy" href="javascript:void(0)">@lang('Allow')</a>
            </div>
        </div>
    @endif
@endsection
@push('script')
    <script>
        (function($) {
            "use strict";

            //Start-Id-Wise-Route-set
            let currentRoute = '{{ Route::currentRouteName() }}'
            let sectionArray = ['#about', '#plan', '#feature', '#faq', '#gateway'];
            if (currentRoute != 'home') {
                let links = $('#linkItem a');
                links.on('click', function() {
                    let section = $(this).attr('href');
                    let base = '{{ route('home') }}';
                    if (sectionArray.includes(section)) {
                        window.location = base + section;
                    }
                });
            }
            //End-Id-Wise-Route-set

            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);
        })(jQuery);
    </script>
@endpush

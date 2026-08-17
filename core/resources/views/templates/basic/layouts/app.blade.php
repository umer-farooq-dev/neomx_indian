<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')
    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/line-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset($activeTemplateTrue . 'css/main.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/custom.css') }}" rel="stylesheet">
    @stack('style-lib')
    @stack('style')
    <link href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}&secondColor={{ gs('secondary_color') }}"
        rel="stylesheet">
</head>
@php echo loadExtension('google-analytics') @endphp

<body>

    <div class="preloader">
        <div class="preloader-container">
            <span class="animated-preloader"></span>
        </div>
    </div>

    <!-- scroll-to-top start -->
    <div class="scroll-to-top">
        <span class="scroll-icon">
            <i class="las la-chevron-up"></i>
        </span>
    </div>

    <div class="body-overlay"></div>

    @php
        $siteNotice = \App\Models\Frontend::where('data_keys', 'notice.data')->first();
    @endphp
    @if ($siteNotice && @$siteNotice->data_values->status && !request()->routeIs('maintenance'))
        <div class="site-notice-bar" id="siteNoticeBar">
            <span>{{ __($siteNotice->data_values->text) }}</span>
            <button type="button" class="site-notice-bar__close" aria-label="Dismiss" onclick="document.getElementById('siteNoticeBar').style.display='none'">&times;</button>
        </div>
        <style>
            .site-notice-bar {
                position: relative;
                z-index: 20;
                background: #ACE600;
                color: #16211b;
                font-weight: 600;
                font-size: .9rem;
                text-align: center;
                padding: 10px 40px;
            }
            .site-notice-bar__close {
                position: absolute;
                right: 14px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                font-size: 1.3rem;
                line-height: 1;
                color: #16211b;
                cursor: pointer;
            }
        </style>
    @endif

    @yield('panel')

    @if (!request()->routeIs('maintenance'))
        @include($activeTemplate . 'partials.footer')
    @endif

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/wow.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/app.js') }}"></script>

    @stack('script-lib')
    @php echo loadExtension('tawk-chat') @endphp
    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif
    @stack('script')
    <script>
        (function($) {
            "use strict";
            $(".langSel").on("change", function() {
                window.location.href = "{{ route('home') }}/change/" + $(this).val();
            });

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }
            });

            $('.select2').each(function(index, element) {
                $(element).select2({
                    minimumResultsForSearch: "-1"
                });
            });

            $('.select2-basic').each(function(index, element) {
                $(element).select2({
                    dropdownParent: $(element).closest('.select2-parent')
                });
            });

            let disableSubmission = false;
            $('.disableSubmission').on('submit', function(e) {
                if (disableSubmission) {
                    e.preventDefault()
                } else {
                    disableSubmission = true;
                }
            });

        })(jQuery);
    </script>
</body>

</html>

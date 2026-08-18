@extends($activeTemplate . 'layouts.app')

@push('style-lib')
    <link href="{{ asset($activeTemplateTrue . 'css/nx-dash.css') }}" rel="stylesheet">
@endpush

@section('body-class', 'nx-dash')

@section('panel')
    @include($activeTemplate . 'partials.dash_sidebar')

    <div class="nxd-shell">
        @include($activeTemplate . 'partials.dash_topbar')

        <main class="nxd-body">
            @yield('content')
        </main>
    </div>

    <x-logout-confirmation />
@endsection

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.validate.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.showFilterBtn').on('click', function() {
                $('.responsive-filter-card').slideToggle();
            });

            // stacked table labels on small screens
            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelectorAll('thead tr th');
                Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                    Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                        if (heading[i]) {
                            colum.setAttribute('data-label', heading[i].innerText)
                        }
                    });
                });
            });
        })(jQuery);
    </script>
@endpush

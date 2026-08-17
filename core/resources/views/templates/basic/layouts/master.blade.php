@extends($activeTemplate . 'layouts.app')
@section('panel')
    @include($activeTemplate . 'partials.auth_header')
    <div class="main-wrapper">
        @include($activeTemplate . 'partials.breadcrumb')
        @yield('content')
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
            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelectorAll('thead tr th');
                Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                    Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                        colum.setAttribute('data-label', heading[i].innerText)
                    });
                });
            });
        })(jQuery);
    </script>
@endpush

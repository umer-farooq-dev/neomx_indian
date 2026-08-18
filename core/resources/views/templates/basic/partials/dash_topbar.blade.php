@php
    $topUnread = \App\Models\NotificationLog::where('user_id', auth()->id())->where('user_read', 0)->count();
@endphp

<header class="nxd-top">
    <button type="button" class="nxd-burger" id="nxdBurger" aria-label="@lang('Toggle menu')">
        <i class="las la-bars"></i>
    </button>

    <h4 class="nxd-top__title">{{ __($pageTitle ?? gs('site_name')) }}</h4>

    <div class="nxd-top__right">
        @if (gs('multi_language'))
            @php
                $language = App\Models\Language::all();
                $selectLang = $language->where('code', config('app.locale'))->first();
            @endphp
            <div class="language_switcher">
                <div class="language_switcher__caption">
                    <span class="icon">
                        <img src="{{ getImage(getFilePath('language') . '/' . $selectLang->image, getFileSize('language')) }}" alt="@lang('image')">
                    </span>
                    <span class="text"> {{ __(@$selectLang->name) }} </span>
                </div>
                <div class="language_switcher__list">
                    @foreach ($language as $item)
                        <div class="language_switcher__item @if (session('lang') == $item->code) selected @endif" data-value="{{ $item->code }}">
                            <a href="{{ route('lang', $item->code) }}" class="thumb">
                                <span class="icon">
                                    <img src="{{ getImage(getFilePath('language') . '/' . $item->image, getFileSize('language')) }}" alt="@lang('image')">
                                </span>
                                <span class="text"> {{ __($item->name) }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <a href="{{ route('user.notifications') }}" class="nxd-iconbtn" title="@lang('Notifications')">
            <i class="las la-bell"></i>
            @if ($topUnread)
                <span class="nxd-iconbtn__dot">{{ $topUnread }}</span>
            @endif
        </a>

        <a href="{{ route('home') }}" class="nxd-iconbtn d-none d-sm-inline-flex" title="@lang('Visit Website')">
            <i class="las la-globe"></i>
        </a>

        <a href="#0" class="nxd-logout" data-bs-toggle="modal" data-bs-target="#ConfirmationModal">
            <i class="las la-sign-out-alt"></i> <span class="d-none d-sm-inline">@lang('Logout')</span>
        </a>
    </div>
</header>

@push('script')
    <script>
        (function($) {
            "use strict";

            const $side = $('#nxdSide');
            const $backdrop = $('#nxdBackdrop');

            $('#nxdBurger').on('click', function() {
                $side.toggleClass('open');
                $backdrop.toggleClass('show');
            });

            $backdrop.on('click', function() {
                $side.removeClass('open');
                $backdrop.removeClass('show');
            });

            // collapsible sidebar groups
            $('.nxd-group > .nxd-link').on('click', function(e) {
                e.preventDefault();
                $(this).parent().toggleClass('open');
            });

            $('.language_switcher > .language_switcher__caption').on('click', function() {
                $(this).parent().toggleClass('open');
            });

            $(document).on('keyup', function(evt) {
                if ((evt.keyCode || evt.which) === 27) {
                    $('.language_switcher').removeClass('open');
                    $side.removeClass('open');
                    $backdrop.removeClass('show');
                }
            });

            $(document).on('click', function(evt) {
                if ($(evt.target).closest(".language_switcher > .language_switcher__caption").length === 0) {
                    $('.language_switcher').removeClass('open');
                }
            });
        })(jQuery);
    </script>
@endpush

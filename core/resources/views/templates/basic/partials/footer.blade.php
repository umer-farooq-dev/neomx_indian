@php
    $socialIcons = getContent('social_icon.element', false, null, true);
    $policyPages = getContent('policy_pages.element');
@endphp
<!-- footer start -->
<footer class="footer">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-2 col-md-3 text-md-start text-center">
                <a href="{{ route('home') }}" class="footer-logo"><img src="{{ siteLogo() }}" alt="image"></a>
            </div>
            <div class="col-lg-10 col-md-9 mt-md-0 mt-3">
                <ul class="inline-menu d-flex flex-wrap justify-content-md-end justify-content-center align-items-center">
                    <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                    @foreach ($policyPages as $policy)
                        <li><a href="{{ route('policy.pages', $policy->slug) }}">{{ __($policy->data_values->title) }}</a></li>
                    @endforeach
            </div>
        </div><!-- row end -->
        <hr class="mt-3">
        <div class="row align-items-center">
            <div class="col-md-6 text-md-start text-center">
                <span class="footer-content__left-text"> &copy; @lang('Copyright')
                    {{ \Carbon\Carbon::now()->format('Y') }}. @lang('All Right Reserved')
                    <a class="text--base" href="{{ route('home') }}">{{ @gs('site_name') }}.</a>
                </span>
            </div>

            <div class="col-md-6 mt-md-0 mt-3">
                <ul class="inline-social-links d-flex align-items-center justify-content-md-end justify-content-center">
                    @foreach ($socialIcons as $icon)
                        <li><a href="{{ $icon->data_values->url }}" target="_blank"> @php echo $icon->data_values->social_icon; @endphp </a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</footer>
<!-- footer end -->

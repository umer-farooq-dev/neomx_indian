@php
    $socialIcons = getContent('social_icon.element', false, null, true);
    $policyPages = getContent('policy_pages.element');
    $seoData = App\Models\Frontend::where('data_keys', 'seo.data')->first();
    $gatewayShowcase = getContent('payment.element');
@endphp
<!-- footer start -->
<footer class="nx-footer">
    <div class="nx-container">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6 nx-foot-brand">
                <a href="{{ route('home') }}" class="d-inline-block mb-3"><img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}" style="max-height:44px;"></a>
                <p>{{ __(@$seoData->data_values->description) }}</p>
                <div class="nx-social mt-3">
                    @foreach ($socialIcons as $icon)
                        <a href="{{ $icon->data_values->url }}" target="_blank">@php echo $icon->data_values->social_icon; @endphp</a>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-2 col-md-6 nx-foot-links">
                <h6>@lang('Quick Links')</h6>
                <ul>
                    <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                    <li><a href="{{ route('home') }}#plan">@lang('Investment Plans')</a></li>
                    <li><a href="{{ route('home') }}#how-it-works">@lang('How It Works')</a></li>
                    <li><a href="{{ route('home') }}#referral">@lang('Referral Program')</a></li>
                    <li><a href="{{ route('home') }}#faq">@lang('FAQ')</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6 nx-foot-links">
                <h6>@lang('Company')</h6>
                <ul>
                    <li><a href="{{ route('home') }}#about">@lang('About Us')</a></li>
                    @foreach ($policyPages as $policy)
                        <li><a href="{{ route('policy.pages', $policy->slug) }}">{{ __($policy->data_values->title) }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-2 col-md-6 nx-foot-links">
                <h6>@lang('Support')</h6>
                <ul>
                    @if (gs('social_number'))
                        <li><i class="las la-phone"></i> {{ gs('social_number') }}</li>
                    @endif
                    @if (gs('email_from') && !str_contains(gs('email_from'), 'viserlab'))
                        <li><i class="las la-envelope"></i> {{ gs('email_from') }}</li>
                    @endif
                    <li><a href="{{ auth()->check() ? route('ticket.open') : route('contact') }}">@lang('24x7 Live Support')</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6 nx-foot-pay">
                <h6>@lang('Payment Methods')</h6>
                <div class="nx-pay-badges">
                    @forelse ($gatewayShowcase as $item)
                        <span class="nx-pay-badge">{{ __(@$item->data_values->gateway_name) }}</span>
                    @empty
                        <span class="nx-pay-badge">@lang('Coming Soon')</span>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="nx-footer-bottom">
            <span>&copy; {{ \Carbon\Carbon::now()->format('Y') }} {{ gs('site_name') }}. @lang('All Rights Reserved')</span>
            <span>@lang('Made with') <i class="las la-heart text--base"></i> @lang('for our investors')</span>
        </div>
    </div>
</footer>
<!-- footer end -->

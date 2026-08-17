<section class="nx-section" id="referral">
    <div class="nx-container">
        <div class="row gy-4">
            <div class="col-lg-6" data-reveal>
                <div class="nx-panel">
                    <div class="nx-eyebrow">@lang('Referral Program')</div>
                    <div class="nx-panel__title">@lang('Refer More, Earn More!')</div>
                    <p class="nx-panel__desc">@lang('Invite your friends and earn exciting rewards on every active referral.')</p>
                    <div class="nx-bullets">
                        <div class="nx-bullet"><i class="las la-users"></i> @lang('5 Active Referrals unlock higher returns')</div>
                        <div class="nx-bullet"><i class="las la-infinity"></i> @lang('Unlimited referrals, unlimited earning')</div>
                    </div>
                    <div class="nx-reflabel">@lang('Your Referral Link')</div>
                    @auth
                        <div class="nx-refbox">
                            <input type="text" id="referralURL" value="{{ route('home') }}?reference={{ auth()->user()->username }}" readonly>
                            <a href="#0" class="nx-btn nx-btn--solid copyBoard" id="copyBoard">@lang('Share Now') <i class="las la-share-alt"></i></a>
                        </div>
                    @else
                        <div class="nx-refbox">
                            <input type="text" value="{{ route('home') }}?reference={{ __('USERNAME') }}" readonly disabled>
                            <a href="#0" class="nx-btn nx-btn--solid" data-bs-toggle="modal" data-bs-target="#registerModal">
                                @lang('Get Your Link') <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
            <div class="col-lg-6" data-reveal>
                <div class="nx-panel text-center">
                    <div class="nx-eyebrow justify-content-center">@lang('Spin & Win')</div>
                    <div class="nx-spinwheel mt-3">
                        <div class="nx-spinwheel__hub"></div>
                    </div>
                    <div class="nx-bullets text-start">
                        <div class="nx-bullet"><i class="las la-gift"></i> @lang('Earn free spins as your referral network grows')</div>
                        <div class="nx-bullet"><i class="las la-dharmachakra"></i> @lang('Every spin always wins a reward')</div>
                    </div>
                    @auth
                        <a href="{{ route('user.spin') }}" class="nx-btn nx-btn--solid mt-3">@lang('Spin Now') <i class="las la-arrow-right"></i></a>
                    @else
                        <a href="#0" class="nx-btn nx-btn--solid mt-3" data-bs-toggle="modal" data-bs-target="#loginModal">@lang('Login to Spin') <i class="las la-arrow-right"></i></a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

@auth
    @push('script')
        <script>
            (function($) {
                "use strict";
                $('.copyBoard').on('click', function(e) {
                    e.preventDefault();
                    var copyText = document.getElementById('referralURL');
                    copyText.select();
                    copyText.setSelectionRange(0, 99999);
                    document.execCommand('copy');
                    notify('success', 'Copied: ' + copyText.value);
                });
            })(jQuery);
        </script>
    @endpush
@endauth

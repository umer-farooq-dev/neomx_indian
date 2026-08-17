@php
    $aboutUs = getContent('about.content', true);
    $aboutElement = getContent('about.element', null, false, true);
@endphp

<section class="nx-section" id="about">
    <div class="nx-container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-5" data-reveal>
                <div class="nx-eyebrow">@lang('About Us')</div>
                <h2 class="nx-title">{{ __(@$aboutUs->data_values->heading) }}</h2>
                <p class="nx-sub">{{ __(@$aboutUs->data_values->subheading) }}</p>
            </div>
            <div class="col-lg-7" data-reveal>
                <div class="row gy-3">
                    @foreach ($aboutElement as $about)
                        <div class="col-sm-6">
                            <div class="nx-about-card">
                                <div class="nx-about-card__icon">@php echo $about->data_values->icon; @endphp</div>
                                <div class="nx-about-card__title">{{ __($about->data_values->title) }}</div>
                                <div class="nx-about-card__text">{{ __($about->data_values->description) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

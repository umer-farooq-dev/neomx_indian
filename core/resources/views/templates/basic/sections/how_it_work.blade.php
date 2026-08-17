@php
    $howToWork = getContent('how_it_work.content', true);
    $workElement = getContent('how_it_work.element', false, null, true);
@endphp

<!-- how work section start -->
<section class="pt-100 pb-100 border-top section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __(@$howToWork->data_values->heading) }}</h2>
                    <p class="mt-3">{{ __(@$howToWork->data_values->subheading) }}</p>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row gy-4">
            @foreach ($workElement as $work)
                <div class="col-lg-3 col-sm-6 how-work-item wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.9s">
                    <div class="how-work-card">
                        <div class="how-work-card__step text--base text-shadow--base">{{ $loop->iteration }}</div>
                        <h3 class="title mt-4">{{ __($work->data_values->title) }}</h3>
                        <p class="mt-2">{{ __($work->data_values->description) }}</p>
                    </div><!-- how-work-card end -->
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- how work section end -->

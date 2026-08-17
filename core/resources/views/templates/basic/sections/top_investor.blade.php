@php
    $topInvestor = getContent('top_investor.content', true);
    $investorElement = getContent('top_investor.element', null, false, true);
@endphp

<!-- latest member section start -->
<section class="pt-50 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-header">
                    <h2 class="section-title">{{ __(@$topInvestor->data_values->heading) }}</h2>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row justify-content-center gy-4">
            @foreach ($investorElement as $investor)
                <div class="col-xl-3 col-lg-4 col-sm-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.7s">
                    <div class="member-card rounded-3">
                        <div class="member-card__thumb">
                            <img src="{{ getImage('assets/images/frontend/top_investor/' . @$investor->data_values->image, '300x250') }}"
                                alt="image">
                        </div>
                        <div class="member-card__content">
                            <h5 class="name">{{ __(@$investor->data_values->name) }}</h5>
                            <p class="fs--14px text--base mt-1">{{ __(@$investor->data_values->country) }}</p>
                            <p class="fs--14px text-white-50 mt-1">{{ @$investor->data_values->date }}</p>
                        </div>
                    </div><!-- member-card end -->
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- latest member section end -->

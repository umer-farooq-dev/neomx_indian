@php
    $testimonial = getContent('testimonial.content', true);
    $testimonialElement = getContent('testimonial.element', null, false, true);
@endphp

<!-- testimonial section start -->
<section class="pt-50 pb-50">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __(@$testimonial->data_values->heading) }}</h2>
                    <p class="mt-3">{{ __(@$testimonial->data_values->subheading) }}</p>
                </div>
            </div>
        </div><!-- row end -->
        <div class="testimonial-slider">

            @foreach ($testimonialElement as $item)
                <div class="single-slide">
                    <div class="testimonial-item rounded-3">
                        <div class="ratings">

                            @for ($i = 0; $i < @$item->data_values->number_of_star; $i++)
                                <i class="las la-star"></i>
                            @endfor

                        </div>
                        <p class="mt-2 text-white">{{ __(@$item->data_values->comment) }}</p>
                        <div class="client-details d-flex align-items-center mt-4">
                            <div class="thumb">
                                <img src="{{ getImage('assets/images/frontend/testimonial/' . @$item->data_values->image, '128x128') }}"
                                    alt="image">
                            </div>
                            <div class="content">
                                <h4 class="name text-white">{{ __(@$singleData->data_values->name) }}</h4>
                                <span class="designation text-white-50 fs--14px">
                                    {{ __(@$singleData->data_values->designation) }}
                                </span>
                            </div>
                        </div>
                    </div><!-- testimonial-item end -->
                </div><!-- single-slide end -->
            @endforeach

        </div><!-- testimonial-slider end -->
    </div>
</section>
<!-- testimonial section end -->

@push('style-lib')
    <link href="{{ asset($activeTemplateTrue . 'css/slick.css') }}" rel="stylesheet">
@endpush
@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict"

            /* ==============================
            					slider area
                    ================================= */

            // testimonial-slider
            $('.testimonial-slider').slick({
                autoplay: false,
                autoplaySpeed: 2000,
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 3,
                arrows: false,
                slidesToScroll: 1,
                responsive: [{
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        })(jQuery)
    </script>
@endpush

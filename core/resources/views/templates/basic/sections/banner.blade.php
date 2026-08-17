@php
    $banner = getContent('banner.content', true);
@endphp

<!-- hero section start -->

<section class="hero bg_img"
    style="background-image: url('{{ getImage('assets/images/frontend/banner/' . @$banner->data_values->image, '1920x1280') }}');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-8 col-xl-8 col-lg-10 text-center">
                <h2 class="hero__title wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">{{ __(@$banner->data_values->heading) }}</h2>
                <p class="hero__description mt-3 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
                    {{ __(@$banner->data_values->subheading) }}</p>

                @auth
                    <a href="{{ route('user.home') }}" class="btn btn--base btn--capsule mt-4 wow fadeInUp" data-wow-duration="0.5s"
                        data-wow-delay="0.7s">@lang('Get Started')</a>
                @else
                    <a href="#0" class="btn btn--base btn--capsule mt-4 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.7s"
                        data-bs-toggle="modal" data-bs-target="#registerModal">@lang('Get Started')</a>
                @endauth
            </div>
        </div>
    </div>
</section>

<!-- hero section end -->

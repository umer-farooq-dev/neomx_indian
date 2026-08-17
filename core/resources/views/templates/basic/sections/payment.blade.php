@php
    $payment = getContent('payment.content', true);
    $paymentElement = getContent('payment.element');
@endphp

<!-- payment brand section start -->
<section class="pt-50 pb-100" id="gateway">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="section-header">
                    <div class="section-top-title">@lang('Payment Gateway')</div>
                    <h2 class="section-title">{{ __($payment->data_values->heading) }}</h2>
                    <p class="mt-3">{{ __($payment->data_values->subheading) }}</p>
                </div>
            </div>
        </div><!-- row end -->

        <div class="row justify-content-center">
            <div class="col-lg-12 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                <div class="subscribe-wrapper bg_img" data-background="">
                    <div class="row align-items-center">

                        @foreach ($paymentElement as $item)
                            <div class="col-lg-2 gy-3 counter-item">
                                <h4 class="caption text--base">{{ __($item->data_values->gateway_name) }}</h4>
                                <h4 class="counter-item__number text--base">{{ __($item->data_values->amount) }}</h4>
                                <p class="caption">{{ __($item->data_values->title) }}</p>
                            </div>
                        @endforeach

                    </div>
                </div><!-- subscribe-wrapper end -->
            </div>
        </div>
    </div>
</section>
<!-- payment brand section end -->

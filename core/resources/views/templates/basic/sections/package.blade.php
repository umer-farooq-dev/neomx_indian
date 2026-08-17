@php
    $package = getContent('package.content', true);
@endphp

<!-- latest package section start -->
<section class="pt-100 pb-100" id="plan">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-header">
                    <h2 class="section-title pb-4">{{ __(@$package->data_values->heading) }}</h2>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row justify-content-center mb-none-70">

            <!-- Here Attach Plans cardfrom view partial blade  -->
            @include($activeTemplate.'partials.plans_card')

        </div><!-- row end -->
    </div>

</section>
<!-- latest package section end -->

<x-plan-modal />

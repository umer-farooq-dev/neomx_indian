@php
    $faq = getContent('faq.content', true);
    $faqElement = getContent('faq.element', null, false, true);
@endphp

<!-- faq section start -->
<section class="pt-100 pb-50" id="faq">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __(@$faq->data_values->heading) }}</h2>
                </div>
            </div>
        </div>
        <div class="row align-items-center justify-content-between gy-4">
            <div class="accordion custom--accordion" id="faqAccordion">
                <div class="row gy-4">

                    @foreach ($faqElement as $item)
                        <div class="col-lg-6">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="h-{{ $item->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#c-{{ $item->id }}" aria-expanded="false" aria-controls="c-{{ $item->id }}">
                                        {{ __($item->data_values->question) }}
                                    </button>
                                </h2>
                                <div id="c-{{ $item->id }}" class="accordion-collapse collapse" aria-labelledby="h-{{ $item->id }}"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>@php echo  __($item->data_values->answer) @endphp</p>
                                    </div>
                                </div>
                            </div><!-- accordion-item-->
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>
<!-- faq section end -->

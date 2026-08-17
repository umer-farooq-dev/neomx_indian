@php
    $faq = getContent('faq.content', true);
    $faqElement = getContent('faq.element', null, false, true);
@endphp

<section class="nx-section" id="faq">
    <div class="nx-container">
        <div class="nx-head" data-reveal>
            <div class="nx-eyebrow justify-content-center">@lang('FAQ')</div>
            <h2 class="nx-title">{{ __(@$faq->data_values->heading) }}</h2>
        </div>
        <div class="row gy-4">
            <div class="col-lg-8" data-reveal>
                <div class="accordion nx-faq" id="nxFaqAccordion">
                    <div class="row gy-3">
                        @foreach ($faqElement as $item)
                            <div class="col-md-6">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="nx-h-{{ $item->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#nx-c-{{ $item->id }}" aria-expanded="false" aria-controls="nx-c-{{ $item->id }}">
                                            {{ __($item->data_values->question) }}
                                        </button>
                                    </h2>
                                    <div id="nx-c-{{ $item->id }}" class="accordion-collapse collapse" aria-labelledby="nx-h-{{ $item->id }}"
                                        data-bs-parent="#nxFaqAccordion">
                                        <div class="accordion-body">
                                            <p>@php echo __($item->data_values->answer) @endphp</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-reveal>
                <div class="nx-support-card">
                    <i class="las la-headset"></i>
                    <div class="nx-panel__title" style="font-size:1.1rem">@lang('Still have questions?')</div>
                    <p class="nx-panel__desc">@lang('Our support team is here to help.')</p>
                    <a href="{{ auth()->check() ? route('ticket.open') : route('contact') }}" class="nx-btn nx-btn--outline mt-3">@lang('Contact Support')</a>
                </div>
            </div>
        </div>
    </div>
</section>

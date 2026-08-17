@php
    $howToWork = getContent('how_it_work.content', true);
    $workElement = getContent('how_it_work.element', false, null, true);
    $stepIcons = ['las la-user-plus', 'las la-user-shield', 'las la-project-diagram', 'las la-chart-line', 'la la-hand-holding-usd'];
@endphp

<section class="nx-section" id="how-it-works">
    <div class="nx-container">
        <div class="nx-head" data-reveal>
            <div class="nx-eyebrow justify-content-center">@lang('How It Works')</div>
            <h2 class="nx-title">{{ __(@$howToWork->data_values->heading) }}</h2>
            <p class="nx-sub">{{ __(@$howToWork->data_values->subheading) }}</p>
        </div>

        <div class="nx-panel" data-reveal>
            <div class="nx-steps">
                @foreach ($workElement as $work)
                    <div class="nx-step">
                        <div class="nx-step__icon"><i class="{{ $stepIcons[$loop->index % count($stepIcons)] }}"></i></div>
                        <div class="nx-step__text">
                            <div class="nx-step__title">{{ $loop->iteration }}. {{ __($work->data_values->title) }}</div>
                            <div class="nx-step__sub">{{ __($work->data_values->description) }}</div>
                        </div>
                        @if (!$loop->last)
                            <i class="las la-long-arrow-alt-right nx-step__arrow"></i>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

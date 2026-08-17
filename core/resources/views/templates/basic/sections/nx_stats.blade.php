@php
    $overviews = getContent('overview.element', null, false, true);
@endphp

<div class="nx-stats">
    <div class="nx-container">
        <div class="nx-stats__row" data-reveal>
            @foreach ($overviews as $overview)
                <div class="nx-stat">
                    <div class="nx-stat__icon">@php echo $overview->data_values->icon; @endphp</div>
                    <div>
                        <div class="nx-stat__value">{{ $overview->data_values->text }}</div>
                        <div class="nx-stat__label">{{ __($overview->data_values->title) }}</div>
                    </div>
                </div>
            @endforeach
            <div class="nx-stat">
                <div class="nx-stat__icon"><i class="las la-shield-alt"></i></div>
                <div>
                    <div class="nx-stat__value">100%</div>
                    <div class="nx-stat__label">@lang('Secure Platform')</div>
                </div>
            </div>
        </div>
    </div>
</div>

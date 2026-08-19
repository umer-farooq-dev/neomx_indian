{{--
    Phone-sized hero artwork. The desktop scene puts the brand badge front and
    centre; on a phone the product itself is the stronger image, so this one
    shows the app running on a handset, on the same lit platform, with the coin
    stacks and rising arrow carried over so both screens read as one family.
--}}
@php
    $mark = Str::upper(Str::substr(gs('site_name'), 0, 1));
@endphp

<svg class="nx-scene-mob" viewBox="76 4 248 332" role="img"
    aria-label="{{ __('The :site app on a phone', ['site' => gs('site_name')]) }}">
    <defs>
        <radialGradient id="mAmbient" cx="50%" cy="45%" r="58%">
            <stop offset="0%" stop-color="#17e0c3" stop-opacity=".28" />
            <stop offset="60%" stop-color="#2f8fff" stop-opacity=".10" />
            <stop offset="100%" stop-color="#2f8fff" stop-opacity="0" />
        </radialGradient>

        <linearGradient id="mBody" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#31435c" />
            <stop offset="50%" stop-color="#16202f" />
            <stop offset="100%" stop-color="#2b3b52" />
        </linearGradient>

        <linearGradient id="mScreen" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#0f2033" />
            <stop offset="100%" stop-color="#081522" />
        </linearGradient>

        <linearGradient id="mBar" x1="0" y1="1" x2="0" y2="0">
            <stop offset="0%" stop-color="#1477d4" />
            <stop offset="100%" stop-color="#7ef7e4" />
        </linearGradient>

        <linearGradient id="mCta" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#17e0c3" />
            <stop offset="100%" stop-color="#2f8fff" />
        </linearGradient>

        <linearGradient id="mCoinTop" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#fff3c4" />
            <stop offset="100%" stop-color="#e3ac35" />
        </linearGradient>

        <linearGradient id="mCoinSide" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#e0a933" />
            <stop offset="100%" stop-color="#a9761b" />
        </linearGradient>

        <linearGradient id="mArrow" x1="0" y1="1" x2="1" y2="0">
            <stop offset="0%" stop-color="#f5d061" stop-opacity=".3" />
            <stop offset="60%" stop-color="#ffd76a" />
            <stop offset="100%" stop-color="#fff3c4" />
        </linearGradient>

        <linearGradient id="mPodium" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#0d3a6b" stop-opacity="0" />
            <stop offset="50%" stop-color="#2f8fff" stop-opacity=".55" />
            <stop offset="100%" stop-color="#0d3a6b" stop-opacity="0" />
        </linearGradient>

        <filter id="mGlow" x="-60%" y="-60%" width="220%" height="220%">
            <feGaussianBlur stdDeviation="7" result="b" />
            <feMerge>
                <feMergeNode in="b" />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>

        <clipPath id="mScreenClip">
            <rect x="127" y="34" width="126" height="228" rx="12" />
        </clipPath>
    </defs>

    <ellipse cx="190" cy="160" rx="185" ry="150" fill="url(#mAmbient)" />

    <!-- platform -->
    <g>
        <ellipse cx="190" cy="288" rx="132" ry="30" fill="#0a1a30" opacity=".85" />
        <ellipse cx="190" cy="288" rx="132" ry="30" fill="none" stroke="url(#mPodium)" stroke-width="2" />
        <ellipse cx="190" cy="281" rx="96" ry="21" fill="#102544" opacity=".9" />
        <ellipse cx="190" cy="281" rx="96" ry="21" fill="none" stroke="url(#mPodium)" stroke-width="1.5" />
        <ellipse cx="190" cy="276" rx="62" ry="13" fill="#16345e" opacity=".95" />
        <ellipse cx="190" cy="276" rx="62" ry="13" fill="none" stroke="#2f8fff" stroke-opacity=".5" stroke-width="1.4" />
    </g>

    <!-- handset -->
    <g class="nx-scene-mob__phone">
        <rect x="119" y="26" width="142" height="244" rx="22" fill="url(#mBody)" />
        <rect x="119" y="26" width="142" height="244" rx="22" fill="none" stroke="#17e0c3" stroke-opacity=".45"
            stroke-width="1.5" filter="url(#mGlow)" />
        <rect x="127" y="34" width="126" height="228" rx="12" fill="url(#mScreen)" />
        <rect x="176" y="30" width="28" height="5" rx="2.5" fill="#0b1420" />

        <g clip-path="url(#mScreenClip)">
            <!-- app header -->
            <circle cx="141" cy="49" r="7" fill="#0f3b46" stroke="#17e0c3" stroke-opacity=".6" />
            <text x="141" y="52.5" text-anchor="middle" font-size="8" font-weight="800"
                font-family="'Plus Jakarta Sans',Arial,sans-serif" fill="#17e0c3">{{ $mark }}</text>
            <text x="153" y="52" font-size="8.5" font-weight="700"
                font-family="'Plus Jakarta Sans',Arial,sans-serif" fill="#eef3fb">{{ Str::upper(gs('site_name')) }}</text>

            <!-- balance -->
            <text x="137" y="72" font-size="6.5" font-family="Arial,sans-serif" fill="#8fa3bb">@lang('Total Earnings')</text>
            <text x="137" y="87" font-size="15" font-weight="800"
                font-family="'Plus Jakarta Sans',Arial,sans-serif" fill="#ffffff">{{ gs('cur_sym') }}12,450</text>

            <!-- chart -->
            <rect x="133" y="96" width="114" height="72" rx="8" fill="#0d1c2e" />
            <g>
                <rect x="141" y="140" width="11" height="20" rx="2.5" fill="url(#mBar)" />
                <rect x="158" y="128" width="11" height="32" rx="2.5" fill="url(#mBar)" />
                <rect x="175" y="132" width="11" height="28" rx="2.5" fill="url(#mBar)" />
                <rect x="192" y="116" width="11" height="44" rx="2.5" fill="url(#mBar)" />
                <rect x="209" y="106" width="11" height="54" rx="2.5" fill="url(#mBar)" />
                <rect x="226" y="112" width="11" height="48" rx="2.5" fill="url(#mBar)" />
            </g>
            <polyline points="146,138 163,126 180,130 197,114 214,104 231,110" fill="none" stroke="#7ef7e4"
                stroke-width="1.6" stroke-linecap="round" opacity=".9" />

            <!-- rows -->
            <rect x="133" y="176" width="114" height="9" rx="4.5" fill="#132538" />
            <rect x="133" y="176" width="72" height="9" rx="4.5" fill="#1b3c53" />
            <rect x="133" y="191" width="114" height="9" rx="4.5" fill="#132538" />
            <rect x="133" y="191" width="52" height="9" rx="4.5" fill="#1b3c53" />

            <!-- actions -->
            <rect x="133" y="212" width="54" height="20" rx="7" fill="url(#mCta)" />
            <text x="160" y="225.5" text-anchor="middle" font-size="7.5" font-weight="700"
                font-family="Arial,sans-serif" fill="#04211c">@lang('Deposit')</text>
            <rect x="193" y="212" width="54" height="20" rx="7" fill="none" stroke="#2f8fff" stroke-opacity=".7" />
            <text x="220" y="225.5" text-anchor="middle" font-size="7.5" font-weight="700"
                font-family="Arial,sans-serif" fill="#9dd0ff">@lang('Withdraw')</text>
        </g>
    </g>

    <!-- rising arrow -->
    <g class="nx-scene-mob__arrow">
        <path d="M266 212 C 288 194, 302 158, 312 108" fill="none" stroke="url(#mArrow)" stroke-width="5"
            stroke-linecap="round" filter="url(#mGlow)" />
        <path d="M298 100 L 320 92 L 316 118 Z" fill="#ffd76a" filter="url(#mGlow)" />
    </g>

    <!-- coins -->
    <g class="nx-scene-mob__coins">
        @php
            $stacks = [
                ['x' => 101, 'y' => 258, 'n' => 4, 'rx' => 20],
                ['x' => 285, 'y' => 264, 'n' => 3, 'rx' => 18],
            ];
        @endphp
        @foreach ($stacks as $s)
            @for ($i = 0; $i < $s['n']; $i++)
                @php $cy = $s['y'] - $i * 8; @endphp
                <ellipse cx="{{ $s['x'] }}" cy="{{ $cy }}" rx="{{ $s['rx'] }}" ry="{{ $s['rx'] * 0.42 }}"
                    fill="url(#mCoinSide)" />
                <ellipse cx="{{ $s['x'] }}" cy="{{ $cy - 3.5 }}" rx="{{ $s['rx'] }}" ry="{{ $s['rx'] * 0.42 }}"
                    fill="url(#mCoinTop)" />
            @endfor
        @endforeach
    </g>
</svg>

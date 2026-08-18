{{--
    Hero artwork, drawn as inline SVG so it stays crisp at any size and needs no
    external asset. The brand initial is extruded by stacking offset copies of
    the glyph, which is what gives it the moulded 3D face.
--}}
@php
    $mark = Str::upper(Str::substr(gs('site_name'), 0, 1));
@endphp

<svg class="nx-scene" viewBox="0 0 560 460" role="img" aria-label="{{ __('Grow your investment with :site', ['site' => gs('site_name')]) }}">
    <defs>
        <radialGradient id="nxAmbient" cx="50%" cy="45%" r="55%">
            <stop offset="0%" stop-color="#17e0c3" stop-opacity=".30" />
            <stop offset="55%" stop-color="#2f8fff" stop-opacity=".12" />
            <stop offset="100%" stop-color="#2f8fff" stop-opacity="0" />
        </radialGradient>

        <radialGradient id="nxGlass" cx="36%" cy="28%" r="78%">
            <stop offset="0%" stop-color="#2fd8ff" stop-opacity=".42" />
            <stop offset="45%" stop-color="#1b4a86" stop-opacity=".38" />
            <stop offset="100%" stop-color="#07172c" stop-opacity=".85" />
        </radialGradient>

        <linearGradient id="nxRim" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#7ef7e4" />
            <stop offset="45%" stop-color="#2f8fff" stop-opacity=".55" />
            <stop offset="100%" stop-color="#17e0c3" stop-opacity=".2" />
        </linearGradient>

        <linearGradient id="nxSpec" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#ffffff" stop-opacity=".55" />
            <stop offset="100%" stop-color="#ffffff" stop-opacity="0" />
        </linearGradient>

        <linearGradient id="nxMarkFace" x1="0" y1="0" x2="0.35" y2="1">
            <stop offset="0%" stop-color="#ffffff" />
            <stop offset="35%" stop-color="#7ef7e4" />
            <stop offset="100%" stop-color="#1e9fe0" />
        </linearGradient>

        <linearGradient id="nxBar" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#7ef7e4" />
            <stop offset="100%" stop-color="#1477d4" />
        </linearGradient>

        <linearGradient id="nxBarTop" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#d3fff6" />
            <stop offset="100%" stop-color="#69e6d6" />
        </linearGradient>

        <linearGradient id="nxArrow" x1="0" y1="1" x2="1" y2="0">
            <stop offset="0%" stop-color="#f5d061" stop-opacity=".35" />
            <stop offset="55%" stop-color="#ffd76a" />
            <stop offset="100%" stop-color="#fff3c4" />
        </linearGradient>

        <linearGradient id="nxCoinTop" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#fff3c4" />
            <stop offset="100%" stop-color="#e3ac35" />
        </linearGradient>

        <linearGradient id="nxCoinSide" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#e0a933" />
            <stop offset="100%" stop-color="#a9761b" />
        </linearGradient>

        <linearGradient id="nxPodium" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#0d3a6b" stop-opacity="0" />
            <stop offset="50%" stop-color="#2f8fff" stop-opacity=".55" />
            <stop offset="100%" stop-color="#0d3a6b" stop-opacity="0" />
        </linearGradient>

        <filter id="nxGlow" x="-60%" y="-60%" width="220%" height="220%">
            <feGaussianBlur stdDeviation="10" result="b" />
            <feMerge>
                <feMergeNode in="b" />
                <feMergeNode in="SourceGraphic" />
            </feMerge>
        </filter>

        <filter id="nxSoft" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur stdDeviation="5" />
        </filter>
    </defs>

    <!-- ambient light -->
    <ellipse cx="245" cy="215" rx="235" ry="200" fill="url(#nxAmbient)" />

    <!-- podium -->
    <g class="nx-scene__podium">
        <ellipse cx="228" cy="352" rx="168" ry="38" fill="#0a1a30" opacity=".85" />
        <ellipse cx="228" cy="352" rx="168" ry="38" fill="none" stroke="url(#nxPodium)" stroke-width="2" />
        <ellipse cx="228" cy="344" rx="124" ry="27" fill="#102544" opacity=".9" />
        <ellipse cx="228" cy="344" rx="124" ry="27" fill="none" stroke="url(#nxPodium)" stroke-width="1.5" />
        <ellipse cx="228" cy="337" rx="84" ry="18" fill="#16345e" opacity=".95" />
        <ellipse cx="228" cy="337" rx="84" ry="18" fill="none" stroke="#2f8fff" stroke-opacity=".5" stroke-width="1.5" />
        <ellipse cx="228" cy="333" rx="52" ry="11" fill="#2f8fff" opacity=".22" filter="url(#nxSoft)" />
    </g>

    <!-- floating glass badge -->
    <g class="nx-scene__badge">
        <!-- outer halo rings -->
        <circle cx="228" cy="196" r="140" fill="none" stroke="#17e0c3" stroke-opacity=".13" stroke-width="1" />
        <circle cx="228" cy="196" r="124" fill="none" stroke="#2f8fff" stroke-opacity=".16" stroke-width="1"
            stroke-dasharray="3 8" class="nx-scene__ring" />

        <!-- glass disc -->
        <circle cx="228" cy="196" r="106" fill="url(#nxGlass)" />
        <circle cx="228" cy="196" r="106" fill="none" stroke="url(#nxRim)" stroke-width="2.5" filter="url(#nxGlow)" />
        <circle cx="228" cy="196" r="96" fill="none" stroke="#ffffff" stroke-opacity=".08" stroke-width="1" />

        <!-- specular sweep -->
        <ellipse cx="192" cy="140" rx="58" ry="30" fill="url(#nxSpec)" transform="rotate(-26 192 140)" opacity=".7" />

        <!-- extruded brand mark: stacked offsets build the side wall -->
        <g class="nx-scene__mark">
            @for ($i = 9; $i >= 1; $i--)
                <text x="{{ 228 + $i * 1.6 }}" y="{{ 232 + $i * 1.6 }}" text-anchor="middle"
                    font-size="118" font-weight="800" font-family="'Plus Jakarta Sans','Exo 2',Arial,sans-serif"
                    fill="#0f4f7d" fill-opacity=".9">{{ $mark }}</text>
            @endfor
            <text x="228" y="232" text-anchor="middle" font-size="118" font-weight="800"
                font-family="'Plus Jakarta Sans','Exo 2',Arial,sans-serif" fill="url(#nxMarkFace)">{{ $mark }}</text>
        </g>
    </g>

    <!-- rising chart -->
    <g class="nx-scene__chart">
        @php
            $bars = [
                ['x' => 372, 'h' => 66],
                ['x' => 404, 'h' => 96],
                ['x' => 436, 'h' => 132],
                ['x' => 468, 'h' => 172],
                ['x' => 500, 'h' => 214],
            ];
            $baseY = 322;
            $bw = 24;
        @endphp
        @foreach ($bars as $i => $bar)
            @php $topY = $baseY - $bar['h']; @endphp
            <g class="nx-scene__bar" style="--d: {{ $i * 0.12 }}s">
                <rect x="{{ $bar['x'] }}" y="{{ $topY }}" width="{{ $bw }}" height="{{ $bar['h'] }}" rx="4"
                    fill="url(#nxBar)" />
                <ellipse cx="{{ $bar['x'] + $bw / 2 }}" cy="{{ $topY }}" rx="{{ $bw / 2 }}" ry="5"
                    fill="url(#nxBarTop)" />
                <rect x="{{ $bar['x'] }}" y="{{ $topY }}" width="6" height="{{ $bar['h'] }}" rx="3"
                    fill="#ffffff" fill-opacity=".18" />
            </g>
        @endforeach
        <ellipse cx="446" cy="326" rx="105" ry="12" fill="#2f8fff" opacity=".16" filter="url(#nxSoft)" />
    </g>

    <!-- growth arrow -->
    <g class="nx-scene__arrow">
        <path d="M352 214 C 400 200, 448 168, 492 96" fill="none" stroke="url(#nxArrow)" stroke-width="6"
            stroke-linecap="round" filter="url(#nxGlow)" />
        <path d="M470 84 L 502 78 L 494 110 Z" fill="#ffd76a" filter="url(#nxGlow)" />
    </g>

    <!-- coin stacks -->
    <g class="nx-scene__coins">
        @php
            $stacks = [
                ['x' => 372, 'y' => 330, 'n' => 5, 'rx' => 26],
                ['x' => 424, 'y' => 342, 'n' => 3, 'rx' => 23],
                ['x' => 336, 'y' => 348, 'n' => 2, 'rx' => 21],
            ];
        @endphp
        @foreach ($stacks as $s)
            @php $th = 9; @endphp
            @for ($i = 0; $i < $s['n']; $i++)
                @php $cy = $s['y'] - $i * $th; @endphp
                <ellipse cx="{{ $s['x'] }}" cy="{{ $cy }}" rx="{{ $s['rx'] }}" ry="{{ $s['rx'] * 0.42 }}"
                    fill="url(#nxCoinSide)" />
                <ellipse cx="{{ $s['x'] }}" cy="{{ $cy - 4 }}" rx="{{ $s['rx'] }}" ry="{{ $s['rx'] * 0.42 }}"
                    fill="url(#nxCoinTop)" />
            @endfor
            <ellipse cx="{{ $s['x'] }}" cy="{{ $s['y'] - ($s['n'] - 1) * 9 - 4 }}" rx="{{ $s['rx'] * 0.45 }}"
                ry="{{ $s['rx'] * 0.19 }}" fill="#ffffff" fill-opacity=".35" />
        @endforeach
    </g>
</svg>

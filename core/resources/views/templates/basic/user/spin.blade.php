@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="py-5">
        <div class="container">

            <div class="row gy-4 mb-4">
                <div class="col-lg-4 col-sm-6">
                    <div class="dashboard-card h-100">
                        <span>@lang('Available Spins')</span>
                        <h3 class="number spin-available-count">{{ $availableSpins }}</h3>
                        <i class="las la-dharmachakra icon"></i>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a class="d-block" href="{{ route('user.wallet', ['wallet_type' => 'reward']) }}">
                        <div class="dashboard-card h-100">
                            <span>@lang('Reward Wallet')</span>
                            <h3 class="number reward-wallet-balance">{{ gs('cur_sym') }}{{ showAmount(auth()->user()->reward_balance) }}</h3>
                            <i class="las la-wallet icon"></i>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="dashboard-card h-100">
                        <span>@lang('How To Get More Spins')</span>
                        <p class="mb-0 small">@lang('Refer active friends to unlock more spins automatically.')</p>
                        <i class="las la-user-friends icon"></i>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">

                    @if ($rewards->isEmpty())
                        <div class="alert alert--warning">@lang('Spin rewards are not set up yet. Please check back later.')</div>
                    @else
                        <div class="spin-wheel-wrap">
                            <div class="spin-pointer"></div>
                            <div class="spin-wheel-rotor" id="spinRotor">
                                <canvas id="spinCanvas" width="360" height="360"></canvas>
                            </div>
                            <div class="spin-hub">
                                <button type="button" id="spinBtn" class="spin-hub-btn" @if (!$availableSpins) disabled @endif>
                                    @lang('SPIN')
                                </button>
                            </div>
                        </div>

                        <p class="mt-4 text-white spin-hint">
                            @if ($availableSpins)
                                @lang('Tap SPIN to try your luck!')
                            @else
                                @lang('No spins left. Refer active friends to earn more.')
                            @endif
                        </p>
                    @endif

                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-12">
                    <h5 class="mb-3">@lang('Spin History')</h5>
                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Reward')</th>
                                    <th>@lang('Amount')</th>
                                </tr>
                            </thead>
                            <tbody id="spinHistoryBody">
                                @forelse($history as $spin)
                                    <tr>
                                        <td>{{ showDateTime($spin->used_at) }}</td>
                                        <td>{{ $spin->reward->name ?? '—' }}</td>
                                        <td>{{ showAmount($spin->won_amount) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($history->hasPages())
                        {{ paginateLinks($history) }}
                    @endif
                </div>
            </div>

        </div>
    </section>
@endsection

@push('style')
    <style>
        .spin-wheel-wrap {
            position: relative;
            width: 360px;
            max-width: 100%;
            margin: 0 auto;
        }

        .spin-wheel-rotor {
            width: 360px;
            height: 360px;
            max-width: 100%;
            border-radius: 50%;
            box-shadow:
                0 0 0 2px rgba(0,0,0,.35),
                0 0 0 10px #8a5a10,
                0 14px 45px rgba(0,0,0,.55);
            transition: transform 10s cubic-bezier(0.17, 0.67, 0.1, 0.99);
            will-change: transform;
        }

        .spin-wheel-rotor canvas {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        .spin-pointer {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            width: 34px;
            height: 34px;
            z-index: 5;
            filter: drop-shadow(0 3px 5px rgba(0,0,0,.5));
        }

        .spin-pointer::before {
            content: "";
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-top: 26px solid #F5D061;
        }

        .spin-pointer::after {
            content: "";
            position: absolute;
            left: 50%;
            top: 24px;
            transform: translateX(-50%);
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 30%, #FFF3C4, #C98A1A 75%);
            border: 1px solid #8a5a10;
        }

        .spin-hub {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 4;
        }

        .spin-hub-btn {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            border: 4px solid #8a5a10;
            background: radial-gradient(circle at 35% 30%, #FFF3C4, #F5D061 45%, #C98A1A 100%);
            color: #5c1414;
            font-weight: 800;
            letter-spacing: .05em;
            font-size: .95rem;
            box-shadow: 0 6px 18px rgba(0,0,0,.5), inset 0 0 0 2px rgba(255,255,255,.5);
            cursor: pointer;
            transition: transform .15s ease;
        }

        .spin-hub-btn:active:not(:disabled) {
            transform: scale(0.95);
        }

        .spin-hub-btn:disabled {
            background: #77726a;
            border-color: #4a453f;
            color: #d8d3c9;
            cursor: not-allowed;
            box-shadow: none;
        }

        .spin-hint {
            opacity: .85;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            const rewards = @json($rewards->values());
            const canvas = document.getElementById('spinCanvas');
            const ctx = canvas ? canvas.getContext('2d') : null;
            const rotor = document.getElementById('spinRotor');
            let currentRotation = 0;
            let spinning = false;

            // Fallback palette only used if a reward has no admin-set color; alternates for a casino look.
            const fallbackPalette = ['#7A1E1E', '#F3E4C4'];

            function isLightColor(hex) {
                if (!hex) return true;
                const c = hex.replace('#', '');
                const r = parseInt(c.substring(0, 2), 16);
                const g = parseInt(c.substring(2, 4), 16);
                const b = parseInt(c.substring(4, 6), 16);
                return (0.299 * r + 0.587 * g + 0.114 * b) > 150;
            }

            function formatSegmentLabel(amount) {
                const n = parseFloat(amount);
                return (n % 1 === 0) ? String(n) : n.toFixed(1);
            }

            function drawWheel() {
                if (!ctx || !rewards.length) return;
                const size = canvas.width;
                const cx = size / 2;
                const cy = size / 2;
                const outerRadius = size / 2 - 3;
                const segRadius = outerRadius - 14;
                const bulbRadius = outerRadius - 7;
                const segAngle = (2 * Math.PI) / rewards.length;

                ctx.clearRect(0, 0, size, size);

                // Gold rim (two-tone bevel)
                ctx.beginPath();
                ctx.arc(cx, cy, outerRadius, 0, Math.PI * 2);
                ctx.fillStyle = '#8a5a10';
                ctx.fill();
                ctx.beginPath();
                ctx.arc(cx, cy, outerRadius - 6, 0, Math.PI * 2);
                ctx.fillStyle = '#F5D061';
                ctx.fill();

                // Segments
                rewards.forEach(function(reward, i) {
                    const start = -Math.PI / 2 + i * segAngle; // start from top, clockwise
                    const end = start + segAngle;
                    const color = reward.color || fallbackPalette[i % 2];

                    ctx.beginPath();
                    ctx.moveTo(cx, cy);
                    ctx.arc(cx, cy, segRadius, start, end);
                    ctx.closePath();
                    ctx.fillStyle = color;
                    ctx.fill();
                    ctx.strokeStyle = 'rgba(245, 208, 97, .55)';
                    ctx.lineWidth = 2;
                    ctx.stroke();

                    // Number label
                    ctx.save();
                    ctx.translate(cx, cy);
                    ctx.rotate(start + segAngle / 2);
                    ctx.textAlign = 'right';
                    ctx.fillStyle = isLightColor(color) ? '#3a1414' : '#fff8e6';
                    ctx.font = '800 22px Arial, sans-serif';
                    ctx.shadowColor = 'rgba(0,0,0,.35)';
                    ctx.shadowBlur = 2;
                    ctx.fillText(formatSegmentLabel(reward.amount), segRadius - 18, 8);
                    ctx.restore();
                });

                ctx.shadowBlur = 0;

                // inner ring framing the segments
                ctx.beginPath();
                ctx.arc(cx, cy, segRadius, 0, Math.PI * 2);
                ctx.lineWidth = 3;
                ctx.strokeStyle = '#F5D061';
                ctx.stroke();

                // light bulbs around the rim
                const bulbCount = Math.max(rewards.length * 2, 12);
                for (let i = 0; i < bulbCount; i++) {
                    const angle = (i / bulbCount) * Math.PI * 2;
                    const bx = cx + Math.cos(angle) * bulbRadius;
                    const by = cy + Math.sin(angle) * bulbRadius;
                    const lit = i % 2 === 0;
                    ctx.beginPath();
                    ctx.arc(bx, by, 3.4, 0, Math.PI * 2);
                    ctx.fillStyle = lit ? '#FFF3B0' : '#C98A1A';
                    ctx.shadowColor = '#FFD76A';
                    ctx.shadowBlur = lit ? 7 : 0;
                    ctx.fill();
                }
                ctx.shadowBlur = 0;
            }

            drawWheel();

            $('#spinBtn').on('click', function() {
                if (spinning) return;
                spinning = true;
                $(this).prop('disabled', true);

                $.post('{{ route('user.spin.play') }}', {
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if (response.status !== 'success') {
                        spinning = false;
                        $('#spinBtn').prop('disabled', false);
                        notify('error', response.message);
                        return;
                    }

                    const segAngle = 360 / response.segments;
                    const targetCenter = response.index * segAngle + segAngle / 2;
                    const jitter = (Math.random() - 0.5) * (segAngle * 0.6);
                    const fullSpins = 12 * 360;

                    // rotate so the winning segment's center lands under the fixed top pointer
                    const delta = fullSpins + (360 - targetCenter) + jitter;
                    currentRotation += delta;

                    rotor.style.transform = `rotate(${currentRotation}deg)`;

                    setTimeout(function() {
                        spinning = false;
                        notify('success', '@lang("You won")' + ': ' + response.reward.name);

                        $('.spin-available-count').text(response.remaining_spins);
                        $('.reward-wallet-balance').text('{{ gs('cur_sym') }}' + response.reward_balance);

                        $('#spinHistoryBody').prepend(`
                            <tr>
                                <td>@lang('Just now')</td>
                                <td>${response.reward.name}</td>
                                <td>${response.reward.amount}</td>
                            </tr>
                        `);

                        if (response.remaining_spins > 0) {
                            $('#spinBtn').prop('disabled', false);
                            $('.spin-hint').text('@lang("Tap SPIN to try your luck!")');
                        } else {
                            $('.spin-hint').text('@lang("No spins left. Refer active friends to earn more.")');
                        }
                    }, 10300);
                }).fail(function() {
                    spinning = false;
                    $('#spinBtn').prop('disabled', false);
                    notify('error', 'Something went wrong');
                });
            });

        })(jQuery);
    </script>
@endpush

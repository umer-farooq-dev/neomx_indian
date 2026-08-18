{{--
    Shared canvas wheel. Renders the same segments the admin configured under
    Spin & Reward, so the marketing teaser on the homepage and the playable
    wheel in the dashboard can never drift apart.

    Props: $wheelRewards (collection), $wheelId, $wheelSize
--}}
@php
    $wheelId      = $wheelId ?? 'nxWheel';
    $wheelSize    = $wheelSize ?? 320;
    $wheelRewards = $wheelRewards ?? collect();
@endphp

<canvas id="{{ $wheelId }}" width="{{ $wheelSize }}" height="{{ $wheelSize }}"></canvas>

@once
    @push('script')
        <script>
            window.nxDrawWheel = function(canvasId, rewards) {
                const canvas = document.getElementById(canvasId);
                if (!canvas || !rewards || !rewards.length) return;

                const ctx = canvas.getContext('2d');
                const size = canvas.width;
                const cx = size / 2;
                const cy = size / 2;
                const outerRadius = size / 2 - 3;
                const segRadius = outerRadius - 14;
                const bulbRadius = outerRadius - 7;
                const segAngle = (2 * Math.PI) / rewards.length;
                const fallbackPalette = ['#7A1E1E', '#F3E4C4'];

                const isLightColor = (hex) => {
                    if (!hex) return true;
                    const c = hex.replace('#', '');
                    const r = parseInt(c.substring(0, 2), 16);
                    const g = parseInt(c.substring(2, 4), 16);
                    const b = parseInt(c.substring(4, 6), 16);
                    return (0.299 * r + 0.587 * g + 0.114 * b) > 150;
                };

                const label = (amount) => {
                    const n = parseFloat(amount);
                    return (n % 1 === 0) ? String(n) : n.toFixed(1);
                };

                ctx.clearRect(0, 0, size, size);

                // gold rim
                ctx.beginPath();
                ctx.arc(cx, cy, outerRadius, 0, Math.PI * 2);
                ctx.fillStyle = '#8a5a10';
                ctx.fill();
                ctx.beginPath();
                ctx.arc(cx, cy, outerRadius - 6, 0, Math.PI * 2);
                ctx.fillStyle = '#F5D061';
                ctx.fill();

                rewards.forEach(function(reward, i) {
                    const start = -Math.PI / 2 + i * segAngle;
                    const color = reward.color || fallbackPalette[i % 2];

                    ctx.beginPath();
                    ctx.moveTo(cx, cy);
                    ctx.arc(cx, cy, segRadius, start, start + segAngle);
                    ctx.closePath();
                    ctx.fillStyle = color;
                    ctx.fill();
                    ctx.strokeStyle = 'rgba(245, 208, 97, .55)';
                    ctx.lineWidth = 2;
                    ctx.stroke();

                    ctx.save();
                    ctx.translate(cx, cy);
                    ctx.rotate(start + segAngle / 2);
                    ctx.textAlign = 'right';
                    ctx.fillStyle = isLightColor(color) ? '#3a1414' : '#fff8e6';
                    ctx.font = '800 ' + Math.round(size / 16) + 'px Arial, sans-serif';
                    ctx.shadowColor = 'rgba(0,0,0,.35)';
                    ctx.shadowBlur = 2;
                    ctx.fillText(label(reward.amount), segRadius - 18, 8);
                    ctx.restore();
                });

                ctx.shadowBlur = 0;

                ctx.beginPath();
                ctx.arc(cx, cy, segRadius, 0, Math.PI * 2);
                ctx.lineWidth = 3;
                ctx.strokeStyle = '#F5D061';
                ctx.stroke();

                const bulbCount = Math.max(rewards.length * 2, 12);
                for (let i = 0; i < bulbCount; i++) {
                    const angle = (i / bulbCount) * Math.PI * 2;
                    ctx.beginPath();
                    ctx.arc(cx + Math.cos(angle) * bulbRadius, cy + Math.sin(angle) * bulbRadius, 3.4, 0, Math.PI * 2);
                    const lit = i % 2 === 0;
                    ctx.fillStyle = lit ? '#FFF3B0' : '#C98A1A';
                    ctx.shadowColor = '#FFD76A';
                    ctx.shadowBlur = lit ? 7 : 0;
                    ctx.fill();
                }
                ctx.shadowBlur = 0;
            };
        </script>
    @endpush
@endonce

@push('script')
    <script>
        nxDrawWheel(@json($wheelId), @json($wheelRewards->values()));
    </script>
@endpush

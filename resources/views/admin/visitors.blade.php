{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/admin/visitors.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Visitors | Admin Zone">
    <style>
        .visitor-kpis {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(157px, 1fr));
            gap: 11px;
            margin-bottom: 19px;
        }

        .visitor-kpi {
            padding: 11px;
            border-top: 1px solid rgba(30, 185, 197, .27);
        }

        .visitor-kpi small,
        .visitor-list small {
            display: block;
            font-size: .73rem;
        }

        .visitor-kpi strong {
            display: block;
            margin-top: 5px;
            font-size: 1.47rem;
        }

        .visitor-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(271px, .9fr);
            gap: 19px;
        }

        .visitor-chart {
            width: 100%;
            height: 273px;
        }

        .visitor-bars {
            display: grid;
            gap: 7px;
        }

        .visitor-bar {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 57px;
            gap: 11px;
            align-items: center;
            font-size: .81rem;
        }

        .visitor-bar span:first-child {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .visitor-bar meter {
            grid-column: 1 / -1;
            width: 100%;
            height: 7px;
            accent-color: var(--brand);
        }

        .visitor-list {
            display: grid;
            gap: 7px;
            max-height: 373px;
            overflow: auto;
            padding-right: 7px;
        }

        .visitor-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto auto;
            gap: 11px;
            align-items: center;
            padding-top: 7px;
            border-top: 1px solid rgba(22, 199, 101, .19);
            font-size: .79rem;
        }

        @media (max-width: 900px) {
            .visitor-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <h1 class="section-title" style="margin:0">Visitors</h1>
        <a class="btn" href="{{ route('admin.dashboard') }}" style="padding:5px 9px;font-size:.79rem">Dashboard</a>
    </div>

    <section class="panel" style="margin-bottom:19px">
        <div class="visitor-kpis">
            @foreach($kpis as $label => $value)
                <div class="visitor-kpi">
                    <small class="muted">{{ $label }}</small>
                    <strong>{{ number_format($value) }}</strong>
                </div>
            @endforeach
        </div>
    </section>

    <div class="visitor-grid">
        <section class="panel">
            <div class="row" style="justify-content:space-between;margin-bottom:11px">
                <h2 class="section-title" style="margin:0">Last 30 Days</h2>
                <span class="muted" style="font-size:.79rem">Page views and visitors</span>
            </div>
            <canvas class="visitor-chart" id="dailyVisitors" aria-label="Visitor chart"></canvas>
        </section>

        <section class="panel">
            <h2 class="section-title" style="margin-bottom:11px">Today By Hour</h2>
            <canvas class="visitor-chart" id="hourlyVisitors" aria-label="Hourly visitor chart"></canvas>
        </section>
    </div>

    <div class="visitor-grid" style="margin-top:19px">
        <section class="panel">
            <h2 class="section-title" style="margin-bottom:11px">Top Pages</h2>
            <div class="visitor-list">
                @forelse($topPages as $page)
                    <div class="visitor-row">
                        <strong style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $page->path }}</strong>
                        <span>{{ number_format($page->views) }}</span>
                        <span class="muted">{{ number_format($page->visitors) }}</span>
                    </div>
                @empty
                    <p class="muted">No visits recorded yet.</p>
                @endforelse
            </div>
        </section>

        <section class="panel">
            <h2 class="section-title" style="margin-bottom:11px">Referrers</h2>
            <div class="visitor-bars">
                @forelse($referrers as $row)
                    <div class="visitor-bar">
                        <span>{{ $row->label }}</span>
                        <strong>{{ number_format($row->total) }}</strong>
                        <meter min="0" max="{{ $referrerMax }}" value="{{ $row->total }}"></meter>
                    </div>
                @empty
                    <p class="muted">No referrers yet.</p>
                @endforelse
            </div>
        </section>
    </div>

    <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(237px,1fr));gap:19px;margin-top:19px">
        @foreach(['Devices' => $devices, 'Browsers' => $browsers, 'Platforms' => $platforms, 'Status Codes' => $statuses] as $title => $rows)
            <section class="panel">
                <h2 class="section-title" style="margin-bottom:11px">{{ $title }}</h2>
                <div class="visitor-bars">
                    @php($max = max(1, (int) $rows->max('total')))
                    @forelse($rows as $row)
                        <div class="visitor-bar">
                            <span>{{ $row->label }}</span>
                            <strong>{{ number_format($row->total) }}</strong>
                            <meter min="0" max="{{ $max }}" value="{{ $row->total }}"></meter>
                        </div>
                    @empty
                        <p class="muted">No data yet.</p>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>

    <section class="panel" style="margin-top:19px">
        <h2 class="section-title" style="margin-bottom:11px">Recent Visits</h2>
        <div class="visitor-list">
            @forelse($recent as $visit)
                <div class="visitor-row">
                    <div style="min-width:0">
                        <strong style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $visit->path }}</strong>
                        <small class="muted">{{ $visit->browser }} · {{ $visit->platform }} · {{ $visit->device_type }} · {{ $visit->created_at->diffForHumans() }}</small>
                    </div>
                    <span>{{ $visit->status_code }}</span>
                    <span class="muted">{{ $visit->duration_ms }}ms</span>
                </div>
            @empty
                <p class="muted">No visits recorded yet.</p>
            @endforelse
        </div>
    </section>

    <script>
        (() => {
            const daily = @json($dailyChart);
            const hourly = @json($hourlyChart);

            function drawLineChart(id, labels, views, visitors) {
                const canvas = document.getElementById(id);
                if (!canvas) return;
                const ctx = canvas.getContext('2d');
                const scale = window.devicePixelRatio || 1;
                const rect = canvas.getBoundingClientRect();
                canvas.width = Math.max(1, Math.floor(rect.width * scale));
                canvas.height = Math.max(1, Math.floor(rect.height * scale));
                ctx.scale(scale, scale);
                const width = rect.width;
                const height = rect.height;
                const pad = 29;
                const max = Math.max(1, ...views, ...visitors);

                ctx.clearRect(0, 0, width, height);
                ctx.strokeStyle = 'rgba(22,199,101,.19)';
                ctx.lineWidth = 1;
                for (let i = 0; i < 5; i++) {
                    const y = pad + ((height - pad * 2) / 4) * i;
                    ctx.beginPath();
                    ctx.moveTo(pad, y);
                    ctx.lineTo(width - pad, y);
                    ctx.stroke();
                }

                function plot(values, color) {
                    ctx.strokeStyle = color;
                    ctx.lineWidth = 3;
                    ctx.beginPath();
                    values.forEach((value, index) => {
                        const x = pad + ((width - pad * 2) / Math.max(1, values.length - 1)) * index;
                        const y = height - pad - ((height - pad * 2) * value / max);
                        index === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
                    });
                    ctx.stroke();
                }

                plot(views, '#16c765');
                plot(visitors, '#1eb9c5');
                ctx.fillStyle = getComputedStyle(document.documentElement).getPropertyValue('--muted') || '#6b7280';
                ctx.font = '11px system-ui';
                ctx.fillText(labels[0] || '', pad, height - 7);
                ctx.fillText(labels[labels.length - 1] || '', width - 73, height - 7);
            }

            drawLineChart('dailyVisitors', daily.labels, daily.views, daily.visitors);
            drawLineChart('hourlyVisitors', hourly.labels, hourly.views, hourly.visitors);
            window.addEventListener('resize', () => {
                drawLineChart('dailyVisitors', daily.labels, daily.views, daily.visitors);
                drawLineChart('hourlyVisitors', hourly.labels, hourly.views, hourly.visitors);
            });
        })();
    </script>
</x-layouts.app>

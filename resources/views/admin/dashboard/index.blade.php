@extends('admin.layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    :root {
        --primary: #22c55e;
        --secondary: #64748b;
        --bg-card: #ffffff;
        --bg-hover: #f8fafc;
        --border: #e2e8f0;
        --radius: 12px;
    }

    /* --- COMPACT GRID LAYOUT --- */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: auto auto auto;
        gap: 1rem;
        padding-bottom: 2rem;
    }

    /* Responsive Grid */
    @media (max-width: 1024px) {
        .dashboard-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .dashboard-grid { grid-template-columns: 1fr; }
    }

    /* --- BENTO CARD STYLE --- */
    .bento-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .bento-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    /* --- STATISTIC CARDS --- */
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }
    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .icon-users { background: #eff6ff; color: #3b82f6; }
    .icon-events { background: #f0fdf4; color: #22c55e; }
    .icon-tests { background: #fefce8; color: #eab308; }
    .icon-db { background: #fef2f2; color: #ef4444; }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }
    .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-link {
        font-size: 0.75rem;
        color: var(--secondary);
        text-decoration: none;
        margin-top: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .stat-link:hover { color: var(--primary); }

    /* --- CHART SECTION --- */
    .col-span-3 { grid-column: span 3; }
    .col-span-2 { grid-column: span 2; }

    @media (max-width: 1024px) { .col-span-3, .col-span-2 { grid-column: span 2; } }
    @media (max-width: 640px) { .col-span-3, .col-span-2 { grid-column: span 1; } }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .section-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* --- COMPACT TABLE --- */
    .compact-table { width: 100%; border-collapse: collapse; }
    .compact-table th {
        text-align: left;
        font-size: 0.7rem;
        color: var(--secondary);
        text-transform: uppercase;
        font-weight: 700;
        padding: 0.75rem 0.5rem;
        border-bottom: 1px solid var(--border);
    }
    .compact-table td {
        padding: 0.8rem 0.5rem;
        font-size: 0.85rem;
        color: #334155;
        border-bottom: 1px dashed #f1f5f9;
        vertical-align: middle;
    }
    .compact-table tr:last-child td { border-bottom: none; }

    .user-cell { display: flex; align-items: center; gap: 0.75rem; }
    .avatar-xs {
        width: 28px; height: 28px;
        border-radius: 6px;
        background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: bold; color: #64748b;
    }

    /* --- QUICK LIST --- */
    .quick-list { display: flex; flex-direction: column; gap: 1rem; }
    .quick-item {
        display: flex; justify-content: space-between; align-items: center;
        font-size: 0.85rem;
    }
    .quick-label { color: var(--secondary); display: flex; align-items: center; gap: 8px; }
    .quick-val { font-weight: 700; color: #0f172a; }

    .status-dot { width: 8px; height: 8px; border-radius: 50%; }
    .dot-green { background: #22c55e; }
    .dot-blue { background: #3b82f6; }
    .dot-red { background: #ef4444; }

    /* Filter Buttons */
    .chart-filter {
        background: #f1f5f9; border: none; padding: 4px 10px;
        border-radius: 6px; font-size: 0.7rem; font-weight: 600; color: #64748b;
        cursor: pointer; transition: all 0.2s;
    }
    .chart-filter.active { background: #22c55e; color: white; }
</style>
@endpush

@section('content')
<div class="dashboard-grid fade-in-up">

    {{-- STATS 1: Total Pengguna --}}
    <div class="bento-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ number_format($totalUsers) }}</div>
                <div class="stat-label">Total Pengguna</div>
            </div>
            <div class="stat-icon icon-users"><i class="fas fa-users"></i></div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="stat-link">Lihat semua <i class="fas fa-arrow-right text-[10px]"></i></a>
    </div>

    {{-- STATS 2: Active Events --}}
    <div class="bento-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ number_format($activeEvents) }}</div>
                <div class="stat-label">Event Aktif</div>
            </div>
            <div class="stat-icon icon-events"><i class="fas fa-calendar-check"></i></div>
        </div>
        <a href="{{ route('admin.events.index') }}" class="stat-link">Kelola Event <i class="fas fa-arrow-right text-[10px]"></i></a>
    </div>

    {{-- STATS 3: Completed --}}
    <div class="bento-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ number_format($completedTests) }}</div>
                <div class="stat-label">Tes Selesai</div>
            </div>
            <div class="stat-icon icon-tests"><i class="fas fa-clipboard-check"></i></div>
        </div>
        <a href="{{ route('admin.results.index') }}" class="stat-link">Lihat Hasil <i class="fas fa-arrow-right text-[10px]"></i></a>
    </div>

    {{-- STATS 4: Questions --}}
    <div class="bento-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ number_format($totalST30Questions + $totalTalentCompetencyQuestions) }}</div>
                <div class="stat-label">Total Soal</div>
            </div>
            <div class="stat-icon icon-db"><i class="fas fa-database"></i></div>
        </div>
        <a href="{{ route('admin.questions.index') }}" class="stat-link">Bank Soal <i class="fas fa-arrow-right text-[10px]"></i></a>
    </div>


    {{-- CHART SECTION --}}
    <div class="bento-card col-span-3" style="justify-content: flex-start; gap: 1rem;">
        <div class="section-header">
            <div class="section-title"><i class="fas fa-chart-area text-green-500"></i> Aktivitas Tes</div>
            <div style="display: flex; gap: 5px;">
                <button class="chart-filter active" onclick="updateStats('today')">Hari Ini</button>
                <button class="chart-filter" onclick="updateStats('week')">Minggu</button>
                <button class="chart-filter" onclick="updateStats('month')">Bulan</button>
            </div>
        </div>
        <div style="flex: 1; min-height: 200px; position: relative;">
            <canvas id="testActivityChart"></canvas>
        </div>
    </div>

    {{-- QUICK PULSE / RINGKASAN --}}
    <div class="bento-card" style="justify-content: flex-start; gap: 1rem;">
        <div class="section-header">
            <div class="section-title"><i class="fas fa-bolt text-yellow-500"></i> Ringkasan</div>
        </div>

        <div class="quick-list">
            <div class="quick-item">
                <span class="quick-label"><span class="status-dot dot-blue"></span> Hari Ini</span>
                <span class="quick-val">{{ $testsToday }}</span>
            </div>
            <div class="quick-item">
                <span class="quick-label"><span class="status-dot dot-green"></span> Minggu Ini</span>
                <span class="quick-val">{{ $testsThisWeek }}</span>
            </div>
            <div class="quick-item">
                <span class="quick-label"><span class="status-dot dot-red"></span> Menunggu</span>
                <span class="quick-val text-danger">{{ $pendingResendRequests }}</span>
            </div>

            <hr style="border-top: 1px dashed #e2e8f0; margin: 0.5rem 0;">

            <div class="quick-item">
                <span class="quick-label">Penyelesaian</span>
                <span class="quick-val">{{ $completionRate }}%</span>
            </div>
            <div class="quick-item">
                <span class="quick-label">Email Terkirim</span>
                <span class="quick-val">{{ $totalResults > 0 ? round(($emailsSent / $totalResults) * 100) : 0 }}%</span>
            </div>
        </div>
    </div>


    {{-- TABLE 1: Sesi Terbaru --}}
    <div class="bento-card col-span-2" style="justify-content: flex-start; gap: 1rem;">
        <div class="section-header">
            <div class="section-title">Sesi Terbaru</div>
            <a href="{{ route('admin.results.index') }}" style="font-size: 0.75rem; font-weight: 600; color: var(--primary);">Lihat Semua</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Status</th>
                        <th style="text-align: right;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTestSessions->take(5) as $session)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="avatar-xs">{{ substr($session->participant_name ?? $session->user->name, 0, 1) }}</div>
                                <div style="display:flex; flex-direction:column; line-height:1.2;">
                                    <span style="font-weight:600; font-size:0.85rem;">{{ Str::limit($session->participant_name ?? $session->user->name, 15) }}</span>
                                    <span style="font-size:0.7rem; color:#94a3b8;">{{ Str::limit($session->event->name ?? '-', 20) }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($session->is_completed)
                                <span style="font-size:0.65rem; font-weight:700; color:#166534; background:#dcfce7; padding:2px 8px; border-radius:4px;">SELESAI</span>
                            @else
                                <span style="font-size:0.65rem; font-weight:700; color:#854d0e; background:#fef9c3; padding:2px 8px; border-radius:4px;">PROSES</span>
                            @endif
                        </td>
                        <td style="text-align: right; color: #94a3b8; font-size: 0.75rem;">
                            {{ $session->created_at->locale('id')->diffForHumans(null, true) }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center; padding: 1rem; color:#94a3b8;">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TABLE 2: Permintaan Resend --}}
    <div class="bento-card col-span-2" style="justify-content: flex-start; gap: 1rem;">
        <div class="section-header">
            <div class="section-title">Permintaan Resend</div>
            <a href="{{ route('admin.resend.index') }}" style="font-size: 0.75rem; font-weight: 600; color: var(--primary);">Lihat Semua</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentResendRequests->take(5) as $request)
                    <tr>
                        <td>
                            <span style="font-weight:500;">{{ $request->user->email }}</span>
                        </td>
                        <td>
                            @if($request->status === 'pending')
                                <span style="font-size:0.65rem; font-weight:700; color:#9a3412; background:#ffedd5; padding:2px 6px; border-radius:4px;">MENUNGGU</span>
                            @elseif($request->status === 'approved')
                                <span style="font-size:0.65rem; font-weight:700; color:#166534; background:#dcfce7; padding:2px 6px; border-radius:4px;">di Setujui</span>
                            @else
                                <span style="font-size:0.65rem; font-weight:700; color:#991b1b; background:#fee2e2; padding:2px 6px; border-radius:4px;">TOLAK</span>
                            @endif
                        </td>
                    <td style="text-align: right;">
                        @if($request->status === 'pending')
                            <a href="{{ route('admin.resend.index', $request->id) }}"
                            style="font-size:0.7rem; font-weight:700; color:#3b82f6; text-decoration:none;">
                            TINJAU
                            </a>
                        @else
                            <span style="font-size:0.7rem; color:#cbd5e1;">-</span>
                        @endif
                    </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center; padding: 1rem; color:#94a3b8;">Tidak ada permintaan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Load jQuery first --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- FIXED: Load Chart.js via CDN karena file lokal tidak ditemukan --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let testActivityChart = null;

$(document).ready(function() {
    Chart.defaults.font.family = "'Figtree', sans-serif";
    Chart.defaults.color = '#94a3b8';

    const ctx = document.getElementById('testActivityChart').getContext('2d');

    // Gradient Hijau
    const gradient = ctx.createLinearGradient(0, 0, 0, 200);
    gradient.addColorStop(0, 'rgba(34, 197, 94, 0.15)');
    gradient.addColorStop(1, 'rgba(34, 197, 94, 0)');

    testActivityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($testsPerDay, 'date')) !!},
            datasets: [{
                label: 'Tes Selesai',
                data: {!! json_encode(array_column($testsPerDay, 'count')) !!},
                borderColor: '#22c55e',
                backgroundColor: gradient,
                borderWidth: 2,
                pointRadius: 3,
                pointHoverRadius: 5,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    display: false,
                    beginAtZero: true,
                    ticks: { precision: 0 }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 }, maxTicksLimit: 7 }
                }
            },
            layout: { padding: { top: 10, bottom: 0, left: -5, right: 0 } }
        }
    });
});

function updateStats(period) {
    $('.chart-filter').removeClass('active');
    $(event.target).addClass('active');

    $.ajax({
        url: '{{ route("admin.dashboard.stats") }}',
        type: 'GET',
        data: { period: period },
        success: function(response) {
            if (testActivityChart) {
                testActivityChart.data.labels = response.labels;
                testActivityChart.data.datasets[0].data = response.data;
                testActivityChart.update();
                console.log('Grafik diperbarui: ' + period);
            }
        },
        error: function(err) {
            console.error("Gagal mengambil data statistik", err);
        }
    });
}
</script>
@endpush

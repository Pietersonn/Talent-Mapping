@extends('pic.layouts.app')

@section('title', 'PIC Dashboard')

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
        gap: 1rem;
        padding-bottom: 2rem;
    }

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
        justify-content: space-between; /* Default: header di atas, link di bawah (untuk kartu stat kecil) */
        height: 100%;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
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
        width: 36px; height: 36px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
    }
    .icon-users { background: #eff6ff; color: #3b82f6; }
    .icon-events { background: #f0fdf4; color: #22c55e; }
    .icon-tests { background: #fefce8; color: #eab308; }
    .icon-pending { background: #fef2f2; color: #ef4444; }

    .stat-value { font-size: 1.5rem; font-weight: 700; color: #0f172a; line-height: 1.2; }
    .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--secondary); text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-link {
        font-size: 0.75rem; font-weight: 500; color: var(--secondary);
        text-decoration: none; margin-top: 0.5rem; display: inline-flex;
        align-items: center; gap: 4px; transition: color 0.2s;
    }
    .stat-link:hover { color: var(--primary); }

    /* --- LAYOUT SPANS --- */
    .col-span-3 { grid-column: span 3; }
    .col-span-2 { grid-column: span 2; }
    @media (max-width: 1024px) { .col-span-3, .col-span-2 { grid-column: span 2; } }
    @media (max-width: 640px) { .col-span-3, .col-span-2 { grid-column: span 1; } }

    /* --- SECTION HEADERS --- */
    .section-header {
        display: flex; justify-content: space-between; align-items: center;
        padding-bottom: 0.5rem; border-bottom: 1px solid #f1f5f9;
        /* Margin-bottom diatur via gap di inline style */
    }
    .section-title { font-size: 0.95rem; font-weight: 700; color: #334155; display: flex; align-items: center; gap: 0.5rem; }

    /* --- COMPACT TABLE --- */
    .compact-table { width: 100%; border-collapse: collapse; }
    .compact-table th {
        text-align: left; font-size: 0.7rem; color: var(--secondary);
        text-transform: uppercase; font-weight: 700; padding: 0.75rem 0.5rem;
        border-bottom: 1px solid var(--border);
    }
    .compact-table td {
        padding: 0.8rem 0.5rem; font-size: 0.85rem; color: #334155;
        border-bottom: 1px dashed #f1f5f9; vertical-align: middle;
    }
    .compact-table tr:last-child td { border-bottom: none; }

    .user-cell { display: flex; align-items: center; gap: 0.75rem; }
    .avatar-xs {
        width: 28px; height: 28px; border-radius: 6px; background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: bold; color: #64748b;
    }

    /* Code Badge Style */
    .code-badge {
        background-color: #f1f5f9;
        color: #475569;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        display: inline-block;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    }

    /* --- QUICK PULSE --- */
    .quick-list { display: flex; flex-direction: column; gap: 1rem; }
    .quick-item { display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; }
    .quick-label { color: var(--secondary); display: flex; align-items: center; gap: 8px; }
    .quick-val { font-weight: 700; color: #0f172a; }
    .status-dot { width: 8px; height: 8px; border-radius: 50%; }
    .dot-green { background: #22c55e; }
    .dot-blue { background: #3b82f6; }

    .chart-container { position: relative; height: 250px; width: 100%; }
</style>
@endpush

@section('content')
<div class="dashboard-grid fade-in-up">

    {{-- STATS 1: Total Participants --}}
    <div class="bento-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ number_format($totalParticipants) }}</div>
                <div class="stat-label">Total Peserta</div>
            </div>
            <div class="stat-icon icon-users"><i class="fas fa-users"></i></div>
        </div>
        <a href="{{ route('pic.participants.index') }}" class="stat-link">Lihat semua <i class="fas fa-arrow-right text-[10px]"></i></a>
    </div>

    {{-- STATS 2: My Events --}}
    <div class="bento-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ number_format($totalEvents) }}</div>
                <div class="stat-label">Event Aktif</div>
            </div>
            <div class="stat-icon icon-events"><i class="fas fa-calendar-check"></i></div>
        </div>
        <a href="{{ route('pic.events.index') }}" class="stat-link">Kelola Event <i class="fas fa-arrow-right text-[10px]"></i></a>
    </div>

    {{-- STATS 3: Completed Tests --}}
    <div class="bento-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ number_format($completedTests) }}</div>
                <div class="stat-label">Selesai</div>
            </div>
            <div class="stat-icon icon-tests"><i class="fas fa-clipboard-check"></i></div>
        </div>
        <span class="stat-link text-green-600">Tes Rampung</span>
    </div>

    {{-- STATS 4: Pending Tests --}}
    <div class="bento-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ number_format($pendingTests) }}</div>
                <div class="stat-label">Belum Selesai</div>
            </div>
            <div class="stat-icon icon-pending"><i class="fas fa-clock"></i></div>
        </div>
        <span class="stat-link text-red-500">Dalam Pengerjaan</span>
    </div>


    {{-- CHART SECTION: Statistik Jumlah Peserta --}}
    <div class="bento-card col-span-3">
        <div class="section-header" style="margin-bottom: 1rem;">
            <div class="section-title"><i class="fas fa-chart-bar text-green-500"></i> Statistik Peserta per Event</div>
        </div>
        <div class="chart-container">
            <canvas id="participantsChart"></canvas>
        </div>
    </div>

    {{-- QUICK PULSE: Ringkasan --}}
    {{-- FIX: style="justify-content: flex-start; gap: 1rem;" agar ringkasan tidak melayang di tengah --}}
    <div class="bento-card" style="justify-content: flex-start; gap: 1rem;">
        <div class="section-header">
            <div class="section-title"><i class="fas fa-bolt text-yellow-500"></i> Ringkasan</div>
        </div>

        <div class="quick-list">
            <div class="quick-item">
                <span class="quick-label"><span class="status-dot dot-blue"></span> Event</span>
                <span class="quick-val">{{ $totalEvents }}</span>
            </div>
            <div class="quick-item">
                <span class="quick-label"><span class="status-dot dot-green"></span> Partisipan</span>
                <span class="quick-val">{{ $totalParticipants }}</span>
            </div>

            <hr style="border-top: 1px dashed #e2e8f0; margin: 0.5rem 0;">

            <div class="quick-item">
                <span class="quick-label">Rata-Rata Peserta/Event</span>
                <span class="quick-val">{{ $totalEvents > 0 ? round($totalParticipants / $totalEvents) : 0 }}</span>
            </div>
        </div>
    </div>


    {{-- TABLE 1: Recent Sessions (Aktivitas Terbaru) --}}
    {{-- FIX: style="justify-content: flex-start; gap: 1rem;" --}}
    <div class="bento-card col-span-2" style="justify-content: flex-start; gap: 1rem;">
        <div class="section-header">
            <div class="section-title">Aktivitas Terbaru</div>
            <a href="{{ route('pic.participants.index') }}" style="font-size: 0.75rem; font-weight: 600; color: var(--primary);">Liat Semua</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Status</th>
                        <th style="text-align: right;">Waktu    </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSessions as $session)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="avatar-xs">{{ substr($session->user->name ?? '?', 0, 1) }}</div>
                                <div style="display:flex; flex-direction:column; line-height:1.2;">
                                    <span style="font-weight:600; font-size:0.85rem;">{{ Str::limit($session->user->name ?? 'Guest', 15) }}</span>
                                    <span style="font-size:0.7rem; color:#94a3b8;">{{ Str::limit($session->event->name ?? '-', 25) }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($session->is_completed)
                                <span style="font-size:0.65rem; font-weight:700; color:#166534; background:#dcfce7; padding:2px 8px; border-radius:4px;">SELESAI</span>
                            @else
                                <span style="font-size:0.65rem; font-weight:700; color:#854d0e; background:#fef9c3; padding:2px 8px; border-radius:4px;">PROGRES</span>
                            @endif
                        </td>
                        <td style="text-align: right; color: #94a3b8; font-size: 0.75rem;">
                            {{ $session->updated_at->locale('id')->diffForHumans(null, true) }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center; padding: 1rem; color:#94a3b8;">Belum ada aktivitas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TABLE 2: Data Event --}}
    {{-- FIX: style="justify-content: flex-start; gap: 1rem;" --}}
    <div class="bento-card col-span-2" style="justify-content: flex-start; gap: 1rem;">
        <div class="section-header">
            <div class="section-title">Data Event</div>
            <a href="{{ route('pic.events.index') }}" style="font-size: 0.75rem; font-weight: 600; color: var(--primary);">Liat Semua</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="compact-table" style="table-layout: fixed; width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 45%;">Nama Event</th>
                        <th style="width: 25%;">Kode</th>
                        <th style="text-align: right; width: 30%;">Peserta / Kuota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventsData as $data)
                        @if($loop->iteration <= 5)
                        <tr>
                            <td>
                                <span style="font-weight:600; font-size:0.85rem; color: #334155;">{{ Str::limit($data['event']->name, 25) }}</span>
                            </td>
                            <td>
                                <span class="code-badge">
                                    {{ $data['event']->event_code }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <span style="font-weight:700; color:#0f172a;">{{ $data['registered'] }}</span>
                                <span class="text-gray-400 text-xs"> / {{ $data['quota'] > 0 ? $data['quota'] : '∞' }}</span>
                            </td>
                        </tr>
                        @endif
                    @empty
                    <tr><td colspan="3" style="text-align:center; padding: 1rem; color:#94a3b8;">Tidak ada event aktif</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    Chart.defaults.font.family = "'Figtree', sans-serif";
    Chart.defaults.color = '#94a3b8';

    const rawData = @json($eventsData);
    const chartData = rawData.slice(0, 10);

    const labels = chartData.map(item => item.event.name.substring(0, 15) + (item.event.name.length > 15 ? '...' : ''));
    const registeredData = chartData.map(item => item.registered);
    const quotaData = chartData.map(item => item.quota);

    const ctx = document.getElementById('participantsChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Peserta Terdaftar',
                    data: registeredData,
                    backgroundColor: '#3b82f6',
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                },
                {
                    label: 'Kuota Maksimal',
                    data: quotaData,
                    backgroundColor: '#e2e8f0',
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8,
                    grouped: false,
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: { boxWidth: 10, usePointStyle: true, pointStyle: 'circle' }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 10,
                    cornerRadius: 8,
                    titleFont: { size: 13 },
                    bodyFont: { size: 12 },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', borderDash: [2, 2] },
                    ticks: { font: { size: 10 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            },
            layout: { padding: { top: 0, bottom: 0, left: 0, right: 0 } }
        }
    });
});
</script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Detail Event')

@push('styles')
<style>
    /* Header Card yang menonjol */
    .detail-header { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: start; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .event-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
    .event-meta { display: flex; gap: 1rem; color: #64748b; font-size: 0.9rem; align-items: center; flex-wrap: wrap; }
    .meta-item { display: flex; align-items: center; gap: 6px; background: #f8fafc; padding: 4px 10px; border-radius: 8px; border: 1px solid #f1f5f9; }

    /* Layout Grid Dashboard (Bento Box) */
    .dashboard-grid { display: grid; grid-template-columns: 350px 1fr; gap: 1.5rem; align-items: start; }
    @media (max-width: 768px) { .dashboard-grid { grid-template-columns: 1fr; } }

    .bento-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; display: flex; flex-direction: column; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-title { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px; padding-bottom: 0.75rem; border-bottom: 2px solid #f1f5f9; }

    /* Info List Style */
    .info-list { display: flex; flex-direction: column; gap: 1rem; }
    .info-item { display: flex; justify-content: space-between; font-size: 0.875rem; }
    .info-label { color: #64748b; font-weight: 500; }
    .info-value { font-weight: 600; color: #0f172a; text-align: right; }

    /* Buttons & Badges */
    .btn-action { padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 0.85rem; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
    .btn-edit { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
    .btn-edit:hover { background: #dbeafe; }
    .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-active { background: #dcfce7; color: #166534; }
    .badge-inactive { background: #fee2e2; color: #991b1b; }

    /* Simple Table for Participants */
    .simple-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .simple-table th { text-align: left; padding: 12px; color: #94a3b8; font-weight: 600; border-bottom: 1px solid #e2e8f0; }
    .simple-table td { padding: 12px; border-bottom: 1px dashed #f1f5f9; color: #334155; }
    .simple-table tr:last-child td { border-bottom: none; }
</style>
@endpush

@section('header')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title" style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-calendar-week" style="color: #22c55e; background: #dcfce7; padding: 10px; border-radius: 12px; font-size: 1.1rem;"></i>
                Detail Event
            </h1>
        </div>
        <a href="{{ route('admin.events.index') }}" class="btn-action" style="background: white; color: #64748b; border: 1px solid #e2e8f0;"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
@endsection

@section('content')
<div class="fade-in-up">
    <div class="detail-header">
        <div>
            <h2 class="event-title">{{ $event->name }}</h2>
            <div class="event-meta">
                <div class="meta-item"><i class="fas fa-barcode text-gray-400"></i> <span style="font-family: monospace;">{{ $event->event_code }}</span></div>
                <div class="meta-item"><i class="fas fa-building text-gray-400"></i> {{ $event->company ?? 'Instansi Tidak Diisi' }}</div>
                <span class="status-badge {{ $event->is_active ? 'badge-active' : 'badge-inactive' }}">
                    <i class="fas fa-circle" style="font-size: 6px; margin-right: 4px; vertical-align: middle;"></i>
                    {{ $event->is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
        </div>

        @if(Auth::user()->role === 'admin')
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn-action btn-edit">
                <i class="fas fa-pen-to-square"></i> Edit Event
            </a>

            <form action="{{ route('admin.events.toggle-status', $event->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-action" style="background: #fefce8; color: #a16207; border: 1px solid #fef08a;">
                    <i class="fas fa-power-off"></i> {{ $event->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
        </div>
        @endif
    </div>

    <div class="dashboard-grid">

        <div class="bento-card">
            <div class="card-title"><i class="fas fa-circle-info text-green-500"></i> Detail Informasi</div>

            <div class="info-list">
                <div class="info-item">
                    <span class="info-label"><i class="far fa-calendar text-gray-400 mr-2"></i>Tanggal Mulai</span>
                    <span class="info-value">{{ $event->start_date->format('d F Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="far fa-calendar-check text-gray-400 mr-2"></i>Tanggal Selesai</span>
                    <span class="info-value">{{ $event->end_date->format('d F Y') }}</span>
                </div>
                <div class="info-item" style="border-top: 1px dashed #f1f5f9; padding-top: 1rem;">
                    <span class="info-label"><i class="far fa-user-circle text-gray-400 mr-2"></i>PIC Event</span>
                    <span class="info-value">{{ $event->pic->name ?? 'Belum ditentukan' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-users text-gray-400 mr-2"></i>Kuota Peserta</span>
                    <span class="info-value">
                        {{ $stats['total_participants'] }} / {{ $event->max_participants ?? '∞' }}
                    </span>
                </div>
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 2px solid #f1f5f9;">
                <div class="text-xs font-bold text-gray-500 uppercase mb-2">Deskripsi Event</div>
                <p class="text-sm text-gray-600 leading-relaxed" style="white-space: pre-line;">
                    {{ $event->description ?? 'Tidak ada deskripsi yang ditambahkan untuk event ini.' }}
                </p>
            </div>
        </div>

        <div class="bento-card" style="min-height: 400px;">
            <div class="card-title" style="justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-users-rectangle text-green-500"></i> Daftar Peserta Terbaru
                </div>
                <span style="background: #f1f5f9; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem;">Total: {{ $stats['total_participants'] }}</span>
            </div>

            @if($event->participants->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Nama Peserta</th>
                                <th>Email</th>
                                <th style="text-align: right;">Status Tes</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Menampilkan 10 peserta terbaru saja --}}
                            @foreach($event->participants->take(10) as $p)
                                @php
                                    // PERBAIKAN LOGIKA STATUS:
                                    // Ambil data sesi test dari relasi yang sudah di-load di controller
                                    $session = $p->testSessions->first(); // Ambil sesi pertama (karena difilter per event)
                                    $isFinished = $session && $session->is_completed;
                                @endphp
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $p->name }}</td>
                                    <td style="color: #64748b;">{{ $p->email }}</td>
                                    <td style="text-align: right;">
                                        @if($isFinished)
                                            <span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">SELESAI</span>
                                        @else
                                            <span style="background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">BELUM SELESAI</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($event->participants->count() > 10)
                    <div style="text-align: center; margin-top: 1rem; font-size: 0.9rem; color: #64748b;">
                        Dan {{ $event->participants->count() - 10 }} peserta lainnya...
                    </div>
                @endif
            @else
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8; gap: 1rem;">
                    <i class="fas fa-user-slash fa-3x" style="color: #e2e8f0;"></i>
                    <p>Belum ada peserta yang mendaftar di event ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Detail Program')

@push('styles')
<style>
    /* Header Card yang menonjol */
    .detail-header { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: start; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .program-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
    .program-meta { display: flex; gap: 1rem; color: #64748b; font-size: 0.9rem; align-items: center; flex-wrap: wrap; }
    .meta-item { display: flex; align-items: center; gap: 6px; background: #f8fafc; padding: 4px 10px; border-radius: 8px; border: 1px solid #f1f5f9; }

    /* Layout Grid Dashboard (Bento Box) */
    .dashboard-grid { display: grid; grid-template-columns: 350px 1fr; gap: 1.5rem; align-items: start; }
    @media (max-width: 768px) { .dashboard-grid { grid-template-columns: 1fr; } }

    /* Bento Card Umum */
    .bento-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); display: flex; flex-direction: column; }
    .card-title { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px; }

    /* Progress & Kuota Area */
    .quota-box { text-align: center; padding: 1.5rem; background: #f0fdf4; border-radius: 16px; border: 1px solid #bbf7d0; margin-bottom: 1.5rem; }
    .quota-number { font-size: 2.5rem; font-weight: 900; color: #166534; line-height: 1; }
    .quota-label { font-size: 0.85rem; color: #15803d; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 5px; }
    .progress-bar-bg { width: 100%; height: 8px; background: #dcfce7; border-radius: 10px; overflow: hidden; margin-top: 15px; }
    .progress-bar-fill { height: 100%; background: #22c55e; border-radius: 10px; transition: width 0.5s ease; }

    /* List Informasi Sidebar */
    .info-list { display: flex; flex-direction: column; gap: 12px; }
    .info-item { display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; padding-bottom: 12px; border-bottom: 1px dashed #e2e8f0; }
    .info-item:last-child { border-bottom: none; padding-bottom: 0; }
    .info-label { color: #64748b; }
    .info-value { font-weight: 600; color: #334155; text-align: right; }

    /* Custom Table Peserta */
    .table-container { overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 12px; }
    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table th { background: #f8fafc; padding: 12px 16px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; color: #334155; vertical-align: middle; }
    .custom-table tr:hover td { background: #f8fafc; }

    /* Link & Button */
    .btn-back { display: inline-flex; align-items: center; gap: 6px; color: #64748b; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: 0.2s; background: white; padding: 8px 16px; border-radius: 10px; border: 1px solid #e2e8f0; }
    .btn-back:hover { color: #0f172a; background: #f8fafc; }

    .status-badge { padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
    .badge-active { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .badge-inactive { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    .btn-del { display: inline-flex; align-items: center; gap: 6px; font-size: 0.9rem; font-weight: 600; border-radius: 10px; padding: 8px 16px; border: 1px solid transparent; transition: 0.2s; cursor: pointer; background: #fef2f2; color: #ef4444; border-color: #fecaca; }
    .btn-del:hover { background: #fee2e2; }
</style>
@endpush

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('admin.programs.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Program</a>
</div>

<div class="detail-header">
    <div>
        <h1 class="program-title">{{ $program->nama }}</h1>
        <div class="program-meta">
            <div class="meta-item"><i class="fas fa-key text-green-500"></i> Kode Akses: <b class="font-mono text-green-700 ml-1">{{ $program->kode_program }}</b></div>
            <div class="meta-item"><i class="far fa-building text-green-500"></i> {{ $program->perusahaan ?? 'Tanpa Instansi' }}</div>
            <div class="meta-item">
                @if($program->aktif)
                    <span class="status-badge badge-active"><i class="fas fa-check-circle mr-1"></i> Aktif Berjalan</span>
                @else
                    <span class="status-badge badge-inactive"><i class="fas fa-minus-circle mr-1"></i> Tidak Aktif</span>
                @endif
            </div>
        </div>
    </div>

    @if(Auth::user()->peran === 'admin')
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.programs.edit', $program->id) }}" style="background: #ecfdf5; color: #059669; border: 1px solid #d1fae5; padding: 8px 16px; border-radius: 10px; font-weight: 600; text-decoration: none; font-size: 0.9rem; display: flex; align-items: center; gap: 6px; transition: 0.2s;">
                <i class="fas fa-pen"></i> Edit
            </a>
            <button onclick="confirmDelete('{{ $program->id }}', '{{ $program->nama }}')" class="btn-del">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </div>
    @endif
</div>

<div class="dashboard-grid">
    {{-- SIDEBAR KIRI --}}
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- Card Jadwal --}}
        <div class="bento-card">
            <div class="card-title"><i class="far fa-calendar-alt text-green-500"></i> Jadwal Program</div>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Tanggal Mulai</span>
                    <span class="info-value" style="color: #0f172a;">{{ \Carbon\Carbon::parse($program->tanggal_mulai)->format('d M Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Selesai</span>
                    <span class="info-value" style="color: #0f172a;">{{ \Carbon\Carbon::parse($program->tanggal_selesai)->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Card Kuota --}}
        <div class="bento-card">
            <div class="card-title"><i class="fas fa-chart-pie text-green-500"></i> Partisipasi Peserta</div>

            <div class="quota-box">
                <div class="quota-number">{{ $program->participants->count() }} <span style="font-size: 1.25rem; color: #86efac; font-weight: 600;">/ {{ $program->maks_peserta ?? '∞' }}</span></div>
                <div class="quota-label">Peserta Terdaftar</div>

                @php
                    $percentage = 0;
                    if ($program->maks_peserta && $program->maks_peserta > 0) {
                        $percentage = min(100, ($program->participants->count() / $program->maks_peserta) * 100);
                    }
                @endphp
                @if($program->maks_peserta)
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $percentage }}%;"></div>
                    </div>
                @endif
            </div>

            <div class="info-list">
                @php
                    $finishedCount = $program->participants->filter(function($p) {
                        return $p->testSessions->where('is_completed', true)->count() == 2;
                    })->count();
                @endphp
                <div class="info-item">
                    <span class="info-label">Selesai Tes</span>
                    <span class="info-value" style="color: #16a34a;"><i class="fas fa-check-circle mr-1"></i> {{ $finishedCount }} Orang</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Sedang Proses</span>
                    <span class="info-value" style="color: #64748b;"><i class="fas fa-spinner mr-1"></i> {{ $program->participants->count() - $finishedCount }} Orang</span>
                </div>
            </div>
        </div>

        {{-- Card Info PIC --}}
        <div class="bento-card">
            <div class="card-title"><i class="fas fa-user-tie text-green-500"></i> Informasi Mitra (PIC)</div>
            <div style="display: flex; align-items: center; gap: 15px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="width: 48px; height: 48px; background: #dcfce7; color: #166534; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 700;">
                    {{ $program->mitra ? substr($program->mitra->nama, 0, 1) : '?' }}
                </div>
                <div>
                    <div style="font-weight: 700; color: #0f172a; margin-bottom: 2px;">{{ $program->mitra->nama ?? 'Mitra Tidak Ditemukan' }}</div>
                    <div style="font-size: 0.8rem; color: #64748b;"><i class="fas fa-envelope mr-1"></i> {{ $program->mitra->email ?? '-' }}</div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-dashed text-sm">
                <div class="font-bold text-slate-700 mb-2">Deskripsi Program:</div>
                <p class="text-slate-500 leading-relaxed">{{ $program->deskripsi ?: 'Tidak ada deskripsi tambahan untuk program ini.' }}</p>
            </div>
        </div>

    </div>

    {{-- KONTEN KANAN: LIST PESERTA --}}
    <div class="bento-card" style="padding: 0; overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <div class="card-title" style="margin: 0;"><i class="fas fa-users text-green-500"></i> Daftar Peserta (10 Terbaru)</div>
            <span style="font-size: 0.8rem; color: #64748b; background: white; padding: 4px 10px; border-radius: 20px; border: 1px solid #e2e8f0;">Hanya lihat preview</span>
        </div>

        <div style="padding: 1rem;">
            @if($program->participants->count() > 0)
                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Nama Peserta</th>
                                <th>Email</th>
                                <th style="text-align: right;">Status Tes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($program->participants->take(10) as $participant)
                                @php
                                    $completedTests = $participant->testSessions->where('is_completed', true)->count();
                                    $isFinished = $completedTests == 2;
                                @endphp
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $participant->nama }}</td>
                                    <td style="color: #64748b; font-size: 0.85rem;">{{ $participant->email }}</td>
                                    <td style="text-align: right;">
                                        @if($isFinished)
                                            <span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">SELESAI</span>
                                        @else
                                            <span style="background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">BELUM SELESAI</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($program->participants->count() > 10)
                    <div style="text-align: center; margin-top: 1rem; font-size: 0.9rem; color: #64748b;">
                        Dan {{ $program->participants->count() - 10 }} peserta lainnya...
                    </div>
                @endif
            @else
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8; gap: 1rem; padding: 3rem 0;">
                    <i class="fas fa-user-slash fa-3x" style="color: #e2e8f0;"></i>
                    <p>Belum ada peserta yang mendaftar di program ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Program?',
            html: `Yakin ingin menghapus program <b>${name}</b>?`,
            icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#f1f5f9',
            confirmButtonText: 'Ya, Hapus', cancelButtonText: '<span style="color:black">Batal</span>',
            customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl px-4 py-2', cancelButton: 'rounded-xl px-4 py-2' }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('admin/programs') }}/${id}`;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush

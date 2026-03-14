@extends('admin.layouts.app')

@section('title', 'Permintaan Kirim Ulang')

@push('styles')
<style>
    :root {
        --primary-green: #22c55e;
        --soft-bg: #f8fafc;
        --border-color: #e2e8f0;
    }

    /* --- SEARCH & BUTTONS STYLE --- */
    .search-group { position: relative; width: 350px; }
    .search-input { width: 100%; height: 46px; padding: 10px 45px 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; background: #ffffff; transition: all 0.3s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); color: #334155; }
    .search-input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15); }
    .loading-spinner { position: absolute; right: 14px; top: 33%; transform: translateY(-50%); display: none; color: #22c55e; font-size: 1.1rem; pointer-events: none; }
    .search-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1rem; pointer-events: none; transition: opacity 0.2s; }

    .btn-print { width: 46px; height: 46px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all 0.2s; text-decoration: none; }
    .btn-print:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; transform: translateY(-1px); }

    /* --- CUSTOM TRUNCATE --- */
    .text-truncate-custom { display: inline-block; max-width: 130px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: bottom; }

    /* --- BENTO GRID SYSTEM --- */
    .bento-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    @media (max-width: 1200px) { .bento-grid-4 { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px)  { .bento-grid-4 { grid-template-columns: 1fr; } }

    /* --- STAT CARD MODERN --- */
    .stat-card-modern { background: white; border: 1px solid var(--border-color); border-radius: 16px; padding: 1.25rem; display: flex; align-items: center; justify-content: space-between; transition: transform 0.2s ease, box-shadow 0.2s ease; height: 100%; }
    .stat-card-modern:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); border-color: #cbd5e1; }
    .stat-content { display: flex; flex-direction: column; }
    .stat-label { font-size: 0.75rem; color: #64748b; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 4px; }
    .stat-value { font-size: 1.75rem; font-weight: 800; color: #0f172a; line-height: 1; }
    .stat-icon-wrapper { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }

    /* --- COMPACT PENDING CARD --- */
    .req-card-compact { background: white; border: 1px solid var(--border-color); border-radius: 16px; padding: 1rem; display: flex; flex-direction: column; justify-content: space-between; height: 100%; position: relative; transition: all 0.2s; overflow: hidden; }
    .req-card-compact:hover { border-color: var(--primary-green); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    .req-card-compact::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: #f59e0b; opacity: 0.8; }

    .user-meta { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
    .user-avatar-text { width: 36px; height: 36px; background: #f1f5f9; color: #475569; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; }
    .user-details { flex: 1; min-width: 0; }
    .user-name { font-weight: 700; color: #0f172a; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.2; }
    .user-email { font-size: 0.75rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .req-info { background: #f8fafc; border-radius: 8px; padding: 8px 10px; margin-bottom: 12px; border: 1px dashed #e2e8f0; font-size: 0.75rem; }
    .req-info-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }

    .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .btn-compact { border: none; border-radius: 8px; padding: 6px; font-size: 0.75rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 6px; cursor: pointer; transition: 0.2s; }
    .btn-green { background: #dcfce7; color: #15803d; }
    .btn-green:hover { background: #22c55e; color: white; }
    .btn-red { background: #fee2e2; color: #b91c1c; }
    .btn-red:hover { background: #ef4444; color: white; }

    /* --- TABLE STYLE --- */
    .table-card { background: white; border: 1px solid #f1f5f9; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-top: 2rem; }
    .table-header { padding: 1.25rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { text-align: left; padding: 1rem; background: #f8fafc; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.85rem; color: #334155; background: white; }
    .custom-table tr:hover td { background-color: #f8fafc; }

    .status-badge { padding: 3px 8px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }
    .status-approved { background: #dcfce7; color: #15803d; }
    .status-rejected { background: #fee2e2; color: #b91c1c; }
    .loading-overlay { position: absolute; inset: 0; background: rgba(255,255,255,0.85); z-index: 10; display: none; align-items: center; justify-content: center; border-radius: 12px; }

    @media (max-width: 768px) { .search-group { width: 100%; } }
</style>
@endpush

@section('content')

    {{-- A. HEADER SECTION --}}
     <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0;">
                <i class="fas fa-paper-plane text-success bg-green-100 p-2 rounded mr-2"></i>
                Permintaan Kirim Ulang
            </h1>
            <p class="text-muted small m-0 mt-1">Kelola permintaan pengiriman ulang hasil assessment</p>
        </div>
    </div>

    {{-- B. STATS SECTION --}}
    <div class="bento-grid-4">
        <div class="stat-card-modern">
            <div class="stat-content">
                <span class="stat-label">Total Permintaan</span>
                <span class="stat-value">{{ $stats['total'] }}</span>
            </div>
            <div class="stat-icon-wrapper" style="background: #eff6ff; color: #3b82f6;">
                <i class="fas fa-layer-group"></i>
            </div>
        </div>
        <div class="stat-card-modern" style="border-bottom: 3px solid #f59e0b;">
            <div class="stat-content">
                <span class="stat-label">Menunggu</span>
                <span class="stat-value">{{ $stats['pending'] }}</span>
            </div>
            <div class="stat-icon-wrapper" style="background: #fff7ed; color: #f59e0b;">
                <i class="fas fa-hourglass-start"></i>
            </div>
        </div>
        <div class="stat-card-modern" style="border-bottom: 3px solid #22c55e;">
            <div class="stat-content">
                <span class="stat-label">Disetujui</span>
                <span class="stat-value">{{ $stats['approved'] }}</span>
            </div>
            <div class="stat-icon-wrapper" style="background: #f0fdf4; color: #22c55e;">
                <i class="fas fa-circle-check"></i>
            </div>
        </div>
        <div class="stat-card-modern" style="border-bottom: 3px solid #ef4444;">
            <div class="stat-content">
                <span class="stat-label">Ditolak</span>
                <span class="stat-value">{{ $stats['rejected'] }}</span>
            </div>
            <div class="stat-icon-wrapper" style="background: #fef2f2; color: #ef4444;">
                <i class="fas fa-circle-xmark"></i>
            </div>
        </div>
    </div>

    {{-- C. PENDING REQUESTS --}}
    <div class="mb-5">
        <div class="d-flex align-items-center mb-3">
            <h6 class="font-weight-bold text-dark m-0">
                <span style="background: #fff7ed; color: #c2410c; padding: 5px 12px; border-radius: 8px; font-size: 0.85rem; border: 1px solid #ffedd5; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-bell animate__animated animate__swing animate__infinite"></i> Menunggu Persetujuan
                </span>
            </h6>
        </div>

        @if($pendingRequests->count() > 0)
            <div class="bento-grid-4">
                @foreach($pendingRequests as $req)
                <div class="req-card-compact">
                    <div class="loading-overlay" id="loading-{{ $req->id }}">
                        <div class="spinner-border text-success" role="status"></div>
                    </div>

                    <div>
                        <div class="user-meta">
                            <div class="user-avatar-text">{{ substr($req->user->name, 0, 1) }}</div>
                            <div class="user-details">
                                <div class="user-name" title="{{ $req->user->name }}">{{ $req->user->name }}</div>
                                <div class="user-email" title="{{ $req->user->email }}">{{ $req->user->email }}</div>
                            </div>
                        </div>

                        <div class="req-info">
                            <div class="req-info-row">
                                <span class="text-muted"><i class="far fa-clock me-1"></i> Waktu:</span>
                                <span class="fw-bold text-dark">{{ $req->request_date->diffForHumans(null, true) }}</span>
                            </div>

                            {{-- KOLOM EVENT DI CARD --}}
                            <div class="req-info-row">
                                <span class="text-muted"><i class="far fa-calendar-check me-1"></i> Acara:</span>
                                <span class="fw-bold text-dark text-truncate-custom"
                                      title="{{ $req->testResult->event_title }}">
                                    {{ $req->testResult->event_title }}
                                </span>
                            </div>

                            <div class="req-info-row">
                                <span class="text-muted"><i class="far fa-file-alt me-1"></i> Hasil:</span>
                                <a href="{{ route('admin.results.index', ['search' => $req->user->email]) }}" target="_blank" class="fw-bold text-success text-decoration-none">
                                    #{{ $req->testResult->id ?? '?' }} <i class="fas fa-external-link-alt" style="font-size: 9px;"></i>
                                </a>
                            </div>
                        </div>

                        @if($req->user_notes)
                            <div style="font-size: 0.75rem; color: #64748b; font-style: italic; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 10px;">
                                "{{ $req->user_notes }}"
                            </div>
                        @endif
                    </div>

                    <div class="action-grid">
                        <button onclick="approveRequest('{{ $req->id }}')" class="btn-compact btn-green">
                            <i class="fas fa-check"></i> Setujui
                        </button>
                        <button onclick="rejectRequest('{{ $req->id }}')" class="btn-compact btn-red">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 bg-white rounded-4 border border-dashed mb-4">
                <i class="fas fa-mug-hot text-secondary mb-3" style="font-size: 2rem; opacity: 0.2;"></i>
                <p class="text-muted small m-0 fw-bold">Tidak ada antrean pending.</p>
            </div>
        @endif
    </div>

    {{-- D. TABLE HISTORY --}}
    <div class="table-card">
        <div class="table-header">
            <div>
                <h6 class="m-0 fw-bold text-dark">Riwayat Proses</h6>
                <small class="text-muted" style="font-size: 0.75rem;">Permintaan Disetujui / Ditolak</small>
            </div>

            {{-- SEARCH & EXPORT --}}
            <div style="display: flex; gap: 12px; align-items: center;">
                <div class="search-group">
                    <input type="text" id="realtimeSearch" class="search-input"
                           placeholder="Cari user, email, atau acara..." autocomplete="off"
                           value="{{ request('search') }}">
                    <i class="fas fa-search search-icon"></i>
                    <i class="fas fa-circle-notch fa-spin loading-spinner"></i>
                </div>

                <a href="{{ route('admin.resend.export.pdf', request()->query()) }}"
                   id="btnExportPdf"
                   class="btn-print" target="_blank" title="Unduh PDF">
                    <i class="fas fa-print"></i>
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">Nama</th>
                        <th style="width: 20%;">Email</th>
                        <th style="width: 20%;">Acara</th> {{-- KOLOM BARU --}}
                        <th style="width: 15%;">Tanggal</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 15%;">Diproses Oleh</th>
                    </tr>
                </thead>
                <tbody id="resendTableBody">
                    @forelse($historyRequests as $req)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $req->user->name }}</div>
                        </td>
                        <td>
                            <div class="text-muted">{{ $req->user->email }}</div>
                        </td>
                        {{-- DATA ACARA (BLADE) --}}
                        <td>
                            <span class="text-truncate-custom text-dark" style="max-width: 150px;" title="{{ $req->testResult->event_title }}">
                                {{ $req->testResult->event_title }}
                            </span>
                        </td>
                        <td>
                            <div class="text-dark" style="font-size: 0.8rem;">{{ $req->request_date->format('d M Y') }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $req->request_date->format('H:i') }}</div>
                        </td>
                        <td>
                            @if($req->status == 'approved')
                                <span class="status-badge status-approved">Disetujui</span>
                            @else
                                <span class="status-badge status-rejected">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-dark" style="font-size: 0.8rem;">{{ $req->approvedBy->name ?? '-' }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">
                                {{-- Tambahkan ->locale('id') di sini --}}
                                {{ $req->approved_at ? $req->approved_at->locale('id')->diffForHumans() : '' }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted small">Belum ada riwayat proses.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($historyRequests->hasPages())
            <div class="p-3 border-top">
                {{ $historyRequests->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- REALTIME SEARCH LOGIC ---
    let debounceTimer;
    const searchInput = $('#realtimeSearch');
    const exportBtn = $('#btnExportPdf');
    const baseExportUrl = "{{ route('admin.resend.export.pdf') }}";

    searchInput.on('input', function() {
        const query = $(this).val();

        // UI: Show Spinner
        $('.loading-spinner').show();
        $('.search-icon').hide();

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            $.ajax({
                url: "{{ route('admin.resend.index') }}",
                type: "GET",
                data: { search: query },
                success: function(response) {
                    renderTable(response);

                    // UI: Hide Spinner
                    $('.loading-spinner').hide();
                    $('.search-icon').show();

                    // Update PDF Link
                    if (query.trim() !== "") {
                        exportBtn.attr('href', baseExportUrl + "?search=" + encodeURIComponent(query));
                    } else {
                        exportBtn.attr('href', baseExportUrl);
                    }
                },
                error: function() {
                    $('.loading-spinner').hide();
                    $('.search-icon').show();
                }
            });
        }, 500);
    });

    // RENDER TABLE FUNCTION (JS)
    function renderTable(response) {
        const tbody = $('#resendTableBody');
        const data = response.data;

        tbody.empty();

        if (data.length === 0) {
            tbody.html('<tr><td colspan="6" class="text-center py-4 text-muted small">Tidak ada data ditemukan.</td></tr>');
            return;
        }

        let html = '';
        data.forEach(item => {
            let statusBadge = item.status === 'approved'
                ? '<span class="status-badge status-approved">Disetujui</span>'
                : '<span class="status-badge status-rejected">Ditolak</span>';

            html += `<tr>
                <td><div class="fw-bold text-dark">${item.user_name}</div></td>
                <td><div class="text-muted">${item.user_email}</div></td>
                <td>
                    <span class="text-truncate-custom text-dark" style="max-width: 150px;" title="${item.event_name}">
                        ${item.event_name}
                    </span>
                </td>
                <td>
                    <div class="text-dark" style="font-size: 0.8rem;">${item.date_dmy}</div>
                    <div class="text-muted" style="font-size: 0.7rem;">${item.date_hi}</div>
                </td>
                <td>${statusBadge}</td>
                <td>
                    <div class="text-dark" style="font-size: 0.8rem;">${item.processor}</div>
                    <div class="text-muted" style="font-size: 0.7rem;">${item.processed_at}</div>
                </td>
            </tr>`;
        });

        tbody.html(html);
    }

    // --- ACTIONS ---
    function approveRequest(id) {
        Swal.fire({
            title: 'Setujui Permintaan?',
            text: "Sistem akan mengirimkan ulang email hasil ke pengguna ini.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Kirim Email',
            cancelButtonText: 'Batal',
            padding: '1.5rem',
            borderRadius: '12px'
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#loading-${id}`).fadeIn();
                $.post("{{ url('admin/resend') }}/" + id + "/approve", { _token: "{{ csrf_token() }}" })
                .done(() => {
                    Swal.fire({ title: 'Terkirim!', text: 'Email hasil telah dikirim ulang.', icon: 'success', timer: 1500, showConfirmButton: false, padding: '1.5rem' }).then(() => location.reload());
                })
                .fail((xhr) => {
                    $(`#loading-${id}`).fadeOut();
                    Swal.fire({ title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan sistem', icon: 'error', confirmButtonColor: '#22c55e' });
                });
            }
        });
    }

    function rejectRequest(id) {
        Swal.fire({
            title: 'Tolak Permintaan',
            text: 'Silakan masukkan alasan penolakan:',
            input: 'text',
            inputPlaceholder: 'Tulis alasan disini...',
            showCancelButton: true,
            confirmButtonText: 'Tolak Permintaan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            padding: '1.5rem',
            borderRadius: '12px',
            inputValidator: (v) => !v && 'Alasan wajib diisi!'
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#loading-${id}`).fadeIn();
                $.post("{{ url('admin/resend') }}/" + id + "/reject", {
                    _token: "{{ csrf_token() }}",
                    rejection_reason: result.value
                })
                .done(() => {
                    Swal.fire({ title: 'Ditolak', text: 'Permintaan berhasil ditolak.', icon: 'success', timer: 1500, showConfirmButton: false, padding: '1.5rem' }).then(() => location.reload());
                })
                .fail((xhr) => {
                    $(`#loading-${id}`).fadeOut();
                    Swal.fire('Error', xhr.responseJSON?.message || 'Gagal', 'error');
                });
            }
        });
    }

    function cleanupOldRequests() {
        Swal.fire({
            title: 'Hapus Data Lama?',
            text: "Data permintaan yang lebih dari 3 bulan akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            padding: '1.5rem'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.resend.cleanup') }}",
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function() { location.reload(); }
                });
            }
        });
    }
</script>
@endpush

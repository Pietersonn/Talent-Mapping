@extends('mitra.layouts.app')

@section('title', 'Program Mitra')

@push('styles')
<style>
    /* --- SEARCH BAR --- */
    .search-group { position: relative; width: 300px; }
    .search-input { width: 100%; padding: 10px 12px 10px 40px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.875rem; background: white; transition: all 0.2s; }
    .search-input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1); }
    .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .loading-spinner { position: absolute; right: 12px; top: 33%; transform: translateY(-50%); color: #22c55e; display: none; }

    /* Tombol Print */
    .btn-print {
        width: 44px; height: 44px;
        background: white; border: 1px solid #e2e8f0;
        border-radius: 12px; display: flex; align-items: center; justify-content: center;
        color: #64748b; cursor: pointer; transition: all 0.2s; text-decoration: none;
    }
    .btn-print:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; transform: translateY(-1px); }

    /* --- TABLE STYLES --- */
    .table-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { text-align: left; padding: 1.25rem; background: #f8fafc; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 1.25rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.9rem; color: #334155; background: white; }
    .custom-table tr:hover td { background-color: #f8fafc; }

    /* --- COMPONENTS --- */
    .event-info { display: flex; flex-direction: column; }
    .event-name { font-weight: 700; color: #0f172a; font-size: 0.95rem; }
    .event-code { font-family: monospace; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; width: fit-content; margin-top: 4px; }

    .status-dot { height: 8px; width: 8px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    .dot-active { background-color: #22c55e; }
    .dot-inactive { background-color: #ef4444; }

    .action-buttons { display: flex; gap: 8px; justify-content: flex-start; }
    .btn-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .btn-view { background: #ecfdf5; color: #059669; }
    .btn-icon:hover { opacity: 0.8; transform: scale(1.05); }
</style>
@endpush

@section('header')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-calendar-alt" style="color: #22c55e; background: #dcfce7; padding: 10px; border-radius: 12px; font-size: 1.1rem;"></i>
                Program Saya
            </h1>
        </div>

        <div style="display: flex; gap: 12px; align-items: center;">
            <div class="search-group">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="realtimeSearch" class="search-input" placeholder="Cari Nama Program, Kode..." autocomplete="off">
                <i class="fas fa-circle-notch fa-spin loading-spinner"></i>
            </div>

            <a href="{{ route('mitra.programs.export.pdf', request()->query()) }}" class="btn-print" id="btnExportPdf" title="Cetak PDF" target="_blank">
                <i class="fas fa-print"></i>
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="table-card">
        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="35%">Nama Program</th>
                        <th width="30%">Instansi</th>
                        <th width="20%">Peserta</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="eventTableBody">
                    @forelse($programs as $program)
                        <tr>
                            <td>
                                <div class="event-info">
                                    <div class="event-name">
                                        @if($program->aktif)
                                            <span class="status-dot dot-active" title="Aktif"></span>
                                        @else
                                            <span class="status-dot dot-inactive" title="Tidak Aktif"></span>
                                        @endif
                                        {{ $program->nama }}
                                    </div>
                                    <div class="event-code">{{ $program->kode_program }}</div>
                                </div>
                            </td>
                            <td style="color: #64748b; font-weight: 500;">{{ $program->perusahaan ?? '-' }}</td>
                            <td>
                                <span style="font-weight: 700; color: #22c55e;">{{ $program->participants_count }}</span>
                                <span style="color: #cbd5e1;">/</span>
                                <span style="color: #94a3b8;">{{ $program->maks_peserta ?? '∞' }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('mitra.programs.show', $program->id) }}" class="btn-icon btn-view" title="Detail"><i class="fas fa-eye text-xs"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; padding: 3rem; color: #94a3b8;">Anda belum memiliki program.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        {{ $programs->appends(request()->query())->links() }}
    </div>
@endsection

@push('scripts')
<script>
    let debounceTimer;

    // --- PENCARIAN REALTIME (AJAX) ---
    $('#realtimeSearch').on('input', function() {
        const query = $(this).val();
        $('.loading-spinner').show();
        $('.search-icon').hide();

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            $.ajax({
                url: "{{ route('mitra.programs.index') }}",
                type: "GET",
                data: { search: query },
                success: function(response) {
                    renderTable(response);
                    $('.loading-spinner').hide();
                    $('.search-icon').show();
                    updateExportUrl(query);
                },
                error: function() {
                    $('.loading-spinner').hide();
                    $('.search-icon').show();
                }
            });
        }, 500);
    });

    function updateExportUrl(searchQuery) {
        let baseUrl = "{{ route('mitra.programs.export.pdf') }}";
        let params = new URLSearchParams(window.location.search);
        if (searchQuery) { params.set('search', searchQuery); } else { params.delete('search'); }
        $('#btnExportPdf').attr('href', baseUrl + "?" + params.toString());
    }

    function renderTable(response) {
        const tbody = $('#eventTableBody');
        const programs = response.programs.data;
        tbody.empty();

        if (programs.length === 0) {
            tbody.html('<tr><td colspan="4" style="text-align:center; padding: 3rem; color: #94a3b8;">Tidak ada data program ditemukan.</td></tr>');
            return;
        }

        let html = '';
        programs.forEach(program => {
            let dotClass = program.aktif ? 'dot-active' : 'dot-inactive';
            let maxPart = program.maks_peserta ? program.maks_peserta : '∞';
            let company = program.perusahaan ? program.perusahaan : '-';

            html += `<tr>
                <td>
                    <div class="event-info">
                        <div class="event-name">
                            <span class="status-dot ${dotClass}"></span>
                            ${program.nama}
                        </div>
                        <div class="event-code">${program.kode_program}</div>
                    </div>
                </td>
                <td style="color: #64748b; font-weight: 500;">${company}</td>
                <td>
                    <span style="font-weight: 700; color: #22c55e;">${program.participants_count}</span>
                    <span style="color: #cbd5e1;">/</span>
                    <span style="color: #94a3b8;">${maxPart}</span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="${program.show_url}" class="btn-icon btn-view" title="Detail"><i class="fas fa-eye text-xs"></i></a>
                    </div>
                </td>
            </tr>`;
        });
        tbody.html(html);
    }
</script>
@endpush

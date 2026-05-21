@extends('admin.layouts.app')

@section('title', 'Peserta Assessment')

@push('styles')
<style>
    /* --- SEARCH & FILTER STYLE --- */
    .filter-wrapper { display: flex; gap: 10px; align-items: center; }

    .search-group { position: relative; width: 280px; }
    .search-input { width: 100%; height: 46px; padding: 10px 45px 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; background: #ffffff; transition: all 0.3s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); color: #334155; }
    .search-input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15); }

    .event-select { height: 46px; padding: 0 36px 0 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; background-color: #ffffff; color: #334155; cursor: pointer; transition: all 0.3s; min-width: 200px; -webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; }
    .event-select:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15); }

    .loading-spinner { position: absolute; right: 14px; top: 33%; transform: translateY(-50%); display: none; color: #22c55e; font-size: 1.1rem; pointer-events: none; }
    .search-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1rem; pointer-events: none; transition: opacity 0.2s; }

    .btn-print { width: 46px; height: 46px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all 0.2s; text-decoration: none; }
    .btn-print:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; transform: translateY(-1px); }

    /* --- TABLE STYLE --- */
    .table-card { background: white; border: 1px solid #f1f5f9; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { text-align: left; padding: 1.25rem; background: #f8fafc; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 1.25rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.9rem; color: #334155; background: white; }
    .custom-table tr:hover td { background-color: #f8fafc; }

    .badge-event { background: #f0fdf4; color: #166534; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; border: 1px solid #bbf7d0; display: inline-block; white-space: nowrap;}

    .action-buttons { display: flex; gap: 8px; }
    .btn-pdf-result { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 0.8rem; text-decoration: none; transition: 0.2s; display: inline-flex; align-items: center; gap: 6px; }
    .btn-pdf-result:hover { background: #fca5a5; color: #991b1b; transform: translateY(-1px); }

    .pagination-wrapper { padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .btn-paginate { background: white; border: 1px solid #e2e8f0; color: #22c55e; padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 0.85rem; text-decoration: none; transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-paginate:hover:not(.disabled) { background: #f0fdf4; border-color: #22c55e; color: #15803d; transform: translateY(-1px); }
    .btn-paginate.disabled { color: #94a3b8; background: #f8fafc; cursor: not-allowed; opacity: 0.7; }
</style>
@endpush

@section('header')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-poll" style="color: #22c55e; background: #dcfce7; padding: 10px; border-radius: 12px; font-size: 1.1rem;"></i>
                Hasil Assessment
            </h1>
            <p style="font-size: 0.9rem; color: #64748b; margin-left: 54px; margin-top: -5px;">Laporan hasil tes yang telah diselesaikan oleh peserta.</p>
        </div>

        <div class="filter-wrapper">
            <select id="eventFilter" class="event-select">
                <option value="">Semua Program</option>
                {{-- FIX: Tambahkan perulangan disini agar data program muncul di dropdown --}}
                @foreach($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->nama }}</option>
                @endforeach
            </select>

            <div class="search-group">
                <input type="text" id="realtimeSearch" class="search-input" placeholder="Cari peserta atau email..." autocomplete="off">
                <i class="fas fa-search search-icon"></i>
                <i class="fas fa-circle-notch fa-spin loading-spinner"></i>
            </div>

            <a href="{{ route('admin.results.export.pdf') }}" id="btnExportPdf" target="_blank" class="btn-print" title="Cetak Rekap">
                <i class="fas fa-print"></i>
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="table-card">
        <div style="overflow-x: auto;">
            <table class="custom-table" id="resultsTable">
                <thead>
                    <tr>
                        <th width="25%">Peserta</th>
                        <th width="15%">No. Telepon</th>
                        <th width="20%">Program</th>
                        <th width="15%">Instansi</th>
                        <th width="15%">Jabatan</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="resultTableBody">
                    {{-- Dirender oleh AJAX --}}
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper" id="paginationWrapper">
            {{-- Pagination dirender oleh AJAX --}}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let debounceTimer;

    const baseExportUrl = "{{ route('admin.results.export.pdf') }}";
    const baseDownloadUrl = "{{ url('admin/results') }}";

    $(document).ready(function () {
        fetchResults();
    });

    // SEARCH & FILTER
    $('#realtimeSearch, #eventFilter').on('input change', function () {

        const query = $('#realtimeSearch').val();
        const programId = $('#eventFilter').val();

        $('.loading-spinner').show();
        $('.search-icon').hide();

        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {

            fetchResults(1, query, programId);
            updateExportUrl(query, programId);

        }, 500);
    });

    // PAGINATION
    $(document).on('click', '.btn-paginate:not(.disabled)', function (e) {

        e.preventDefault();

        const url = new URL($(this).attr('href'));

        const page = url.searchParams.get('page');

        const query = $('#realtimeSearch').val();
        const programId = $('#eventFilter').val();

        fetchResults(page, query, programId);
    });

    // EXPORT URL
    function updateExportUrl(search, programId) {

        let params = new URLSearchParams();

        if (search) {
            params.append('search', search);
        }

        if (programId) {
            params.append('program_id', programId);
        }

        const finalUrl = params.toString()
            ? baseExportUrl + "?" + params.toString()
            : baseExportUrl;

        $('#btnExportPdf').attr('href', finalUrl);
    }

    // FETCH DATA
    function fetchResults(page = 1, search = '', programId = '') {

        $.ajax({

            url: "{{ route('admin.results.index') }}",
            type: "GET",

            data: {
                page: page,
                search: search,
                program_id: programId
            },

            success: function (response) {

                renderTable(response.results.data);
                renderPagination(response.results);

                $('.loading-spinner').hide();
                $('.search-icon').show();
            },

            error: function () {

                $('.loading-spinner').hide();
                $('.search-icon').show();

                $('#resultTableBody').html(`
                    <tr>
                        <td colspan="6"
                            style="text-align:center;
                                   padding: 3rem;
                                   color: #ef4444;">

                            Gagal memuat data.
                        </td>
                    </tr>
                `);
            }
        });
    }

    // RENDER TABLE
    function renderTable(results) {

        const tbody = $('#resultTableBody');

        tbody.empty();

        if (results.length === 0) {

            tbody.html(`
                <tr>
                    <td colspan="6"
                        style="text-align:center;
                               padding: 4rem;
                               color: #94a3b8;">

                        Belum ada data hasil tes peserta.
                    </td>
                </tr>
            `);

            return;
        }

        let html = '';

        results.forEach(result => {

            const session = result.session || {};
            const user = session.user || {};
            const program = session.program || {};

            // FIELD DB BARU
            const name = user.nama || '-';

            const email = user.email || '-';

            const phone = user.nomor_telepon || '-';

            const instansi = session.latar_belakang || '-';

            const jabatan = session.jabatan || '-';

            const progName = program.nama || '-';

            // PROGRAM BADGE
            const programBadge = progName !== '-'
                ? `<span class="badge-event">${progName}</span>`
                : '<span style="color: #94a3b8;">-</span>';

            // DOWNLOAD URL
            const downloadUrl =
                `${baseDownloadUrl}/${result.id}/download-pdf`;

            html += `
                <tr>

                    <td>
                        <div style="font-weight: 700; color: #0f172a;">
                            ${name}
                        </div>

                        <div style="font-size: 0.75rem; color: #64748b;">
                            ${email}
                        </div>
                    </td>

                    <td style="font-family: monospace; color: #334155;">
                        ${phone}
                    </td>

                    <td>
                        ${programBadge}
                    </td>

                    <td>
                        ${instansi}
                    </td>

                    <td>
                        ${jabatan}
                    </td>

                    <td>
                        <div class="action-buttons">

                            <a href="${downloadUrl}"
                               class="btn-pdf-result"
                               target="_blank"
                               title="Lihat PDF Hasil">

                                <i class="fas fa-file-pdf"></i>
                                Result
                            </a>

                        </div>
                    </td>

                </tr>
            `;
        });

        tbody.html(html);
    }

    // PAGINATION
    function renderPagination(paginator) {

        const wrapper = $('#paginationWrapper');

        if (paginator.last_page <= 1) {

            wrapper.html('');

            return;
        }

        let prevClass =
            paginator.current_page === 1
                ? 'btn-paginate disabled'
                : 'btn-paginate';

        let prevUrl =
            paginator.prev_page_url
                ? paginator.prev_page_url
                : '#';

        let nextClass =
            paginator.current_page === paginator.last_page
                ? 'btn-paginate disabled'
                : 'btn-paginate';

        let nextUrl =
            paginator.next_page_url
                ? paginator.next_page_url
                : '#';

        let html = `
            <div style="font-size: 0.85rem; color: #64748b;">

                Halaman

                <span style="font-weight: 700; color: #22c55e;">
                    ${paginator.current_page}
                </span>

                dari ${paginator.last_page}

            </div>

            <div style="display: flex; gap: 10px;">

                <a href="${prevUrl}"
                   class="${prevClass}">

                    <i class="fas fa-chevron-left mr-1"></i>
                    Sebelumnya

                </a>

                <a href="${nextUrl}"
                   class="${nextClass}">

                    Selanjutnya
                    <i class="fas fa-chevron-right ml-1"></i>

                </a>

            </div>
        `;

        wrapper.html(html);
    }

</script>
@endpush

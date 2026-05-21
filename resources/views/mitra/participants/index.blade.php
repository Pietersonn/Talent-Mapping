@extends('mitra.layouts.app')

@section('title', 'Peserta')

@push('styles')
<style>
    /* --- FILTER & SEARCH --- */
    .filter-wrapper { display: flex; gap: 10px; align-items: center; }
    .search-group { position: relative; width: 280px; }
    .search-input { width: 100%; height: 46px; padding: 10px 45px 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; background: #ffffff; transition: all 0.3s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); color: #334155; }
    .search-input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15); }

    .event-select { height: 46px; padding: 0 36px 0 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; background-color: #ffffff; color: #334155; cursor: pointer; transition: all 0.3s; min-width: 200px; -webkit-appearance: none; appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; }
    .event-select:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15); }

    .loading-spinner { position: absolute; right: 14px; top: 33%; transform: translateY(-50%); display: none; color: #22c55e; }
    .search-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }

    .btn-print { width: 46px; height: 46px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all 0.2s; text-decoration: none; flex-shrink: 0; }
    .btn-print:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; transform: translateY(-1px); }

    /* --- TABLE --- */
    .table-card { background: white; border: 1px solid #f1f5f9; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { text-align: left; padding: 1.25rem; background: #f8fafc; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 1.25rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.9rem; color: #334155; background: white; }
    .custom-table tr:hover td { background-color: #f8fafc; }

    .action-buttons { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-pdf-result { background-color: #eff6ff; color: #2563eb; padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
    .btn-pdf-result:hover { background-color: #dbeafe; color: #1d4ed8; transform: translateY(-1px); }

    .badge-event { background: #e0f2fe; color: #0369a1; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
    .score-value { font-weight: 700; color: #0f172a; }

    @media (max-width: 991px) {
        .header-wrapper { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .filter-wrapper { width: 100%; flex-wrap: wrap; }
        .search-group, .event-select { width: 100%; }
    }
</style>
@endpush

@section('header')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center;">
                <i class="fas fa-users" style="color: #22c55e; background: #dcfce7; padding: 10px; border-radius: 12px; margin-right: 12px; font-size: 1.1rem;"></i>
                Peserta
            </h1>
        </div>

        <div class="filter-wrapper">
            <select id="eventFilter" class="event-select">
                <option value="">Semua Program Saya</option>

                @foreach($programs as $program)
                    <option value="{{ $program->id }}"
                        {{ request('program_id') == $program->id ? 'selected' : '' }}>

                        {{ Str::limit($program->nama, 25) }}
                        ({{ $program->kode_program }})
                    </option>
                @endforeach
            </select>

            <div class="search-group">
                <input
                    type="text"
                    id="realtimeSearch"
                    class="search-input"
                    placeholder="Cari Nama, Email..."
                    autocomplete="off"
                    value="{{ request('search') }}"
                >

                <i class="fas fa-search search-icon"></i>
                <i class="fas fa-circle-notch fa-spin loading-spinner"></i>
            </div>

            <a href="{{ route('mitra.participants.export-pdf', request()->query()) }}"
               class="btn-print"
               id="btnExportPdf"
               target="_blank"
               title="Export PDF">

                <i class="fas fa-print"></i>
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="table-card fade-in-up">

        <div style="overflow-x: auto;">
            <table class="custom-table">

                <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th width="20%">Nama Peserta</th>
                        <th width="20%">Email</th>
                        <th width="20%">Program</th>
                        <th width="15%">Instansi</th>
                        <th width="10%" class="text-center">Skor</th>
                        <th width="15%" style="text-align: center;">Aksi</th>
                    </tr>
                </thead>

                <tbody id="tableBody">

                    @php
                        $baseNo = isset($rows)
                            ? ($rows->currentPage() - 1) * $rows->perPage()
                            : 0;
                    @endphp

                    @forelse($rows as $i => $row)
                        <tr>

                            <td class="text-center" style="color: #94a3b8;">
                                {{ $baseNo + $i + 1 }}
                            </td>

                            <td>
                                <div style="font-weight: 700; color: #0f172a;">
                                    {{ $row->name }}
                                </div>
                            </td>

                            <td style="color: #64748b;">
                                {{ $row->email ?? '-' }}
                            </td>

                            <td>
                                @if($row->program_name)

                                    <span class="badge-event"
                                          title="{{ $row->program_name }}">

                                        {{ Str::limit($row->program_name, 20) }}
                                    </span>

                                    <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 2px; font-family: monospace;">
                                        {{ $row->program_code }}
                                    </div>

                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>

                            <td style="color: #334155;">
                                {{ $row->instansi ?? '-' }}
                            </td>

                            <td class="text-left">
                                @if(isset($row->total_score))
                                    <span class="score-value">
                                        {{ $row->total_score }}
                                    </span>
                                @else
                                    <span style="color: #cbd5e1;">-</span>
                                @endif
                            </td>

                            <td>
                                <div class="action-buttons">

                                    @if(!empty($row->download_url))

                                        <a href="{{ $row->download_url }}"
                                           class="btn-pdf-result"
                                           target="_blank">

                                            <i class="fas fa-file-pdf"></i>
                                            Result
                                        </a>

                                    @else
                                        <span style="font-size: 0.75rem; color: #cbd5e1;">
                                            Belum Selesai
                                        </span>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty

                        <tr>
                            <td colspan="7"
                                style="text-align:center; padding: 3rem; color: #94a3b8;">

                                <i class="fas fa-inbox fa-2x mb-3"
                                   style="color: #e2e8f0;"></i>

                                <br>

                                Tidak ada data peserta ditemukan.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-50" id="paginationWrapper">
            {{ $rows->appends(request()->query())->links() }}
        </div>

    </div>
@endsection

@push('scripts')
<script>
    let debounceTimer;

    const searchInput = $('#realtimeSearch');
    const eventSelect = $('#eventFilter');
    const exportBtn = $('#btnExportPdf');

    const tableBody = $('#tableBody');
    const paginationWrapper = $('#paginationWrapper');

    // Base URL Export
    const baseExportUrl = "{{ route('mitra.participants.export-pdf') }}";

    // --- FETCH DATA ---
    function fetchResults() {

        const searchQuery = searchInput.val();
        const programId = eventSelect.val();

        // Spinner
        $('.loading-spinner').show();
        $('.search-icon').hide();

        $.ajax({

            url: "{{ route('mitra.participants.index') }}",
            type: "GET",

            data: {
                search: searchQuery,
                program_id: programId
            },

            success: function(response) {

                // Render Table
                renderTable(response.data, response.from);

                // Pagination
                if(response.links) {
                    paginationWrapper.html(response.links);
                } else {
                    paginationWrapper.empty();
                }

                // Hide Spinner
                $('.loading-spinner').hide();
                $('.search-icon').show();

                // Update Export URL
                updateExportLink(searchQuery, programId);
            },

            error: function() {

                $('.loading-spinner').hide();
                $('.search-icon').show();

                console.error("Gagal mengambil data.");
            }
        });
    }

    // --- RENDER TABLE ---
    function renderTable(data, from) {

        tableBody.empty();

        if (data.length === 0) {

            tableBody.html(`
                <tr>
                    <td colspan="7"
                        style="text-align:center; padding: 3rem; color: #94a3b8;">

                        <i class="fas fa-inbox fa-2x mb-3"
                           style="color: #e2e8f0;"></i>

                        <br>

                        Tidak ada data peserta ditemukan.
                    </td>
                </tr>
            `);

            return;
        }

        let html = '';

        let currentNo = from ? from : 1;

        data.forEach(row => {

            // Data
            let email = row.email ? row.email : '-';

            let eventName = row.program_name_short
                ? `<span class="badge-event" title="${row.program_name}">${row.program_name_short}</span>`
                : '<span style="color:#94a3b8">-</span>';

            let eventCode = row.program_code
                ? `<div style="font-size: 0.7rem; color: #94a3b8; margin-top: 2px; font-family: monospace;">${row.program_code}</div>`
                : '';

            let instansi = row.instansi
                ? row.instansi
                : '-';

            let score = row.total_score !== null
                ? `<span class="score-value">${row.total_score}</span>`
                : '<span style="color:#cbd5e1">-</span>';

            // Action
            let action = '';

            if (row.download_url) {

                action = `
                    <a href="${row.download_url}"
                       class="btn-pdf-result"
                       target="_blank">

                        <i class="fas fa-file-pdf"></i>
                        Result
                    </a>
                `;

            } else {

                action = `
                    <span style="font-size: 0.75rem; color: #cbd5e1;">
                        Belum Selesai
                    </span>
                `;
            }

            // HTML
            html += `
                <tr>

                    <td class="text-center" style="color: #94a3b8;">
                        ${currentNo}
                    </td>

                    <td>
                        <div style="font-weight: 700; color: #0f172a;">
                            ${row.name}
                        </div>
                    </td>

                    <td style="color: #64748b;">
                        ${email}
                    </td>

                    <td>
                        ${eventName}
                        ${eventCode}
                    </td>

                    <td style="color: #334155;">
                        ${instansi}
                    </td>

                    <td class="text-center">
                        ${score}
                    </td>

                    <td>
                        <div class="action-buttons">
                            ${action}
                        </div>
                    </td>

                </tr>
            `;

            currentNo++;
        });

        tableBody.html(html);
    }

    // --- UPDATE EXPORT URL ---
    function updateExportLink(search, programId) {

        let params = new URLSearchParams();

        if (search) {
            params.append('search', search);
        }

        if (programId) {
            params.append('program_id', programId);
        }

        let finalUrl = baseExportUrl;

        if (params.toString()) {
            finalUrl += '?' + params.toString();
        }

        exportBtn.attr('href', finalUrl);
    }

    // --- EVENT LISTENERS ---

    // Search Typing
    searchInput.on('input', function() {

        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(fetchResults, 300);
    });

    // Filter Change
    eventSelect.on('change', function() {

        fetchResults();
    });

</script>
@endpush

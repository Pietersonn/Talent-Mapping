@extends('admin.layouts.app')

@section('title', 'Peserta Assessment')

@push('styles')
<style>
    /* --- SEARCH & FILTER STYLE --- */
    .filter-wrapper { display: flex; gap: 10px; align-items: center; }

    .search-group { position: relative; width: 280px; }
    .search-input { width: 100%; height: 46px; padding: 10px 45px 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; background: #ffffff; transition: all 0.3s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); color: #334155; }
    .search-input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15); }

    .event-select { height: 46px; padding: 0 36px 0 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; background-color: #ffffff; color: #334155; cursor: pointer; transition: all 0.3s; min-width: 200px; -webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; }
    .event-select:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15); }

    .loading-spinner { position: absolute; right: 14px; top: 33%; transform: translateY(-50%); display: none; color: #22c55e; font-size: 1.1rem; pointer-events: none; }
    .search-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1rem; pointer-events: none; transition: opacity 0.2s; }

    .btn-print { width: 46px; height: 46px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all 0.2s; text-decoration: none; flex-shrink: 0; }
    .btn-print:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; transform: translateY(-1px); }

    /* --- TABLE STYLE --- */
    .table-card { background: white; border: 1px solid #f1f5f9; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { text-align: left; padding: 1.25rem; background: #f8fafc; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 1.25rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.9rem; color: #334155; background: white; }
    .custom-table tr:hover td { background-color: #f8fafc; }

    .action-buttons { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-pdf-result { background-color: #eff6ff; color: #2563eb; padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; border: 1px solid transparent; }
    .btn-pdf-result:hover { background-color: #dbeafe; color: #1d4ed8; transform: translateY(-1px); }
    .badge-event { background: #e0f2fe; color: #0369a1; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }

    @media print { body { display: none; } }
</style>
@endpush

@section('header')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                <i class="fas fa-poll-h" style="color: #22c55e; background: #dcfce7; padding: 8px; border-radius: 8px; margin-right: 8px;"></i>
                Peserta
            </h1>
        </div>

        <div class="filter-wrapper">
            <select id="eventFilter" class="event-select">
                <option value="">Semua Event</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                        {{ Str::limit($event->name, 25) }}
                    </option>
                @endforeach
            </select>

            <div class="search-group">
                <input type="text" id="realtimeSearch" class="search-input" placeholder="Cari Peserta, Instansi, Jabatan..." autocomplete="off" value="{{ request('search') }}">
                <i class="fas fa-search search-icon"></i>
                <i class="fas fa-circle-notch fa-spin loading-spinner"></i>
            </div>

            <a href="{{ route('admin.results.export.pdf', request()->query()) }}"
               class="btn-print"
               id="btnExportPdf"
               target="_blank"
               title="Cetak Laporan Tabel">
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
                        <th width="20%">Nama Peserta</th>
                        <th width="15%">No Telp</th>
                        <th width="20%">Event</th>
                        <th width="20%">Instansi</th>
                        <th width="15%">Jabatan</th>
                        <th width="10%" style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="resultTableBody">
                    @forelse($results as $result)
                        @php
                            $session = $result->session;
                            $user = $session->user ?? null;
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: #0f172a;">
                                    {{ $session->participant_name ?? '-' }}
                                </div>
                                <div style="font-size: 0.75rem; color: #64748b;">
                                    {{ $user->email ?? '-' }}
                                </div>
                            </td>
                            <td style="font-family: monospace; color: #334155;">
                                {{ $user->phone_number ?? '-' }}
                            </td>
                            <td>
                                @if($session->event)
                                    <span class="badge-event">{{ $session->event->name }}</span>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td>{{ $session->participant_background ?? '-' }}</td>
                            <td>{{ $session->position ?? '-' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.results.download-pdf', $result->id) }}"
                                       class="btn-pdf-result"
                                       target="_blank"
                                       title="Lihat PDF Hasil">
                                        <i class="fas fa-file-pdf"></i> Hasil
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center; padding: 3rem; color: #94a3b8;">Tidak ada hasil assessment ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        {{ $results->appends(request()->query())->links() }}
    </div>
@endsection

@push('scripts')
<script>
    // --- VARIABLES ---
    let debounceTimer;
    const searchInput = $('#realtimeSearch');
    const eventSelect = $('#eventFilter');
    const exportBtn = $('#btnExportPdf');

    // Base URLs
    const baseExportUrl = "{{ route('admin.results.export.pdf') }}";
    const baseDownloadUrl = "{{ url('admin/results') }}";

    // --- FUNCTION: FETCH DATA ---
    function fetchResults() {
        const searchQuery = searchInput.val();
        const eventId = eventSelect.val();

        // Show Spinner
        $('.loading-spinner').show();
        $('.search-icon').hide();

        $.ajax({
            url: "{{ route('admin.results.index') }}",
            type: "GET",
            data: {
                search: searchQuery,
                event_id: eventId
            },
            success: function(response) {
                renderTable(response);

                $('.loading-spinner').hide();
                $('.search-icon').show();

                // Update URL Export PDF (agar filter terbawa saat print)
                updateExportLink(searchQuery, eventId);
            },
            error: function() {
                $('.loading-spinner').hide();
                $('.search-icon').show();
            }
        });
    }

    // --- FUNCTION: UPDATE EXPORT LINK ---
    function updateExportLink(search, eventId) {
        let params = new URLSearchParams();

        if (search) params.append('search', search);
        if (eventId) params.append('event_id', eventId);

        let finalUrl = baseExportUrl;
        if (params.toString()) {
            finalUrl += '?' + params.toString();
        }

        exportBtn.attr('href', finalUrl);
    }

    // --- EVENTS ---

    // 1. Search Typing (Debounce)
    searchInput.on('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchResults, 500);
    });

    // 2. Event Dropdown Change (Langsung fetch)
    eventSelect.on('change', function() {
        fetchResults();
    });

    // --- RENDER TABLE ---
    function renderTable(response) {
        const tbody = $('#resultTableBody');
        const results = response.results.data;

        tbody.empty();

        if (results.length === 0) {
            tbody.html('<tr><td colspan="6" style="text-align:center; padding: 3rem; color: #94a3b8;">Tidak ada hasil assessment ditemukan.</td></tr>');
            return;
        }

        let html = '';
        results.forEach(result => {
            const session = result.session || {};
            const user = session.user || {};
            const event = session.event || {};

            const name = session.participant_name || '-';
            const email = user.email || '-';
            const phone = user.phone_number || '-';
            const instansi = session.participant_background || '-';
            const jabatan = session.position || '-';
            const eventName = event.name ? `<span class="badge-event">${event.name}</span>` : '<span style="color: #94a3b8;">-</span>';
            const downloadUrl = `${baseDownloadUrl}/${result.id}/download-pdf`;

            html += `<tr>
                <td>
                    <div style="font-weight: 700; color: #0f172a;">${name}</div>
                    <div style="font-size: 0.75rem; color: #64748b;">${email}</div>
                </td>
                <td style="font-family: monospace; color: #334155;">${phone}</td>
                <td>${eventName}</td>
                <td>${instansi}</td>
                <td>${jabatan}</td>
                <td>
                    <div class="action-buttons">
                        <a href="${downloadUrl}" class="btn-pdf-result" target="_blank" title="Lihat PDF Hasil">
                            <i class="fas fa-file-pdf"></i> Result
                        </a>
                    </div>
                </td>
            </tr>`;
        });
        tbody.html(html);
    }
</script>
@endpush

@extends('admin.layouts.app')
@section('title', 'Kompetensi — Peserta')

@push('styles')
<style>
    /* --- FILTER & INPUT STYLES --- */
    .filter-card { background: white; border: 1px solid #f1f5f9; border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    .filter-wrapper { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

    /* Input & Select Base Style */
    .custom-input {
        height: 44px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.9rem;
        background-color: #ffffff;
        color: #334155;
        transition: all 0.3s;
    }
    .custom-input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15); }
    .custom-input:disabled { background-color: #f8fafc; color: #94a3b8; cursor: not-allowed; }

    /* Specific Inputs */
    .select-input {
        padding: 0 36px 0 12px;
        cursor: pointer;
        -webkit-appearance: none; -moz-appearance: none; appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat; background-position: right 10px center; background-size: 14px;
        min-width: 160px;
    }

    .search-group { position: relative; flex-grow: 1; min-width: 250px; }
    .search-input { width: 100%; padding: 0 40px 0 16px; }
    .search-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }

    .count-group { position: relative; width: 100px; }
    .count-input { width: 100%; padding: 0 35px 0 12px; }
    .count-label { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.75rem; pointer-events: none; }

    /* Buttons */
    .btn-print { width: 46px; height: 46px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all 0.2s; text-decoration: none; flex-shrink: 0; }
    .btn-print:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; transform: translateY(-1px); }


    /* --- TABLE STYLE --- */
    .table-card { background: white; border: 1px solid #f1f5f9; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { text-align: left; padding: 1.25rem 0.75rem; background: #f8fafc; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
    .custom-table td { padding: 1rem 0.75rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.9rem; color: #334155; background: white; }
    .custom-table tr:hover td { background-color: #f8fafc; }

    .th-center, .td-center { text-align: center !important; }

    @media print { body { display: none; } }
    @media (max-width: 992px) { .filter-wrapper { flex-direction: column; align-items: stretch; } .select-input, .search-group, .count-group { width: 100%; } }
</style>
@endpush

@section('content')
@php
    $mode = request('mode', 'all');
    $n = (int) request('n', 10);
    $eventId = request('event_id', '');
    $q = request('q', '');
@endphp

{{-- HEADER ROW --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0;">
            <i class="fas fa-chart-line" style="color: #22c55e; background: #dcfce7; padding: 8px; border-radius: 8px; margin-right: 8px;"></i>
            Kompetensi Peserta
        </h1>
    </div>


</div>

<section class="content">
    <div class="container-fluid p-0">

        {{-- FILTER ROW (CARD) --}}
        <div class="filter-card">
            <form method="GET" action="{{ route('admin.score.index') }}" id="filterForm">
                <div class="filter-wrapper">

                    {{-- 1. Filter Event --}}
                    <div style="flex-grow: 1; min-width: 200px;">
                        <select name="event_id" class="custom-input select-input w-100" onchange="document.getElementById('filterForm').submit()">
                            <option value="">— Semua Event —</option>
                            @foreach ($events ?? [] as $ev)
                                <option value="{{ $ev->id }}" {{ $eventId == $ev->id ? 'selected' : '' }}>
                                    {{ \Illuminate\Support\Str::limit($ev->name, 40) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. Filter Mode --}}
                    <div>
                        <select name="mode" id="modeSelect" class="custom-input select-input" onchange="handleModeChange()">
                            <option value="all" {{ $mode == 'all' ? 'selected' : '' }}>Semua Data</option>
                            <option value="top" {{ $mode == 'top' ? 'selected' : '' }}>Top Score</option>
                            <option value="bottom" {{ $mode == 'bottom' ? 'selected' : '' }}>Bottom Score</option>
                        </select>
                    </div>

                    {{-- 3. Count Input (Conditional) --}}
                    <div class="count-group">
                        <input type="number" name="n" id="countInput"
                               class="custom-input count-input"
                               value="{{ $n }}" min="1" max="1000"
                               {{ $mode === 'all' ? 'disabled' : '' }}>
                        <span class="count-label">Baris</span>
                    </div>

                    {{-- 4. Search Input --}}
                    <div class="search-group">
                        <input type="text" name="q" class="custom-input search-input"
                               placeholder="Cari Peserta / Instansi..."
                               value="{{ $q }}" autocomplete="off">
                        <i class="fas fa-search search-icon"></i>
                    </div>

                    {{-- Submit Button Hidden (Enter Key Works) --}}
                    <button type="submit" style="display: none;"></button>

                    {{-- 5. Export Button --}}
                    <a href="{{ route('admin.score.export.pdf', request()->query()) }}"
                       class="btn-print"
                       id="btnExportPdf"
                       target="_blank"
                       title="Export PDF (Sesuai Filter)">
                        <i class="fas fa-print"></i>
                    </a>

                </div>
            </form>
        </div>

        {{-- TABLE CARD --}}
        <div class="table-card">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th class="th-center" style="width:50px;">No.</th>
                            <th style="min-width: 200px;">Nama Peserta</th>
                            <th class="th-center" style="min-width: 120px;">Kontak</th>
                            <th class="th-center" title="Self Management">SM</th>
                            <th class="th-center" title="Creativity & Innovation">CIA</th>
                            <th class="th-center" title="Technical Skill">TS</th>
                            <th class="th-center" title="Working With Others">WWO</th>
                            <th class="th-center" title="Customer Awareness">CA</th>
                            <th class="th-center" title="Leadership">L</th>
                            <th class="th-center" title="Social Engagement">SE</th>
                            <th class="th-center" title="Problem Solving">PS</th>
                            <th class="th-center" title="Planning & Execution">PE</th>
                            <th class="th-center" title="Grit & Hardwork">GH</th>
                            <th class="th-center font-weight-bold" style="background:#f1f5f9; color:#0f172a;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $baseNo = isset($pagination)
                                ? (request()->integer('page', 1) - 1) * $pagination->perPage()
                                : 0;
                        @endphp
                        @forelse(($rows ?? []) as $i => $r)
                            <tr>
                                <td class="td-center text-muted font-weight-bold">{{ $baseNo + $i + 1 }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">{{ $r->name }}</span>
                                    </div>
                                </td>
                                <td class="td-center" style="font-family: monospace; color: #475569;">
                                    {{ $r->phone_number ?? '-' }}
                                </td>
                                <td class="td-center">{{ $r->SM ?? 0 }}</td>
                                <td class="td-center">{{ $r->CIA ?? 0 }}</td>
                                <td class="td-center">{{ $r->TS ?? 0 }}</td>
                                <td class="td-center">{{ $r->WWO ?? 0 }}</td>
                                <td class="td-center">{{ $r->CA ?? 0 }}</td>
                                <td class="td-center">{{ $r->L ?? 0 }}</td>
                                <td class="td-center">{{ $r->SE ?? 0 }}</td>
                                <td class="td-center">{{ $r->PS ?? 0 }}</td>
                                <td class="td-center">{{ $r->PE ?? 0 }}</td>
                                <td class="td-center">{{ $r->GH ?? 0 }}</td>
                                <td class="td-center font-weight-bold" style="color: #2563eb; background: #f8fafc;">
                                    {{ $r->total_score ?? 0 }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" style="text-align:center; padding: 4rem; color: #94a3b8;">
                                    <i class="fas fa-folder-open mb-3" style="font-size: 2rem; opacity: 0.5;"></i><br>
                                    Tidak ada data peserta ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @isset($pagination)
            <div class="mt-4 d-flex justify-content-end">
                {{ $pagination->links() }}
            </div>
        @endisset

    </div>
</section>

@push('scripts')
<script>
    // Base URL untuk Export PDF
    const baseExportUrl = "{{ route('admin.score.export.pdf') }}";

    function handleModeChange() {
        const modeSelect = document.getElementById('modeSelect');
        const countInput = document.getElementById('countInput');
        const form = document.getElementById('filterForm');

        // Logic Disable/Enable Input Count
        if (modeSelect.value === 'all') {
            countInput.disabled = true;
        } else {
            countInput.disabled = false;
        }

        // Submit form otomatis saat mode berubah
        form.submit();
    }

    // Update Link PDF secara dinamis saat input berubah
    function updateExportLink() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        // Hapus parameter 'n' jika mode == 'all' agar URL bersih
        if (formData.get('mode') === 'all') {
            params.delete('n');
        }

        document.getElementById('btnExportPdf').href = baseExportUrl + "?" + params.toString();
    }

    // Listeners
    document.getElementById('filterForm').addEventListener('change', updateExportLink);
    document.querySelector('input[name="q"]').addEventListener('input', updateExportLink);

    // Initial run
    document.addEventListener('DOMContentLoaded', function() {
        updateExportLink();
    });
</script>
@endpush
@endsection

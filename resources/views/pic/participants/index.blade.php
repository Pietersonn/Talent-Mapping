@extends('pic.layouts.app')
@section('title', 'Participants')

@section('content')

    @php
        // nilai dari server / query
        $mode = $mode ?? request('mode', 'all'); // all | top | bottom
        $n = (int) ($n ?? request('n', 10));
        $eventId = $filters['event_id'] ?? request('event_id', '');
        $instansi = $filters['instansi'] ?? request('instansi', '');
        $q = $filters['q'] ?? request('q', '');
    @endphp

    <style>
        /* === Compact tweaks (scoped) === */
        .part-compact .form-control,
        .part-compact .form-select {
            height: 34px;
            padding-top: .125rem;
            padding-bottom: .125rem;
        }

        .part-compact .input-group-text {
            height: 34px;
        }

        .part-compact .btn-sm {
            padding: .25rem .6rem;
        }

        .part-compact .card {
            border-radius: 12px;
        }

        .part-compact .table thead th {
            white-space: nowrap;
        }

        .part-compact .badge.rounded-pill {
            min-width: 42px;
            font-weight: 700;
        }

        @media (max-width: 991.98px) {
            .part-compact .filters-actions {
                justify-content: flex-start !important;
                margin-top: .25rem;
            }
        }
    </style>

    <div class="part-compact">

        {{-- HEADER --}}
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div>
                <h1 class="h5 mb-1">Participants</h1>
                <small class="text-muted">Pilih mode → atur count → Apply. Data hanya event milik Anda.</small>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-sm btn-danger" href="{{ route('pic.participants.export-pdf', request()->query()) }}">
                    <i class="bi bi-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="card mb-3">
            <div class="card-body pb-2">
                <form method="GET" id="filterForm">
                    <div class="row g-2 align-items-end">
                        {{-- MODE (Dropdown biar hemat tempat) --}}
                        <div class="col-12 col-lg-2">
                            <label for="mode" class="form-label mb-1 small fw-semibold">Mode</label>
                            <select id="mode" name="mode" class="form-select form-select-sm">
                                <option value="all" {{ $mode === 'all' ? 'selected' : '' }}>All</option>
                                <option value="top" {{ $mode === 'top' ? 'selected' : '' }}>Top</option>
                                <option value="bottom" {{ $mode === 'bottom' ? 'selected' : '' }}>Bottom</option>
                            </select>
                        </div>

                        {{-- COUNT --}}
                        <div class="col-6 col-lg-2">
                            <label for="countInput" class="form-label mb-1 small fw-semibold">Count</label>
                            <div class="input-group input-group-sm">
                                <input type="number" min="1" max="5000" name="n" id="countInput"
                                    class="form-control" value="{{ $n }}" {{ $mode === 'all' ? 'disabled' : '' }}>
                                <span class="input-group-text">rows</span>
                            </div>
                        </div>

                        {{-- EVENT --}}
                        <div class="col-12 col-lg-4">
                            <label for="event_id" class="form-label mb-1 small fw-semibold">Event</label>
                            <select id="event_id" name="event_id" class="form-select form-select-sm">
                                <option value="">— My Events —</option>
                                @foreach ($events ?? [] as $ev)
                                    <option value="{{ $ev->id }}"
                                        {{ (string) $eventId === (string) $ev->id ? 'selected' : '' }}>
                                        {{ $ev->name }} ({{ $ev->event_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ORGANIZATION --}}
                        <div class="col-12 col-lg-2">
                            <label for="instansi" class="form-label mb-1 small fw-semibold">Organization</label>
                            <input type="text" id="instansi" name="instansi" class="form-control form-control-sm"
                                placeholder="contains…" value="{{ $instansi }}">
                        </div>

                        {{-- SEARCH --}}
                        <div class="col-12 col-lg-2">
                            <label for="q" class="form-label mb-1 small fw-semibold">Search</label>
                            <div class="input-group input-group-sm">
                                <input type="text" id="q" name="q" class="form-control"
                                    placeholder="Name / Email" value="{{ $q }}">
                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                    onclick="document.getElementById('q').value=''; document.getElementById('filterForm').submit();">
                                    Clear
                                </button>
                            </div>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="col-12 d-flex justify-content-lg-end filters-actions">
                            <div class="d-flex gap-2">
                                <a href="{{ route('pic.participants.index') }}"
                                    class="btn btn-outline-secondary btn-sm">Reset</a>
                                <button type="submit" class="btn btn-success btn-sm">Apply</button>
                            </div>
                        </div>
                    </div>

                    {{-- Hint kecil di bawah filter --}}
                    <div class="text-muted small mt-1">
                        <span class="me-3">Mode: <strong
                                class="text-primary text-uppercase">{{ $mode }}</strong></span>
                        @if ($mode !== 'all')
                            <span class="me-3">Count: <strong>{{ $n }}</strong></span>
                        @endif
                        @if ($eventId)
                            <span class="badge bg-info-subtle text-dark border">Event filtered</span>
                        @endif
                        @if ($instansi)
                            <span class="badge bg-secondary-subtle text-dark border">Org: “{{ $instansi }}”</span>
                        @endif
                        @if ($q)
                            <span class="badge bg-secondary-subtle text-dark border">Search: “{{ $q }}”</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:56px;">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Organization</th>
                            <th>Event</th>
                            <th class="text-end" style="width:110px;">Score</th>
                            <th style="width:160px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $baseNo = isset($pagination)
                                ? (request()->integer('page', 1) - 1) * $pagination->perPage()
                                : 0;
                        @endphp
                        @forelse(($rows ?? []) as $i => $r)
                            @php

                                $param = $r->session_id;
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $baseNo + $i + 1 }}</td>
                                <td class="fw-semibold">{{ $r->name }}</td>
                                <td class="text-muted">{{ $r->email ?? '—' }}</td>
                                <td>{{ $r->instansi ?: '—' }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $r->event_name ?: ($r->event_code ?: '—') }}</span>
                                        @if ($r->event_code)
                                            <small class="text-muted">{{ $r->event_code }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="text-muted">{{ $r->total_score ?? '—' }}</span>
                                    </td>
                                <td>
                                    @if (!empty($r->pdf_path))
                                        <a href="{{ route('pic.participants.result-pdf', $param) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark-pdf me-1"></i> Result PDF
                                        </a>
                                    @else
                                        <span class="text-muted">No result</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @isset($pagination)
                <div class="card-footer py-2">
                    {{ $pagination->links() }}
                </div>
            @endisset
        </div>

    </div>

    {{-- JS: enable/disable Count berdasarkan Mode (tanpa auto-submit) --}}
    <script>
        (function() {
            var modeSel = document.getElementById('mode');
            var countInput = document.getElementById('countInput');

            function updateCountState() {
                if (modeSel.value === 'all') {
                    countInput.disabled = true;
                } else {
                    countInput.disabled = false;
                }
            }
            modeSel.addEventListener('change', updateCountState);
            updateCountState();
        })();
    </script>
@endsection

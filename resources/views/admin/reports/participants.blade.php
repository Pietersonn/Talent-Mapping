@extends('admin.layouts.app')
@section('title', 'Reports — Peserta')

@section('content')
    @php
        $mode = request('mode', 'all'); // all | top | bottom
        $n = (int) request('n', 10);
        $eventId = request('event_id', '');
        $instansi = request('instansi', '');
        $q = request('q', '');

        // build URL tombol mode sambil mempertahankan filter lain
        $baseQs = request()->query();
        $qsAll = array_merge($baseQs, ['mode' => 'all']);
        $qsTop = array_merge($baseQs, ['mode' => 'top']);
        $qsBottom = array_merge($baseQs, ['mode' => 'bottom']);
    @endphp

    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <h1 class="m-0">Peserta</h1>
                <small class="text-muted">All / Top N / Bottom N • tanpa filter tanggal</small>
            </div>
            <a class="btn btn-sm btn-danger" href="{{ route('admin.reports.pdf.participants', request()->query()) }}">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF
            </a>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- FILTERS (2 baris, Bootstrap only) --}}
            <div class="card mb-3">
                <div class="card-body pt-3 pb-2">
                    <form method="GET" id="filterForm">

                        {{-- ROW 1: Mode + Count + Actions --}}
                        <div class="form-row align-items-end mb-2">
                            {{-- Mode (3 tombol link) --}}
                            <div class="form-group col-xl-6 col-lg-7 col-md-8 mb-2">
                                <label class="font-weight-bold d-block mb-2">Mode</label>
                                <div class="d-flex flex-wrap">
                                    <a href="{{ route('admin.reports.participants', $qsAll) }}"
                                        class="btn {{ $mode === 'all' ? 'btn-primary' : 'btn-outline-primary' }} mr-2 mb-2">
                                        All
                                    </a>
                                    <a href="{{ route('admin.reports.participants', $qsTop) }}"
                                        class="btn {{ $mode === 'top' ? 'btn-primary' : 'btn-outline-primary' }} mr-2 mb-2">
                                        Top N
                                    </a>
                                    <a href="{{ route('admin.reports.participants', $qsBottom) }}"
                                        class="btn {{ $mode === 'bottom' ? 'btn-primary' : 'btn-outline-primary' }} mb-2">
                                        Bottom N
                                    </a>
                                </div>
                            </div>

                            {{-- Count --}}
                            <div class="form-group col-xl-2 col-lg-2 col-md-4 mb-2">
                                <label for="countInput" class="font-weight-bold">Count</label>
                                <div class="input-group">
                                    <input type="number" min="1" max="5000" name="n" id="countInput"
                                        class="form-control" value="{{ $n }}"
                                        {{ $mode === 'all' ? 'disabled' : '' }}>
                                    <div class="input-group-append">
                                        <span class="input-group-text">rows</span>
                                    </div>
                                </div>
                                <small class="text-muted">Active for Top/Bottom</small>
                            </div>

                            {{-- Actions (kanan) --}}
                            <div class="form-group col-xl-4 col-lg-3 col-md-12 mb-2">
                                <div class="d-flex justify-content-xl-end justify-content-lg-end justify-content-md-start">
                                    <a href="{{ route('admin.reports.participants') }}"
                                        class="btn btn-outline-secondary mr-2">Reset</a>
                                    <button type="submit" class="btn btn-success">Apply</button>
                                </div>
                            </div>
                        </div>

                        {{-- ROW 2: Event + Organization + Search --}}
                        <div class="form-row">
                            {{-- Event --}}
                            <div class="form-group col-xl-6 col-lg-7 col-md-12 mb-2">
                                <label for="event_id" class="font-weight-bold">Event</label>
                                <select id="event_id" name="event_id" class="custom-select">
                                    <option value="">— All Events —</option>
                                    @foreach ($events ?? [] as $ev)
                                        <option value="{{ $ev->id }}" {{ $eventId == $ev->id ? 'selected' : '' }}>
                                            {{ $ev->name }} ({{ $ev->event_code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Organization --}}
                            <div class="form-group col-xl-3 col-lg-5 col-md-6 mb-2">
                                <label for="instansi" class="font-weight-bold">Organization</label>
                                <input type="text" id="instansi" name="instansi" class="form-control"
                                    placeholder="contains…" value="{{ $instansi }}">
                            </div>

                            {{-- Search --}}
                            <div class="form-group col-xl-3 col-lg-5 col-md-6 mb-2">
                                <label for="q" class="font-weight-bold">Search</label>
                                <div class="input-group">
                                    <input type="text" id="q" name="q" class="form-control"
                                        placeholder="Name / Email" value="{{ $q }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="document.getElementById('q').value=''; document.getElementById('filterForm').submit();">
                                            Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                {{-- Ringkasan filter --}}
                @if ($mode || $eventId || $instansi || $q)
                    <div class="card-footer bg-white">
                        <span class="badge badge-light border">Mode: <strong
                                class="text-primary text-uppercase ml-1">{{ $mode }}</strong></span>
                        @if ($mode !== 'all')
                            <span class="badge badge-light border">Count: <strong
                                    class="ml-1">{{ $n }}</strong></span>
                        @endif
                        @if ($eventId)
                            <span class="badge badge-info">Event filtered</span>
                        @endif
                        @if ($instansi)
                            <span class="badge badge-secondary">Org: “{{ $instansi }}”</span>
                        @endif
                        @if ($q)
                            <span class="badge badge-secondary">Search: “{{ $q }}”</span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- TABLE --}}
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:56px;">#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Instansi</th>
                                <th>Event</th>
                                <th class="text-right" style="width:110px;">Skor</th>
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
                                    $score = (int) ($r->sum_top3 ?? 0);
                                    $tone = $score >= 12 ? 'success' : ($score >= 8 ? 'warning' : 'secondary');
                                @endphp
                                <tr>
                                    <td class="text-muted align-middle">{{ $baseNo + $i + 1 }}</td>
                                    <td class="align-middle">{{ $r->name }}</td>
                                    <td class="text-muted align-middle">{{ $r->email ?? '—' }}</td>
                                    <td class="align-middle">{{ $r->instansi ?: '—' }}</td>
                                    <td class="align-middle">
                                        {{ $r->event_name ?: ($r->event_code ?: '—') }}
                                        @if ($r->event_code)
                                            <div class="small text-muted">{{ $r->event_code }}</div>
                                        @endif
                                    </td>
                                    <td class="text-right align-middle">
                                        <span class="badge badge-{{ $tone }}">{{ $score }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Tidak ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @isset($pagination)
                    <div class="card-footer">{{ $pagination->links() }}</div>
                @endisset
            </div>

        </div>
    </section>

    <script>
        // Disable Count saat mode=all ketika halaman load
        (function() {
            var isAll = "{{ $mode }}" === 'all';
            var countInput = document.getElementById('countInput');
            if (countInput) countInput.disabled = isAll;
        })();
    </script>
@endsection

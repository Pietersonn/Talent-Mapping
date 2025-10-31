@extends('admin.layouts.app')
@section('title', 'Reports — Peserta')

@section('content')
@php
    $mode = request('mode', 'all'); // all | top | bottom
    $n = (int) request('n', 10);
    $eventId = request('event_id', '');
    $instansi = request('instansi', ''); // Filter instansi (dari query baseParticipantsQuery)
    $q = request('q', ''); // Filter search (dari query baseParticipantsQuery)

    // Build URL tombol mode sambil mempertahankan filter lain
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

        {{-- FILTERS --}}
        <div class="card mb-3">
            <div class="card-body pt-3 pb-2">
                <form method="GET" id="filterForm">

                    {{-- ROW 1: Mode + Count + Actions --}}
                    <div class="form-row align-items-end mb-2">
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

                        <div class="form-group col-xl-4 col-lg-3 col-md-12 mb-2">
                            <div class="d-flex justify-content-xl-end justify-content-lg-end justify-content-md-start">
                                <a href="{{ route('admin.reports.participants') }}" class="btn btn-outline-secondary mr-2">Reset</a>
                                <button type="submit" class="btn btn-success">Apply</button>
                            </div>
                        </div>
                    </div>

                    {{-- ROW 2: Event + Instansi + Search --}}
                    <div class="form-row">
                        <div class="form-group col-xl-4 col-lg-5 col-md-6 mb-2">
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

                        {{-- Filter Instansi --}}
                        <div class="form-group col-xl-4 col-lg-4 col-md-6 mb-2">
                             <label for="instansi" class="font-weight-bold">Instansi/Background</label>
                             <input type="text" id="instansi" name="instansi" class="form-control"
                                 placeholder="Instansi/Background" value="{{ $instansi }}">
                        </div>

                        <div class="form-group col-xl-4 col-lg-3 col-md-12 mb-2">
                            <label for="q" class="font-weight-bold">Search</label>
                            <div class="input-group">
                                <input type="text" id="q" name="q" class="form-control"
                                    placeholder="Name / Email" value="{{ $q }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button"
                                            onclick="document.getElementById('q').value=''; document.getElementById('instansi').value=''; document.getElementById('event_id').value=''; document.getElementById('filterForm').submit();">
                                        Clear Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
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
                            <th>No. Telp</th>
                            <th>Instansi</th>
                            <th>Jabatan</th> {{-- <--- HEADER JABATAN --- --}}
                            <th>Top Competency</th>
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
                                <td class="text-muted align-middle">{{ $baseNo + $i + 1 }}</td>
                                <td class="align-middle">{{ $r->name }}</td>
                                <td class="text-muted align-middle">{{ $r->email ?? '—' }}</td>
                                <td class="text-muted align-middle">{{ $r->phone_number ?? '—' }}</td>
                                <td class="text-muted align-middle">{{ $r->instansi ?? '—' }}</td>
                                <td class="text-muted align-middle">{{ $r->position ?? '—' }}</td> {{-- <--- DATA JABATAN --- --}}

                                <td class="align-middle">
                                    {{ $r->top_competency ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada data.</td> {{-- <--- Ubah colspan jadi 8 --- --}}
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

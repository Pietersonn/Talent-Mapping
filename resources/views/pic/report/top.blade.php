@extends('pic.layouts.app')
@section('title','Report • Top (Top 10)')

@section('content')
<form method="GET" class="row g-2 mb-3">
  <div class="col-auto">
    <select name="event_id" class="form-select">
      <option value="">All Events</option>
      @foreach($events as $e)
        <option value="{{ $e->id }}" {{ (string)$eventId === (string)$e->id ? 'selected' : '' }}>
          {{ $e->name }} ({{ $e->event_code }})
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-auto">
    <input type="text" name="instansi" class="form-control" placeholder="Filter instansi" value="{{ $instansiQ }}">
  </div>
  <div class="col-auto">
    <button class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
  </div>
  <div class="col-auto ms-auto">
    <a href="{{ route('pic.reports.top.pdf', request()->query()) }}" class="btn btn-outline-danger">
      <i class="bi bi-filetype-pdf"></i> Cetak PDF
    </a>
  </div>
</form>

<div class="card">
  <div class="card-body">
    <h6 class="mb-3">Top 10 — SJT (Jumlah 3 Kompetensi Tertinggi)</h6>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th><th>Participant</th><th>Instansi</th><th>Event</th><th class="text-end">Skor</th>
          </tr>
        </thead>
        <tbody>
        @forelse($rows as $i => $r)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>
              <div class="fw-semibold">{{ $r->name }}</div>
              <div class="text-muted small">{{ $r->email }}</div>
            </td>
            <td>{{ $r->instansi }}</td>
            <td>{{ $r->event_name }} <small class="text-muted">({{ $r->event_code }})</small></td>
            <td class="text-end"><strong>{{ $r->score }}</strong></td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted">No data.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <small class="text-muted">Skor = jumlah nilai pada `top3` SJT (3 kompetensi tertinggi) tiap peserta.</small>
  </div>
</div>
@endsection

@extends('admin.layouts.app')
@section('title','Reports — Anomali')

@section('content')
<div class="content-header">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <h1 class="m-0">Anomali (Durasi Terlalu Cepat)</h1>
    <div>
      <a class="btn btn-sm btn-danger" href="{{ route('admin.reports.pdf.anomaly', request()->query()) }}">
        <i class="fas fa-file-pdf"></i> Export PDF
      </a>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    @include('admin.reports._filters')

    <form method="GET" class="mb-3">
      <div class="form-inline">
        <label class="mr-2">Min Minutes</label>
        <input type="number" class="form-control form-control-sm mr-2" name="min_minutes" value="{{ $thresholdMin }}" min="1">
        <button class="btn btn-sm btn-primary">Apply</button>
      </div>
      @foreach(request()->except('min_minutes') as $k => $v)
        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
      @endforeach
    </form>

    <div class="card">
      <div class="card-body table-responsive p-0">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Instansi</th>
              <th>Event</th>
              <th>Durasi (menit)</th>
              <th>Flag</th>
            </tr>
          </thead>
          <tbody>
            @forelse($flagged as $i => $r)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $r['name'] }}</td>
                <td>{{ $r['email'] ?? '—' }}</td>
                <td>{{ $r['instansi'] ?: '—' }}</td>
                <td>{{ $eventMap[$r['event_id']] ?? $r['event_id'] }}</td>
                <td>{{ $r['duration_m'] }}</td>
                <td><span class="badge badge-danger">{{ $r['flag'] }}</span></td>
              </tr>
            @empty
              <tr><td colspan="7" class="text-center text-muted">Tidak ada data ter-flag.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
@endsection

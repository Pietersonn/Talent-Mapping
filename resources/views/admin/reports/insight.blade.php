@extends('admin.layouts.app')
@section('title','Reports — Lite Insight')

@section('content')
<div class="content-header">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <h1 class="m-0">Lite Insight (ST-30 & SJT)</h1>
    <a class="btn btn-sm btn-danger" href="{{ route('admin.reports.pdf.insight', request()->query()) }}">
      <i class="fas fa-file-pdf"></i> Export PDF
    </a>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    @include('admin.reports._filters')

    <div class="row">
      <div class="col-md-6">
        <div class="card card-outline card-info">
          <div class="card-header"><h3 class="card-title">ST-30 Dominant Typology (Top 10)</h3></div>
          <div class="card-body p-0">
            <table class="table table-sm">
              <thead><tr><th>#</th><th>Typology</th><th>Count</th></tr></thead>
              <tbody>
                @php $i=1; @endphp
                @forelse(array_slice($typoCount,0,10,true) as $name => $cnt)
                  <tr><td>{{ $i++ }}</td><td>{{ $name }}</td><td>{{ $cnt }}</td></tr>
                @empty
                  <tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-outline card-success">
          <div class="card-header"><h3 class="card-title">SJT — Rata-rata Skor Kompetensi (Top 10)</h3></div>
          <div class="card-body p-0">
            <table class="table table-sm">
              <thead><tr><th>#</th><th>Kompetensi</th><th>Avg Score</th></tr></thead>
              <tbody>
                @php $i=1; @endphp
                @forelse(array_slice($compAvg,0,10,true) as $name => $avg)
                  <tr><td>{{ $i++ }}</td><td>{{ $name }}</td><td>{{ $avg }}</td></tr>
                @empty
                  <tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="card card-outline card-secondary">
      <div class="card-header"><h3 class="card-title">SJT — Frekuensi Kompetensi Masuk Top-3</h3></div>
      <div class="card-body p-0">
        <table class="table table-sm">
          <thead><tr><th>#</th><th>Kompetensi</th><th>Frekuensi</th></tr></thead>
          <tbody>
            @php $i=1; @endphp
            @forelse($top3Freq as $name => $cnt)
              <tr><td>{{ $i++ }}</td><td>{{ $name }}</td><td>{{ $cnt }}</td></tr>
            @empty
              <tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
@endsection

@extends('admin.layouts.app')
@section('title','Reports — Data Quality')

@section('content')
<div class="content-header">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <h1 class="m-0">Data Quality</h1>
    <a class="btn btn-sm btn-danger" href="{{ route('admin.reports.pdf.data_quality', request()->query()) }}">
      <i class="fas fa-file-pdf"></i> Export PDF
    </a>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    @include('admin.reports._filters')

    <div class="row">
      <div class="col-md-4">
        <div class="card card-outline card-warning">
          <div class="card-header"><h3 class="card-title">Instansi Kosong</h3></div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush">
              @forelse($missingInstansi as $s)
                <li class="list-group-item">
                  {{ $s->participant_name }}
                </li>
              @empty
                <li class="list-group-item text-muted">Tidak ada.</li>
              @endforelse
            </ul>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card card-outline card-danger">
          <div class="card-header"><h3 class="card-title">Session Tanpa Result</h3></div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush">
              @forelse($noResult as $s)
                <li class="list-group-item">
                  {{ $s->participant_name }}
                </li>
              @empty
                <li class="list-group-item text-muted">Tidak ada.</li>
              @endforelse
            </ul>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card card-outline card-secondary">
          <div class="card-header"><h3 class="card-title">PDF Path Bermasalah</h3></div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush">
              @forelse($invalidPdf as $r)
                <li class="list-group-item">
                  {{ data_get($r,'session.participant_name') }} — <code>(empty)</code>
                </li>
              @empty
                <li class="list-group-item text-muted">Tidak ada.</li>
              @endforelse
            </ul>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection

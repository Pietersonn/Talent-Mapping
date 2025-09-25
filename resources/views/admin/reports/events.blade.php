@extends('admin.layouts.app')
@section('title','Reports — Event')

@section('content')
<div class="content-header">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <h1 class="m-0">Rekap per Event</h1>
    <a class="btn btn-sm btn-danger" href="{{ route('admin.reports.pdf.events', request()->query()) }}">
      <i class="fas fa-file-pdf"></i> Export PDF
    </a>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    @include('admin.reports._filters')

    <div class="card">
      <div class="card-body table-responsive p-0">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Event</th>
              <th>Periode</th>
              <th>Registered</th>
              <th>ST-30 Started</th>
              <th>SJT Started</th>
              <th>Completed</th>
              <th>Completion %</th>
              <th>Results Sent</th>
            </tr>
          </thead>
          <tbody>
            @forelse($summary as $i => $r)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $r['event_name'] }}</td>
                <td>{{ $r['period'] }}</td>
                <td>{{ $r['registered'] }}</td>
                <td>{{ $r['st30_started'] }}</td>
                <td>{{ $r['sjt_started'] }}</td>
                <td>{{ $r['completed'] }}</td>
                <td>{{ $r['completion_rate'] }}%</td>
                <td>{{ $r['results_sent'] }}</td>
              </tr>
            @empty
              <tr><td colspan="9" class="text-center text-muted">Tidak ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
@endsection

@extends('admin.layouts.app')
@section('title','Reports — Delivery & SLA')

@section('content')
<div class="content-header">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <h1 class="m-0">Delivery & SLA (PDF & Email)</h1>
    <a class="btn btn-sm btn-danger" href="{{ route('admin.reports.pdf.delivery', request()->query()) }}">
      <i class="fas fa-file-pdf"></i> Export PDF
    </a>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    @include('admin.reports._filters')

    <div class="card">
      <div class="card-body table-responsive p-0">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Event</th>
              <th>PDF Path</th>
              <th>Generated At</th>
              <th>Sent At</th>
              <th>SLA Generate (m)</th>
              <th>SLA Send (m)</th>
            </tr>
          </thead>
          <tbody>
            @forelse($mapped as $i => $r)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $r['name'] }}</td>
                <td>{{ $r['email'] ?? '—' }}</td>
                <td>{{ $eventMap[$r['event_id']] ?? $r['event_id'] }}</td>
                <td class="text-truncate" style="max-width:220px">{{ $r['pdf_path'] ?: '—' }}</td>
                <td>{{ $r['generated_at'] ?: '—' }}</td>
                <td>{{ $r['sent_at'] ?: '—' }}</td>
                <td>{{ $r['sla_generate'] !== null ? $r['sla_generate'] : '—' }}</td>
                <td>{{ $r['sla_send'] !== null ? $r['sla_send'] : '—' }}</td>
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

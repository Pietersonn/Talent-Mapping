@extends('admin.layouts.app')
@section('title','Reports — Resend Requests')

@section('content')
<div class="content-header">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <h1 class="m-0">Resend Requests</h1>
    <a class="btn btn-sm btn-danger" href="{{ route('admin.reports.pdf.resend', request()->query()) }}">
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
              <th>Nama</th>
              <th>Email</th>
              <th>Instansi</th>
              <th>Event</th>
              <th>Alasan</th>
              <th>Status</th>
              <th>Requested At</th>
              <th>Approved By</th>
              <th>Approved At</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rows as $i => $r)
              @php $evId = data_get($r,'result.session.event_id'); @endphp
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ data_get($r,'result.session.participant_name') }}</td>
                <td>{{ data_get($r,'result.session.participant_email') ?? '—' }}</td>
                <td>{{ data_get($r,'result.session.participant_background') ?: '—' }}</td>
                <td>{{ $eventMap[$evId] ?? $evId }}</td>
                <td class="text-truncate" style="max-width:220px">{{ $r->reason ?: '—' }}</td>
                <td>
                  <span class="badge badge-{{ $r->status === 'approved' ? 'success' : ($r->status === 'rejected' ? 'danger' : 'warning') }}">
                    {{ ucfirst($r->status) }}
                  </span>
                </td>
                <td>{{ optional($r->request_date)?->toDateTimeString() }}</td>
                <td>{{ $r->approved_by ?: '—' }}</td>
                <td>{{ optional($r->approved_at)?->toDateTimeString() ?: '—' }}</td>
              </tr>
            @empty
              <tr><td colspan="10" class="text-center text-muted">Tidak ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
@endsection

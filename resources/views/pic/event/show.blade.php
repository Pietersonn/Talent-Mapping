@extends('pic.layouts.app')
@section('title', ($event->name ?? 'Event').' — PIC Dashboard')

@section('content')
<div class="page-header mb-3">
  <h5 class="mb-1">
    {{ $event->name ?? 'Event' }} <small class="text-muted">({{ $event->event_code ?? '-' }})</small>
  </h5>
  <p class="text-muted mb-0">Detail event & daftar peserta.</p>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="card h-100"><div class="card-body">
      <div class="text-muted">Period</div>
      <div>
        {{ \Illuminate\Support\Carbon::parse($event->start_date)->format('d M Y') }}
        –
        {{ \Illuminate\Support\Carbon::parse($event->end_date)->format('d M Y') }}
      </div>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card h-100"><div class="card-body">
      <div class="text-muted">Status</div>
      <div>
        {!! ($event->is_active ?? false)
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-secondary">Inactive</span>' !!}
      </div>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card h-100"><div class="card-body">
      <div class="text-muted">Max Participants</div>
      <div>{{ $event->max_participants ?? '-' }}</div>
    </div></div>
  </div>
</div>

<h6 class="mb-2">Participants</h6>
<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Name</th><th>Email</th><th>Completed</th><th>Sent</th><th>Joined</th>
        </tr>
      </thead>
      <tbody>
      @forelse($participants ?? [] as $p)
        <tr>
          <td>{{ $p->user->name ?? '-' }}</td>
          <td>{{ $p->user->email ?? '-' }}</td>
          <td>{!! ($p->test_completed ?? false) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-warning text-dark">No</span>' !!}</td>
          <td>{!! ($p->results_sent ?? false) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
          <td>{{ optional($p->created_at)->format('d M Y H:i') }}</td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center text-muted">No participants yet.</td></tr>
      @endforelse
      </tbody>
    </table>

    @if(isset($participants) && method_exists($participants,'links'))
      <div class="mt-3">{{ $participants->links() }}</div>
    @endif
  </div>
</div>
@endsection

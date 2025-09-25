@extends('pic.layouts.app')
@section('title','Participants — PIC Dashboard')

@section('content')
<div class="page-header mb-3">
  <h5 class="mb-1">Participants</h5>
  <p class="text-muted mb-0">Semua peserta dari event yang kamu kelola.</p>
</div>

<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Name</th><th>Email</th><th>Event</th><th>Completed</th><th>Result Sent</th><th>Joined</th>
        </tr>
      </thead>
      <tbody>
      @forelse($participants ?? [] as $p)
        <tr>
          <td>{{ $p->name ?? ($p->user->name ?? '-') }}</td>
          <td>{{ $p->email ?? ($p->user->email ?? '-') }}</td>
          <td>{{ $p->event_name ?? ($p->event->name ?? '-') }} <small class="text-muted">({{ $p->event_code ?? ($p->event->event_code ?? '-') }})</small></td>
          <td>{!! ($p->test_completed ?? false) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-warning text-dark">No</span>' !!}</td>
          <td>{!! ($p->results_sent ?? false) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
          <td>{{ \Illuminate\Support\Carbon::parse($p->created_at ?? now())->format('d M Y H:i') }}</td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-center text-muted">No data.</td></tr>
      @endforelse
      </tbody>
    </table>

    @if(isset($participants) && method_exists($participants,'links'))
      <div class="mt-3">{{ $participants->links() }}</div>
    @endif
  </div>
</div>
@endsection

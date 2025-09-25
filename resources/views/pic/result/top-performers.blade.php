@extends('pic.layouts.app')
@section('title','Top Performers — PIC Dashboard')

@section('content')
<div class="page-header mb-3">
  <h5 class="mb-1">Top Performers</h5>
  <p class="text-muted mb-0">Peserta dengan skor terbaik (metrik sesuai field di DB).</p>
</div>

<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr><th>Participant</th><th>Email</th><th>Event</th><th>Score</th></tr>
      </thead>
      <tbody>
      @forelse($results ?? [] as $r)
        <tr>
          <td>{{ $r->session->user->name ?? '-' }}</td>
          <td>{{ $r->session->user->email ?? '-' }}</td>
          <td>{{ $r->session->event->name ?? '-' }} ({{ $r->session->event->event_code ?? '-' }})</td>
          <td>{{ $r->sjt_total_score ?? '-' }}</td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center text-muted">No data.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection


@extends('pic.layouts.app')
@section('title','Results — PIC Dashboard')

@section('content')
<div class="page-header mb-3">
  <h5 class="mb-1">Results</h5>
  <p class="text-muted mb-0">Hasil asesmen untuk event yang kamu kelola.</p>
</div>

<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Participant</th><th>Email</th><th>Event</th>
          <th>Generated</th><th>Email Sent</th><th class="text-end"></th>
        </tr>
      </thead>
      <tbody>
      @forelse($results ?? [] as $r)
        <tr>
          <td>{{ $r->session->user->name ?? '-' }}</td>
          <td>{{ $r->session->user->email ?? '-' }}</td>
          <td>{{ $r->session->event->name ?? '-' }} <small class="text-muted">({{ $r->session->event->event_code ?? '-' }})</small></td>
          <td>{{ optional($r->report_generated_at)->format('d M Y H:i') ?: '-' }}</td>
          <td>{{ optional($r->email_sent_at)->format('d M Y H:i') ?: '-' }}</td>
          <td class="text-end">
            <a href="{{ route('pic.results.show', $r->id) }}" class="btn btn-sm btn-primary">Detail</a>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-center text-muted">No results.</td></tr>
      @endforelse
      </tbody>
    </table>

    @if(isset($results) && method_exists($results,'links'))
      <div class="mt-3">{{ $results->links() }}</div>
    @endif
  </div>
</div>
@endsection

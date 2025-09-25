@extends('pic.layouts.app')
@section('title','My Events — PIC Dashboard')

@section('content')
<div class="page-header mb-3">
  <h5 class="mb-1">My Events</h5>
  <p class="text-muted mb-0">Daftar event yang kamu kelola sebagai PIC.</p>
</div>

<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Event</th>
          <th>Code</th>
          <th>Period</th>
          <th>Status</th>
          <th>Quota</th>
          <th class="text-end"></th>
        </tr>
      </thead>
      <tbody>
      @forelse($events ?? [] as $e)
        <tr>
          <td>{{ $e->name }}</td>
          <td><span class="badge bg-dark">{{ $e->event_code }}</span></td>
          <td>
            {{ \Illuminate\Support\Carbon::parse($e->start_date)->format('d M Y') }}
            –
            {{ \Illuminate\Support\Carbon::parse($e->end_date)->format('d M Y') }}
          </td>
          <td>
            {!! $e->is_active
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>' !!}
          </td>
          <td>{{ $e->max_participants ?? '-' }}</td>
          <td class="text-end">
            <a class="btn btn-sm btn-primary" href="{{ route('pic.events.show', $e->id) }}">
              Detail
            </a>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-center text-muted">No events.</td></tr>
      @endforelse
      </tbody>
    </table>

    @if(isset($events) && method_exists($events,'links'))
      <div class="mt-3">{{ $events->links() }}</div>
    @endif
  </div>
</div>
@endsection

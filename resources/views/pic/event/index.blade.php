@extends('pic.layouts.app')
@section('title','My Events')

@section('content')
<div class="content-header">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <h1 class="m-0">My Events</h1>
    @if(isset($events))
      <span class="badge bg-secondary">Total: {{ $events->count() }}</span>
    @endif
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <div class="card">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:72px;">#</th>
              <th>Event</th>
              <th style="width:180px;">Code</th>
              <th style="width:260px;">Period</th>
              <th style="width:160px;">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse(($events ?? collect()) as $i => $e)
              <tr>
                <td class="text-muted">{{ $i+1 }}</td>
                <td class="fw-semibold">{{ $e->name }}</td>
                <td class="text-muted">{{ $e->event_code ?? '—' }}</td>
                <td class="text-muted">
                  @if($e->start_date || $e->end_date)
                    {{ $e->start_date ?? '—' }} — {{ $e->end_date ?? '—' }}
                  @else
                    —
                  @endif
                </td>
                <td>
                  <a href="{{ route('pic.participants.index', ['event_id'=>$e->id, 'mode'=>'all']) }}"
                     class="btn btn-sm btn-primary">
                    View Participants
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">
                  You don’t have any events yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
@endsection

@extends('pic.layouts.app')
@section('title','Reports — PIC Dashboard')

@section('content')
<form method="GET" class="row g-2 mb-3">
  <div class="col-auto">
    <select name="event_id" class="form-select">
      <option value="">All Events</option>
      @foreach($events as $e)
        <option value="{{ $e->id }}" {{ (string)$eventId === (string)$e->id ? 'selected' : '' }}>
          {{ $e->name }} ({{ $e->event_code }})
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-auto">
    <button class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
  </div>
  @if($eventId)
  <div class="col-auto">
    <a href="{{ route('pic.reports.event.pdf', $eventId) }}" class="btn btn-outline-danger">
      <i class="bi bi-filetype-pdf"></i> Export Event PDF
    </a>
  </div>
  @endif
  <div class="col-auto ms-auto">
    <a href="{{ route('pic.reports.top', ['event_id'=>$eventId]) }}" class="btn btn-success">
      <i class="bi bi-trophy"></i> View Top Performers
    </a>
  </div>
</form>

<div class="row g-3">
  <div class="col-md-3">
    <div class="card h-100"><div class="card-body">
      <div class="text-muted">Events</div>
      <div class="display-6">{{ $summary['events'] ?? 0 }}</div>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card h-100"><div class="card-body">
      <div class="text-muted">Participants</div>
      <div class="display-6">{{ $summary['participants'] ?? 0 }}</div>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card h-100"><div class="card-body">
      <div class="text-muted">Completed</div>
      <div class="display-6">{{ $summary['completed'] ?? 0 }}</div>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card h-100"><div class="card-body">
      <div class="text-muted">Average SJT Total</div>
      <div class="display-6">{{ $summary['avg_sjt_total'] ?? 0 }}</div>
    </div></div>
  </div>
</div>

<div class="card mt-4">
  <div class="card-body">
    <h6 class="mb-3">Top Performers (Top 20)</h6>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th><th>Participant</th><th>Email</th><th>Event</th><th>SJT Total</th>
          </tr>
        </thead>
        <tbody>
        @forelse($top as $i => $r)
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $r->user_name }}</td>
            <td>{{ $r->user_email }}</td>
            <td>{{ $r->event_name }} <small class="text-muted">({{ $r->event_code }})</small></td>
            <td><strong>{{ $r->sjt_total }}</strong></td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted">No data.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@if(!empty($competencyAvg))
<div class="card mt-4">
  <div class="card-body">
    <h6 class="mb-3">Competency Average (SJT)</h6>
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead><tr><th>Competency</th><th>Average Score</th></tr></thead>
        <tbody>
        @foreach($competencyAvg as $comp => $avg)
          <tr><td>{{ $comp }}</td><td>{{ $avg }}</td></tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endif
@endsection

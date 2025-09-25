@extends('pic.layouts.app')
@section('title','Report • Participants')

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
    <input type="text" name="instansi" class="form-control" placeholder="Filter instansi"
           value="{{ $instansiQ }}">
  </div>
  <div class="col-auto">
    <button class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
  </div>
  <div class="col-auto ms-auto">
    <a href="{{ route('pic.reports.participants.pdf', request()->query()) }}" class="btn btn-outline-danger">
      <i class="bi bi-filetype-pdf"></i> Cetak PDF
    </a>
  </div>
</form>

<div class="card">
  <div class="card-body">
    <h6 class="mb-3">Participants</h6>
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead><tr>
          <th>Name</th><th>Email</th><th>Instansi</th><th>Event</th>
        </tr></thead>
        <tbody>
        @forelse($participants as $p)
          <tr>
            <td>{{ $p->name }}</td>
            <td>{{ $p->email }}</td>
            <td>{{ $p->instansi }}</td>
            <td>{{ $p->event_name }} <small class="text-muted">({{ $p->event_code }})</small></td>
          </tr>
        @empty
          <tr><td colspan="4" class="text-center text-muted">No participants.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    {{ $participants->links() }}
  </div>
</div>
@endsection

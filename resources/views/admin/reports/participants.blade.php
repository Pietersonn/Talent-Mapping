@extends('admin.layouts.app')
@section('title','Reports — Peserta')

@section('content')
<div class="content-header">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <h1 class="m-0">Peserta</h1>
    <a class="btn btn-sm btn-danger" href="{{ route('admin.reports.pdf.participants', request()->query()) }}">
      <i class="fas fa-file-pdf"></i> Export PDF
    </a>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    @include('admin.reports._filters')

    <div class="card">
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Instansi</th>
              <th>Event</th>
              <th>Skor</th>
            </tr>
          </thead>
          <tbody>
            @forelse($mapped as $i => $r)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $r['name'] }}</td>
                <td>{{ $r['email'] ?? '—' }}</td>
                <td>{{ $r['instansi'] ?: '—' }}</td>
                <td>{{ $eventMap[$r['event_id']] ?? $r['event_id'] }}</td>
                <td><strong>{{ $r['sum_top3'] }}</strong></td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted">Tidak ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
@endsection

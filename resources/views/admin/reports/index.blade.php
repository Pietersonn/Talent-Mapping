@extends('admin.layouts.app')
@section('title','Reports — Health Dashboard')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <h1 class="m-0">Health Dashboard</h1>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    @include('admin.reports._filters')

    <div class="mb-2">
      <a class="btn btn-sm btn-danger" href="{{ route('admin.reports.pdf.health', request()->query()) }}">
        <i class="fas fa-file-pdf"></i> Export PDF
      </a>
    </div>

    <div class="row">
      <div class="col-md-3">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $metrics['total_registered'] }}</h3>
            <p>Registered</p>
          </div>
          <div class="icon"><i class="fas fa-user-plus"></i></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ $metrics['sjt_started'] }}</h3>
            <p>SJT Started</p>
          </div>
          <div class="icon"><i class="fas fa-align-left"></i></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{ $metrics['completed'] }}</h3>
            <p>Completed</p>
          </div>
          <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="small-box bg-primary">
          <div class="inner">
            <h3>{{ $metrics['conversion_rate'] }}%</h3>
            <p>Completion Rate</p>
          </div>
          <div class="icon"><i class="fas fa-percent"></i></div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="card card-outline card-secondary">
          <div class="card-header"><h3 class="card-title">Median SLA Generate PDF</h3></div>
          <div class="card-body">
            <h4 class="mb-0">{{ $metrics['median_sla_generate'] !== null ? $metrics['median_sla_generate'].' menit' : '—' }}</h4>
            <small class="text-muted">Waktu dari Completed (proxy) → Report Generated</small>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-outline card-secondary">
          <div class="card-header"><h3 class="card-title">Median SLA Send Email</h3></div>
          <div class="card-body">
            <h4 class="mb-0">{{ $metrics['median_sla_send'] !== null ? $metrics['median_sla_send'].' menit' : '—' }}</h4>
            <small class="text-muted">Waktu dari Completed (proxy) → Email Sent</small>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection

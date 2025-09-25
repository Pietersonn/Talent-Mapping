@extends('pic.layouts.app')
@section('title','Result Detail — PIC Dashboard')

@section('content')
<div class="page-header mb-3">
  <h5 class="mb-1">Result Detail</h5>
  <p class="text-muted mb-0">Ringkasan hasil ST-30 & SJT peserta.</p>
</div>

<div class="card mb-3">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <div><strong>Participant</strong>: {{ $testResult->session->user->name ?? '-' }} ({{ $testResult->session->user->email ?? '-' }})</div>
        <div><strong>Event</strong>: {{ $testResult->session->event->name ?? '-' }} ({{ $testResult->session->event->event_code ?? '-' }})</div>
      </div>
      <div class="col-md-6">
        <div><strong>Generated</strong>: {{ optional($testResult->report_generated_at)->format('d M Y H:i') ?: '-' }}</div>
        <div><strong>Email Sent</strong>: {{ optional($testResult->email_sent_at)->format('d M Y H:i') ?: '-' }}</div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="card h-100"><div class="card-body">
      <h6 class="card-title mb-2">ST-30 Summary</h6>
      <pre class="mb-0" style="white-space:pre-wrap">{{ json_encode($testResult->st30_results ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
    </div></div>
  </div>
  <div class="col-md-6">
    <div class="card h-100"><div class="card-body">
      <h6 class="card-title mb-2">SJT Summary</h6>
      <pre class="mb-0" style="white-space:pre-wrap">{{ json_encode($testResult->sjt_results ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
    </div></div>
  </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Test Result Details')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Test Result Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.results.index') }}">All Results</a></li>
                    <li class="breadcrumb-item active">{{ optional($testResult->session)->participant_name ?? '-' }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Participant Info -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user mr-2"></i>Participant Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>{{ optional($testResult->session)->participant_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ optional(optional($testResult->session)->user)->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Background:</strong></td>
                                        <td>{{ optional($testResult->session)->participant_background ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Event:</strong></td>
                                        <td>
                                            @if(optional($testResult->session)->event)
                                                <span class="badge badge-info">{{ $testResult->session->event->name }}</span>
                                            @else
                                                <span class="text-muted">No Event</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Test Completed:</strong></td>
                                        <td>{{ optional(optional($testResult->session)->completed_at)->format('d M Y H:i') ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Report Generated:</strong></td>
                                        <td>{{ $testResult->report_generated_at ? $testResult->report_generated_at->format('d M Y H:i') : 'Not Generated' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email Sent:</strong></td>
                                        <td>
                                            @if($testResult->email_sent_at)
                                                <span class="badge badge-success">{{ $testResult->email_sent_at->format('d M Y H:i') }}</span>
                                            @else
                                                <span class="badge badge-warning">Not Sent</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dominant Typology:</strong></td>
                                        <td>
                                            @if($testResult->dominantTypologyDescription)
                                                <strong>{{ $testResult->dominantTypologyDescription->typology_name }}</strong>
                                                <small class="text-muted">({{ $testResult->dominant_typology }})</small>
                                            @else
                                                {{ $testResult->dominant_typology ?? 'N/A' }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ST-30 Results: SHOW ALL items (no descriptions) -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-2"></i>ST-30 Assessment Results
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Strengths (SEMUA) -->
                            <div class="col-md-6">
                                <h5 class="text-success">
                                    <i class="fas fa-star mr-1"></i>Strengths ({{ count($st30Details['strengths'] ?? []) }})
                                </h5>
                                @if(!empty($st30Details['strengths']))
                                    <div class="list-group">
                                        @foreach(($st30Details['strengths'] ?? []) as $strength)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>{{ $strength['name'] ?? 'N/A' }}</strong>
                                            <small class="text-muted">{{ $strength['code'] ?? 'N/A' }}</small>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No strengths data available.</p>
                                @endif
                            </div>

                            <!-- Development Areas (SEMUA) -->
                            <div class="col-md-6">
                                <h5 class="text-warning">
                                    <i class="fas fa-arrow-up mr-1"></i>Development Areas ({{ count($st30Details['weakness'] ?? []) }})
                                </h5>
                                @if(!empty($st30Details['weakness']))
                                    <div class="list-group">
                                        @foreach(($st30Details['weakness'] ?? []) as $weakness)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>{{ $weakness['name'] ?? 'N/A' }}</strong>
                                            <small class="text-muted">{{ $weakness['code'] ?? 'N/A' }}</small>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No development areas data available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SJT Results: only top/bottom 3 (name + score only) -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-brain mr-2"></i>SJT Assessment Results
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Top Competencies (Top 3) -->
                            <div class="col-md-6">
                                <h5 class="text-success">
                                    <i class="fas fa-trophy mr-1"></i>Top Competencies (Top 3)
                                </h5>
                                @php $sjtTop3 = collect($sjtDetails['top3'] ?? [])->take(3); @endphp
                                @if($sjtTop3->isNotEmpty())
                                    <div class="list-group">
                                        @foreach($sjtTop3 as $competency)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>{{ $competency['competency'] ?? $competency['name'] ?? 'N/A' }}</strong>
                                            <span class="badge badge-success">{{ number_format($competency['score'] ?? 0, 1) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No top competencies data available.</p>
                                @endif
                            </div>

                            <!-- Bottom Competencies (Top 3) -->
                            <div class="col-md-6">
                                <h5 class="text-warning">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Areas for Development (Top 3)
                                </h5>
                                @php $sjtBottom3 = collect($sjtDetails['bottom3'] ?? [])->take(3); @endphp
                                @if($sjtBottom3->isNotEmpty())
                                    <div class="list-group">
                                        @foreach($sjtBottom3 as $competency)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>{{ $competency['competency'] ?? $competency['name'] ?? 'N/A' }}</strong>
                                            <span class="badge badge-warning">{{ number_format($competency['score'] ?? 0, 1) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No development areas data available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommendations (from SJT bottom3 only; max 3 each) -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-lightbulb mr-2"></i>Recommendations
                        </h3>
                    </div>
                    <div class="card-body">
                        @php
                            // Ambil hanya dari 3 weakness SJT (bottom3)
                            $bottom3Only = collect($sjtDetails['bottom3'] ?? [])->take(3);
                            $allActivities = $bottom3Only->pluck('activity')->filter()->unique()->take(3)->values();
                            $allTrainings = $bottom3Only->pluck('training')->filter()->unique()->take(3)->values();
                        @endphp

                        <h5 class="text-info"><i class="fas fa-tasks mr-1"></i> Activities (from SJT Weaknesses)</h5>
                        @if($allActivities->isNotEmpty())
                            <ul class="mb-3">
                                @foreach($allActivities as $act)
                                    <li>{{ $act }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No activity recommendations.</p>
                        @endif

                        <h5 class="text-primary mt-2"><i class="fas fa-graduation-cap mr-1"></i> Trainings (from SJT Weaknesses)</h5>
                        @if($allTrainings->isNotEmpty())
                            <ul class="mb-0">
                                @foreach($allTrainings as $train)
                                    <li>{{ $train }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No training recommendations.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tools mr-2"></i>Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if($pdfExists)
                            <a href="{{ route('admin.results.download-pdf', $testResult) }}"
                               class="btn btn-primary btn-block">
                                <i class="fas fa-download mr-2"></i>Download PDF Report
                            </a>
                            @endif

                            @if(!$testResult->email_sent_at && $pdfExists)
                            <button type="button" class="btn btn-success btn-block" onclick="sendEmail()">
                                <i class="fas fa-envelope mr-2"></i>Send Email to Participant
                            </button>
                            @elseif($testResult->email_sent_at)
                            <button type="button" class="btn btn-warning btn-block" onclick="resendEmail()">
                                <i class="fas fa-redo mr-2"></i>Resend Email
                            </button>
                            @endif

                            @if(!$pdfExists)
                            <button type="button" class="btn btn-info btn-block" onclick="regeneratePdf()">
                                <i class="fas fa-sync mr-2"></i>Regenerate PDF
                            </button>
                            @endif

                            @if(auth()->user()->role === 'admin')
                            <button type="button" class="btn btn-outline-danger btn-block" onclick="deleteResult()">
                                <i class="fas fa-trash mr-2"></i>Delete Result
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Test Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-2"></i>Test Statistics
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>ST-30 Total Responses:</strong></td>
                                <td>{{ $st30Details['total_responses'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><strong>SJT Total Responses:</strong></td>
                                <td>{{ $sjtDetails['total_responses'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><strong>Session ID:</strong></td>
                                <td><code>{{ $testResult->session_id }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Result ID:</strong></td>
                                <td><code>{{ $testResult->id }}</code></td>
                            </tr>
                            @if($testResult->pdf_path)
                            <tr>
                                <td><strong>PDF Path:</strong></td>
                                <td><small class="text-muted">{{ $testResult->pdf_path }}</small></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Activity Log -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-2"></i>Activity Timeline
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @if($testResult->report_generated_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Report Generated</h6>
                                    <p class="timeline-text">{{ $testResult->report_generated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($testResult->email_sent_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Email Sent</h6>
                                    <p class="timeline-text">{{ $testResult->email_sent_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            @endif

                            @if(optional($testResult->session)->completed_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Test Completed</h6>
                                    <p class="timeline-text">{{ optional($testResult->session->completed_at)->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            @endif

                            @if(optional($testResult->session)->created_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-secondary"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Test Started</h6>
                                    <p class="timeline-text">{{ optional($testResult->session->created_at)->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div><!-- /Sidebar -->
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline { position: relative; padding-left: 30px; }
.timeline-item { position: relative; margin-bottom: 20px; }
.timeline-item:before {
    content: ''; position: absolute; left: -21px; top: 8px;
    width: 2px; height: calc(100% + 20px); background: #dee2e6;
}
.timeline-item:last-child:before { display: none; }
.timeline-marker {
    position: absolute; left: -30px; top: 0; width: 16px; height: 16px;
    border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 0 1px #dee2e6;
}
.timeline-content { margin-left: 10px; }
.timeline-title { margin: 0 0 5px 0; font-weight: 600; }
.timeline-text { margin: 0; color: #6c757d; font-size: 0.875rem; }
</style>
@endpush

@push('scripts')
<script>
function sendEmail() {
    customConfirm({
        title: 'Send Email?',
        text: 'Send assessment result via email to participant?',
        confirmButtonText: 'Yes, send it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('{{ route("admin.results.send-result", $testResult) }}', {
                _token: '{{ csrf_token() }}'
            }).done(function() {
                location.reload();
            }).fail(function(xhr) {
                showAlert('error', 'Failed to send email: ' + (xhr.responseJSON?.message || ''));
            });
        }
    });
}

function resendEmail() {
    customConfirm({
        title: 'Resend Email?',
        text: 'Resend assessment result via email to participant?',
        confirmButtonText: 'Yes, resend it!'
    }).then((result) => {
        if (result.isConfirmed) {
            sendEmail();
        }
    });
}

function regeneratePdf() {
    customConfirm({
        title: 'Regenerate PDF?',
        text: 'This will create a new PDF report. Continue?',
        confirmButtonText: 'Yes, regenerate!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('{{ route("admin.results.regenerate-pdf", $testResult) }}', {
                _token: '{{ csrf_token() }}'
            }).done(function() {
                showAlert('success', 'PDF is being regenerated. Please wait a moment.');
                setTimeout(() => location.reload(), 3000);
            }).fail(function(xhr) {
                showAlert('error', 'Failed to regenerate PDF: ' + (xhr.responseJSON?.message || ''));
            });
        }
    });
}

function deleteResult() {
    customConfirm({
        title: 'Delete Result?',
        text: 'This will permanently delete this test result and associated PDF. This action cannot be undone.',
        confirmButtonText: 'Yes, delete it!',
        icon: 'warning'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('{{ route("admin.results.bulk-action") }}', {
                _token: '{{ csrf_token() }}',
                action: 'delete',
                selected_results: ['{{ $testResult->id }}']
            }).done(function() {
                window.location.href = '{{ route("admin.results.index") }}';
            }).fail(function(xhr) {
                showAlert('error', 'Failed to delete result: ' + (xhr.responseJSON?.message || ''));
            });
        }
    });
}
</script>
@endpush

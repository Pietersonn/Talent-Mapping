@extends('admin.layouts.app')

@section('title', 'Resend Request Details')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Resend Request Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.resend.index') }}">Resend Requests</a></li>
                    <li class="breadcrumb-item active">{{ $resendRequest->user->name }}</li>
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
                <!-- Request Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>Request Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Request ID:</strong></td>
                                        <td><code>{{ $resendRequest->id }}</code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>User:</strong></td>
                                        <td>
                                            <strong>{{ $resendRequest->user->name }}</strong><br>
                                            <small class="text-muted">{{ $resendRequest->user->email }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Request Date:</strong></td>
                                        <td>{{ $resendRequest->request_date->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge {{ $resendRequest->status_badge_class }}">
                                                {{ ucfirst($resendRequest->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Processed By:</strong></td>
                                        <td>
                                            @if($resendRequest->approvedBy)
                                                {{ $resendRequest->approvedBy->name }}
                                            @else
                                                <span class="text-muted">Not processed yet</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Processed Date:</strong></td>
                                        <td>
                                            @if($resendRequest->approved_at)
                                                {{ $resendRequest->approved_at->format('d M Y H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($resendRequest->rejection_reason)
                                    <tr>
                                        <td><strong>Rejection Reason:</strong></td>
                                        <td class="text-danger">{{ $resendRequest->rejection_reason }}</td>
                                    </tr>
                                    @endif
                                    @if($resendRequest->admin_notes)
                                    <tr>
                                        <td><strong>Admin Notes:</strong></td>
                                        <td>{{ $resendRequest->admin_notes }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Test Result Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-2"></i>Related Test Result
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Result ID:</strong></td>
                                        <td><code>{{ $resendRequest->testResult->id }}</code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Participant:</strong></td>
                                        <td>{{ $resendRequest->testResult->testSession->participant_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Event:</strong></td>
                                        <td>
                                            @if($resendRequest->testResult->testSession->event)
                                                <span class="badge badge-info">{{ $resendRequest->testResult->testSession->event->name }}</span>
                                            @else
                                                <span class="text-muted">No Event</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dominant Typology:</strong></td>
                                        <td>
                                            @if($resendRequest->testResult->dominantTypologyDescription)
                                                {{ $resendRequest->testResult->dominantTypologyDescription->typology_name }}
                                            @else
                                                {{ $resendRequest->testResult->dominant_typology ?? 'N/A' }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Report Generated:</strong></td>
                                        <td>
                                            @if($resendRequest->testResult->report_generated_at)
                                                {{ $resendRequest->testResult->report_generated_at->format('d M Y H:i') }}
                                            @else
                                                <span class="text-muted">Not Generated</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Email Sent:</strong></td>
                                        <td>
                                            @if($resendRequest->testResult->email_sent_at)
                                                {{ $resendRequest->testResult->email_sent_at->format('d M Y H:i') }}
                                            @else
                                                <span class="text-muted">Never Sent</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>PDF Available:</strong></td>
                                        <td>
                                            @if(!empty($resendRequest->testResult->pdf_path))
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Available
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times"></i> Not Available
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Test Completed:</strong></td>
                                        <td>
                                            @if($resendRequest->testResult->testSession->completed_at)
                                                {{ $resendRequest->testResult->testSession->completed_at->format('d M Y H:i') }}
                                            @else
                                                <span class="text-muted">Not Completed</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Previous Resend Requests -->
                @php
                    $previousRequests = \App\Models\ResendRequest::where('user_id', $resendRequest->user_id)
                        ->where('id', '!=', $resendRequest->id)
                        ->orderBy('request_date', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                @if($previousRequests->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-2"></i>Previous Requests by This User
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Processed By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($previousRequests as $prevRequest)
                                    <tr>
                                        <td>{{ $prevRequest->request_date->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge {{ $prevRequest->status_badge_class }}">
                                                {{ ucfirst($prevRequest->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($prevRequest->approvedBy)
                                                {{ $prevRequest->approvedBy->name }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.resend.show', $prevRequest) }}"
                                               class="btn btn-info btn-xs">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar Actions -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tools mr-2"></i>Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($resendRequest->status === 'pending' && auth()->user()->role === 'admin')
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success btn-block" onclick="approveRequest()">
                                <i class="fas fa-check mr-2"></i>Approve Request
                            </button>
                            <button type="button" class="btn btn-danger btn-block" onclick="rejectRequest()">
                                <i class="fas fa-times mr-2"></i>Reject Request
                            </button>
                        </div>
                        @elseif($resendRequest->status === 'approved')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle mr-2"></i>
                            This request has been approved and email sent to the participant.
                        </div>
                        @elseif($resendRequest->status === 'rejected')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle mr-2"></i>
                            This request has been rejected.
                        </div>
                        @endif

                        <hr>

                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.results.show', $resendRequest->testResult) }}"
                               class="btn btn-outline-primary btn-block">
                                <i class="fas fa-chart-bar mr-2"></i>View Test Result
                            </a>

                            <a href="{{ route('admin.users.show', $resendRequest->user) }}"
                               class="btn btn-outline-info btn-block">
                                <i class="fas fa-user mr-2"></i>View User Profile
                            </a>

                            @if(!empty($resendRequest->testResult->pdf_path))
                            <a href="{{ route('admin.results.download-pdf', $resendRequest->testResult) }}"
                               class="btn btn-outline-secondary btn-block">
                                <i class="fas fa-download mr-2"></i>Download PDF
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Request Details -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info mr-2"></i>Request Summary
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="progress-group">
                            <span class="progress-text">Request Status</span>
                            <span class="float-right">
                                <b class="text-{{ $resendRequest->status === 'approved' ? 'success' : ($resendRequest->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($resendRequest->status) }}
                                </b>
                            </span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-{{ $resendRequest->status === 'approved' ? 'success' : ($resendRequest->status === 'rejected' ? 'danger' : 'warning') }}"
                                     style="width: {{ $resendRequest->status === 'approved' ? '100' : ($resendRequest->status === 'rejected' ? '100' : '50') }}%"></div>
                            </div>
                        </div>

                        <small class="text-muted">
                            @if($resendRequest->status === 'pending')
                                Waiting for admin review...
                            @elseif($resendRequest->status === 'approved')
                                Approved by {{ $resendRequest->approvedBy->name }} on {{ $resendRequest->approved_at->format('d M Y') }}
                            @else
                                Rejected by {{ $resendRequest->approvedBy->name }} on {{ $resendRequest->approved_at->format('d M Y') }}
                            @endif
                        </small>
                    </div>
                </div>

                <!-- User Statistics -->
                @php
                    $userStats = [
                        'total_requests' => \App\Models\ResendRequest::where('user_id', $resendRequest->user_id)->count(),
                        'approved_requests' => \App\Models\ResendRequest::where('user_id', $resendRequest->user_id)->where('status', 'approved')->count(),
                        'rejected_requests' => \App\Models\ResendRequest::where('user_id', $resendRequest->user_id)->where('status', 'rejected')->count(),
                    ];
                @endphp

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-2"></i>User Request History
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Total Requests:</strong></td>
                                <td>{{ $userStats['total_requests'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Approved:</strong></td>
                                <td>
                                    <span class="badge badge-success">{{ $userStats['approved_requests'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Rejected:</strong></td>
                                <td>
                                    <span class="badge badge-danger">{{ $userStats['rejected_requests'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Success Rate:</strong></td>
                                <td>
                                    @if($userStats['total_requests'] > 0)
                                        {{ round(($userStats['approved_requests'] / $userStats['total_requests']) * 100, 1) }}%
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Approve Resend Request</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="approveForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Admin Notes (Optional)</label>
                        <textarea name="admin_notes" class="form-control" rows="3"
                                  placeholder="Internal notes for admin reference..."></textarea>
                    </div>
                    <p class="text-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Approving this request will automatically send the assessment result email to the participant.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reject Resend Request</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rejection Reason *</label>
                        <textarea name="rejection_reason" class="form-control" rows="3"
                                  placeholder="Please provide a clear reason for rejection..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Admin Notes (Optional)</label>
                        <textarea name="admin_notes" class="form-control" rows="2"
                                  placeholder="Internal notes for admin reference..."></textarea>
                    </div>
                    <p class="text-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        The participant will be notified about the rejection via email.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function approveRequest() {
    $('#approveModal').modal('show');
}

function rejectRequest() {
    $('#rejectModal').modal('show');
}

$('#approveForm').submit(function(e) {
    e.preventDefault();

    const formData = $(this).serializeArray();
    const data = {
        _token: '{{ csrf_token() }}'
    };

    formData.forEach(function(item) {
        data[item.name] = item.value;
    });

    $.post('{{ route("admin.resend.approve", $resendRequest) }}', data)
        .done(function() {
            $('#approveModal').modal('hide');
            location.reload();
        }).fail(function(xhr) {
            showAlert('error', 'Failed to approve request: ' + xhr.responseJSON?.message);
        });
});

$('#rejectForm').submit(function(e) {
    e.preventDefault();

    const formData = $(this).serializeArray();
    const data = {
        _token: '{{ csrf_token() }}'
    };

    formData.forEach(function(item) {
        data[item.name] = item.value;
    });

    $.post('{{ route("admin.resend.reject", $resendRequest) }}', data)
        .done(function() {
            $('#rejectModal').modal('hide');
            location.reload();
        }).fail(function(xhr) {
            showAlert('error', 'Failed to reject request: ' + xhr.responseJSON?.message);
        });
});
</script>
@endpush

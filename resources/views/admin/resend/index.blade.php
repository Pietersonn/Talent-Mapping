@extends('admin.layouts.app')

@section('title', 'Resend Requests')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Resend Requests</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Resend Requests</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total_requests'] }}</h3>
                        <p>Total Requests</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-redo"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['pending'] }}</h3>
                        <p>Pending Review</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['approved'] }}</h3>
                        <p>Approved</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['rejected'] }}</h3>
                        <p>Rejected</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Requests</h3>
                @if(auth()->user()->role === 'admin')
                <div class="card-tools">
                    <button type="button" class="btn btn-danger btn-sm" onclick="cleanupOldRequests()">
                        <i class="fas fa-trash"></i> Cleanup Old Requests
                    </button>
                </div>
                @endif
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.resend.index') }}" class="row">
                    <div class="col-md-3">
                        <label>Search User</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="Name or email..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-2">
                        <label>Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Resend Requests ({{ $requests->total() }} found)</h3>

                @if($requests->count() > 0 && auth()->user()->role === 'admin')
                <div class="card-tools">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-success" onclick="bulkApprove()">
                            <i class="fas fa-check"></i> Approve Selected
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="bulkReject()">
                            <i class="fas fa-times"></i> Reject Selected
                        </button>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-body p-0">
                @if($requests->count() > 0)
                <form id="bulkForm">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    @if(auth()->user()->role === 'admin')
                                    <th style="width: 40px">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    @endif
                                    <th>User</th>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                    <th>Processed By</th>
                                    <th>Processed Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                <tr>
                                    @if(auth()->user()->role === 'admin')
                                    <td>
                                        @if($request->status === 'pending')
                                        <input type="checkbox" name="selected_requests[]"
                                               value="{{ $request->id }}" class="request-checkbox">
                                        @endif
                                    </td>
                                    @endif
                                    <td>
                                        <strong>{{ $request->user->name }}</strong><br>
                                        <small class="text-muted">{{ $request->user->email }}</small>
                                    </td>
                                    <td>{{ $request->request_date->format('d M Y H:i') }}</td>
                                    <td>
                                        <span class="badge {{ $request->status_badge_class }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($request->approvedBy)
                                            {{ $request->approvedBy->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($request->approved_at)
                                            {{ $request->approved_at->format('d M Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.resend.show', $request) }}"
                                               class="btn btn-info btn-xs" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($request->status === 'pending' && auth()->user()->role === 'admin')
                                            <button type="button" class="btn btn-success btn-xs"
                                                    onclick="approveRequest('{{ $request->id }}')" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-xs"
                                                    onclick="rejectRequest('{{ $request->id }}')" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $requests->appends(request()->query())->links() }}
                </div>

                @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No resend requests found</h5>
                    <p class="text-muted">Try adjusting your search filters.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bulk Reject Modal -->
<div class="modal fade" id="bulkRejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bulk Reject Requests</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="bulkRejectForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rejection Reason *</label>
                        <textarea name="bulk_rejection_reason" class="form-control" rows="3"
                                  placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Admin Notes (Optional)</label>
                        <textarea name="bulk_admin_notes" class="form-control" rows="2"
                                  placeholder="Internal notes for admin reference..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Requests</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Approve Modal -->
<div class="modal fade" id="bulkApproveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bulk Approve Requests</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="bulkApproveForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Admin Notes (Optional)</label>
                        <textarea name="bulk_admin_notes" class="form-control" rows="2"
                                  placeholder="Internal notes for admin reference..."></textarea>
                    </div>
                    <p class="text-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Approved requests will automatically send emails to participants.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Requests</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select All functionality
    $('#selectAll').change(function() {
        $('.request-checkbox').prop('checked', $(this).prop('checked'));
    });

    $('.request-checkbox').change(function() {
        if (!$(this).prop('checked')) {
            $('#selectAll').prop('checked', false);
        }

        if ($('.request-checkbox:checked').length === $('.request-checkbox').length) {
            $('#selectAll').prop('checked', true);
        }
    });
});

function approveRequest(requestId) {
    customConfirm({
        title: 'Approve Request?',
        text: 'This will send the assessment result to the participant.',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('{{ route("admin.resend.approve", ":id") }}'.replace(':id', requestId), {
                _token: '{{ csrf_token() }}'
            }).done(function() {
                location.reload();
            }).fail(function(xhr) {
                showAlert('error', 'Failed to approve request: ' + xhr.responseJSON?.message);
            });
        }
    });
}

function rejectRequest(requestId) {
    customPrompt({
        title: 'Reject Request',
        text: 'Please provide a reason for rejection:',
        input: 'textarea',
        inputPlaceholder: 'Rejection reason...',
        inputValidator: (value) => {
            if (!value) {
                return 'You need to provide a rejection reason!'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('{{ route("admin.resend.reject", ":id") }}'.replace(':id', requestId), {
                _token: '{{ csrf_token() }}',
                rejection_reason: result.value
            }).done(function() {
                location.reload();
            }).fail(function(xhr) {
                showAlert('error', 'Failed to reject request: ' + xhr.responseJSON?.message);
            });
        }
    });
}

function bulkApprove() {
    const selected = $('.request-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (selected.length === 0) {
        showAlert('warning', 'Please select at least one request.');
        return;
    }

    $('#bulkApproveModal').modal('show');
}

function bulkReject() {
    const selected = $('.request-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (selected.length === 0) {
        showAlert('warning', 'Please select at least one request.');
        return;
    }

    $('#bulkRejectModal').modal('show');
}

$('#bulkApproveForm').submit(function(e) {
    e.preventDefault();

    const selected = $('.request-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    const formData = $(this).serializeArray();
    const data = {
        _token: '{{ csrf_token() }}',
        selected_requests: selected
    };

    formData.forEach(function(item) {
        data[item.name] = item.value;
    });

    $.post('{{ route("admin.resend.bulk-approve") }}', data)
        .done(function() {
            $('#bulkApproveModal').modal('hide');
            location.reload();
        }).fail(function(xhr) {
            showAlert('error', 'Failed to approve requests: ' + xhr.responseJSON?.message);
        });
});

$('#bulkRejectForm').submit(function(e) {
    e.preventDefault();

    const selected = $('.request-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    const formData = $(this).serializeArray();
    const data = {
        _token: '{{ csrf_token() }}',
        selected_requests: selected
    };

    formData.forEach(function(item) {
        data[item.name] = item.value;
    });

    $.post('{{ route("admin.resend.bulk-reject") }}', data)
        .done(function() {
            $('#bulkRejectModal').modal('hide');
            location.reload();
        }).fail(function(xhr) {
            showAlert('error', 'Failed to reject requests: ' + xhr.responseJSON?.message);
        });
});

function cleanupOldRequests() {
    customConfirm({
        title: 'Cleanup Old Requests?',
        text: 'This will delete processed requests older than 3 months.',
        confirmButtonText: 'Yes, cleanup!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("admin.resend.cleanup") }}',
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' }
            }).done(function() {
                location.reload();
            }).fail(function(xhr) {
                showAlert('error', 'Failed to cleanup: ' + xhr.responseJSON?.message);
            });
        }
    });
}
</script>
@endpush

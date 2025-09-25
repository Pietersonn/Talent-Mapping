@extends('admin.layouts.app')

@section('title', 'All Test Results')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">All Test Results</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">All Results</li>
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
                        <h3>{{ $stats['total_results'] }}</h3>
                        <p>Total Results</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['emails_sent'] }}</h3>
                        <p>Emails Sent</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['emails_pending'] }}</h3>
                        <p>Emails Pending</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $stats['this_month'] }}</h3>
                        <p>This Month</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter & Actions</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" onclick="exportResults()">
                        <i class="fas fa-download"></i> Export CSV
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.results.index') }}" class="row">
                    <div class="col-md-3">
                        <label>Search User</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="Name or email..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <label>Event</label>
                        <select name="event_id" class="form-control">
                            <option value="">All Events</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}"
                                    {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Email Status</label>
                        <select name="email_status" class="form-control">
                            <option value="">All Status</option>
                            <option value="sent" {{ request('email_status') == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="pending" {{ request('email_status') == 'pending' ? 'selected' : '' }}>Pending</option>
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

        <!-- Results Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Test Results ({{ $results->total() }} found)</h3>

                @if($results->count() > 0)
                <div class="card-tools">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-success" onclick="bulkSendEmail()">
                            <i class="fas fa-envelope"></i> Send Selected
                        </button>
                        @if(auth()->user()->role === 'admin')
                        <button type="button" class="btn btn-sm btn-danger" onclick="bulkDelete()">
                            <i class="fas fa-trash"></i> Delete Selected
                        </button>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="card-body p-0">
                @if($results->count() > 0)
                <form id="bulkForm">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 40px">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>Participant</th>
                                    <th>Event</th>
                                    <th>Generated</th>
                                    <th>Email Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_results[]"
                                               value="{{ $result->id }}" class="result-checkbox">
                                    </td>
                                    <td>
                                        <strong>{{ optional($result->session)->participant_name ?? '-' }}</strong><br>
                                        <small class="text-muted">{{ optional(optional($result->session)->user)->email ?? '-' }}</small>
                                    </td>
                                    <td>
                                        @if(optional($result->session)->event)
                                            <span class="badge badge-info">{{ $result->session->event->name }}</span>
                                        @else
                                            <span class="text-muted">No Event</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($result->report_generated_at)
                                            {{ $result->report_generated_at->format('d M Y H:i') }}
                                        @else
                                            <span class="text-muted">Not Generated</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($result->email_sent_at)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Sent
                                            </span><br>
                                            <small class="text-muted">{{ $result->email_sent_at->format('d M Y H:i') }}</small>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.results.show', $result) }}"
                                               class="btn btn-info btn-xs" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if(!empty($result->pdf_path))
                                            <a href="{{ route('admin.results.download-pdf', $result) }}"
                                               class="btn btn-primary btn-xs" title="Download PDF">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @endif

                                            @if(!$result->email_sent_at && !empty($result->pdf_path))
                                            <button type="button" class="btn btn-success btn-xs"
                                                    onclick="sendEmail('{{ $result->id }}')" title="Send Email">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                            @elseif($result->email_sent_at)
                                            <button type="button" class="btn btn-warning btn-xs"
                                                    onclick="resendEmail('{{ $result->id }}')" title="Resend Email">
                                                <i class="fas fa-redo"></i>
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
                    {{ $results->appends(request()->query())->links() }}
                </div>

                @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No test results found</h5>
                    <p class="text-muted">Try adjusting your search filters.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select All functionality
    $('#selectAll').change(function() {
        $('.result-checkbox').prop('checked', $(this).prop('checked'));
    });

    $('.result-checkbox').change(function() {
        if (!$(this).prop('checked')) {
            $('#selectAll').prop('checked', false);
        }
        if ($('.result-checkbox:checked').length === $('.result-checkbox').length) {
            $('#selectAll').prop('checked', true);
        }
    });
});

function exportResults() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '{{ route("admin.results.export") }}?' + params.toString();
}

function sendEmail(resultId) {
    customConfirm({
        title: 'Send Email?',
        text: 'Send assessment result via email to participant?',
        confirmButtonText: 'Yes, send it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('{{ route("admin.results.send-result", ":id") }}'.replace(':id', resultId), {
                _token: '{{ csrf_token() }}'
            }).done(function() {
                location.reload();
            }).fail(function(xhr) {
                showAlert('error', 'Failed to send email: ' + (xhr.responseJSON?.message || ''));
            });
        }
    });
}

function resendEmail(resultId) {
    customConfirm({
        title: 'Resend Email?',
        text: 'Resend assessment result via email to participant?',
        confirmButtonText: 'Yes, resend it!'
    }).then((result) => {
        if (result.isConfirmed) {
            sendEmail(resultId);
        }
    });
}

function bulkSendEmail() {
    const selected = $('.result-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (selected.length === 0) {
        showAlert('warning', 'Please select at least one result.');
        return;
    }

    customConfirm({
        title: 'Send Bulk Emails?',
        text: `Send emails to ${selected.length} selected participants?`,
        confirmButtonText: 'Yes, send them!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('{{ route("admin.results.bulk-action") }}', {
                _token: '{{ csrf_token() }}',
                action: 'send_email',
                selected_results: selected
            }).done(function() {
                location.reload();
            }).fail(function(xhr) {
                showAlert('error', 'Failed to send emails: ' + (xhr.responseJSON?.message || ''));
            });
        }
    });
}

function bulkDelete() {
    const selected = $('.result-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (selected.length === 0) {
        showAlert('warning', 'Please select at least one result.');
        return;
    }

    customConfirm({
        title: 'Delete Results?',
        text: `Delete ${selected.length} selected results? This action cannot be undone.`,
        confirmButtonText: 'Yes, delete them!',
        icon: 'warning'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('{{ route("admin.results.bulk-action") }}', {
                _token: '{{ csrf_token() }}',
                action: 'delete',
                selected_results: selected
            }).done(function() {
                location.reload();
            }).fail(function(xhr) {
                showAlert('error', 'Failed to delete results: ' + (xhr.responseJSON?.message || ''));
            });
        }
    });
}
</script>
@endpush

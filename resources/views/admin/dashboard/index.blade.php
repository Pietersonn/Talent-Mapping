@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Main row -->
<div class="row">

    <!-- User Statistics -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($totalUsers) }}</h3>
                <p>Total Users</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- Event Statistics -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($activeEvents) }}</h3>
                <p>Active Events</p>
            </div>
            <div class="icon">
                <i class="ion ion-calendar"></i>
            </div>
            <a href="{{ route('admin.events.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- Test Statistics -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($completedTests) }}</h3>
                <p>Completed Tests</p>
            </div>
            <div class="icon">
                <i class="ion ion-checkmark"></i>
            </div>
            <a href="{{ route('admin.results.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- Question Bank Statistics -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ number_format($totalST30Questions + $totalSJTQuestions) }}</h3>
                <p>Total Questions</p>
            </div>
            <div class="icon">
                <i class="ion ion-help"></i>
            </div>
            <a href="{{ route('admin.questions.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

</div>
<!-- /.row -->

<!-- Second row -->
<div class="row">

    <!-- Test Activity Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Test Activity (Last 7 Days)
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-period="today" onclick="updateStats('today')">Today</button>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-period="week" onclick="updateStats('week')">This Week</button>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-period="month" onclick="updateStats('month')">This Month</button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="testActivityChart" width="400" height="150"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tachometer-alt mr-1"></i>
                    Quick Stats
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Tests Today
                        <span class="badge badge-primary badge-pill">{{ $testsToday }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Tests This Week
                        <span class="badge badge-success badge-pill">{{ $testsThisWeek }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Tests This Month
                        <span class="badge badge-info badge-pill">{{ $testsThisMonth }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Completion Rate
                        <span class="badge badge-warning badge-pill">{{ $completionRate }}%</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Pending Resends
                        <span class="badge badge-danger badge-pill">{{ $pendingResendRequests }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
<!-- /.row -->

<!-- Third row -->
<div class="row">

    <!-- Recent Test Sessions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Recent Test Sessions
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.results.index') }}" class="btn btn-tool">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Participant</th>
                                <th>Event</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTestSessions as $session)
                            <tr>
                                <td>
                                    <strong>{{ $session->participant_name ?? $session->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $session->user->email }}</small>
                                </td>
                                <td>
                                    @if($session->event)
                                        {{ Str::limit($session->event->name, 30) }}
                                    @else
                                        <em class="text-muted">No Event</em>
                                    @endif
                                </td>
                                <td>
                                    @if($session->is_completed)
                                        <span class="badge badge-success">Completed</span>
                                    @else
                                        <span class="badge badge-warning">{{ ucfirst(str_replace('_', ' ', $session->current_step)) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $session->created_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    No recent test sessions
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Resend Requests -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-redo mr-1"></i>
                    Recent Resend Requests
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.resend.index') }}" class="btn btn-tool">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentResendRequests as $request)
                            <tr>
                                <td>
                                    <strong>{{ $request->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $request->user->email }}</small>
                                </td>
                                <td>
                                    @if($request->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($request->status === 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $request->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    @if($request->status === 'pending' && Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.resend.show', $request->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    No recent resend requests
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.row -->

<!-- Fourth row - Role-specific content -->
@if(Auth::user()->role === 'admin')
<div class="row">

    <!-- User Role Distribution -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-1"></i>
                    User Role Distribution
                </h3>
            </div>
            <div class="card-body">
                <canvas id="userRoleChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-heartbeat mr-1"></i>
                    System Health
                </h3>
            </div>
            <div class="card-body">
                <div class="progress-group">
                    Email Delivery Rate
                    <span class="float-right"><b>{{ $totalResults > 0 ? round(($emailsSent / $totalResults) * 100, 1) : 0 }}%</b></span>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-success" style="width: {{ $totalResults > 0 ? ($emailsSent / $totalResults) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div class="progress-group">
                    Test Completion Rate
                    <span class="float-right"><b>{{ $completionRate }}%</b></span>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" style="width: {{ $completionRate }}%"></div>
                    </div>
                </div>

                <div class="progress-group">
                    Active Users
                    <span class="float-right"><b>{{ $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0 }}%</b></span>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-info" style="width: {{ $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div class="progress-group">
                    Question Bank Coverage
                    <span class="float-right"><b>{{ $activeVersions > 0 ? 100 : 0 }}%</b></span>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-warning" style="width: {{ $activeVersions > 0 ? 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endif
<!-- /.row -->

@endsection

@push('scripts')
<!-- Chart.js -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Test Activity Chart
    var ctx = document.getElementById('testActivityChart').getContext('2d');
    var testActivityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($testsPerDay, 'date')) !!},
            datasets: [{
                label: 'Tests Completed',
                data: {!! json_encode(array_column($testsPerDay, 'count')) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    @if(Auth::user()->role === 'admin')
    // User Role Chart
    var ctx2 = document.getElementById('userRoleChart').getContext('2d');
    var userRoleChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Participants', 'PICs', 'Staff', 'Admins'],
            datasets: [{
                data: [{{ $totalParticipants }}, {{ $totalPICs }}, {{ $totalStaff }}, {{ $totalAdmins }}],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    @endif
});

// Update statistics function
function updateStats(period) {
    // Update button states
    $('[data-period]').removeClass('btn-primary').addClass('btn-outline-primary');
    $('[data-period="' + period + '"]').removeClass('btn-outline-primary').addClass('btn-primary');

    // Fetch new data
    $.get('{{ route("admin.dashboard.stats") }}', { period: period })
        .done(function(data) {
            // Update stats - you can enhance this to update specific elements
            console.log('Stats updated:', data);
        });
}

// Auto-refresh stats every 30 seconds
setInterval(function() {
    var activePeriod = $('.btn-primary[data-period]').data('period') || 'today';
    updateStats(activePeriod);
}, 30000);
</script>
@endpush

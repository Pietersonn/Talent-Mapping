@extends('pic.layouts.app')

@section('title', 'PIC Dashboard - TalentMapping')

@section('content')
<div class="dashboard-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="dashboard-title">Dashboard</h1>
            <p class="dashboard-subtitle">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
        <div class="col-auto">
            <div class="date-info">
                <i class="bi bi-calendar3"></i>
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalEvents }}</div>
                    <div class="stat-label">My Events</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-info">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalParticipants }}</div>
                    <div class="stat-label">Total Participants</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-success">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $completedTests }}</div>
                    <div class="stat-label">Completed Tests</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-warning">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $pendingTests }}</div>
                    <div class="stat-label">Pending Tests</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Event Progress -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-bar-chart-line me-2"></i>
                    Event Progress
                </h5>
            </div>
            <div class="card-body">
                @if(count($eventProgress) > 0)
                    @foreach($eventProgress as $progress)
                        <div class="event-progress-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="event-info">
                                    <h6 class="event-name mb-0">{{ $progress['event']->name }}</h6>
                                    <small class="text-muted">{{ $progress['event']->event_code }}</small>
                                </div>
                                <div class="progress-stats">
                                    <span class="badge bg-primary">{{ $progress['completion_rate'] }}%</span>
                                </div>
                            </div>
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar" role="progressbar"
                                     style="width: {{ $progress['completion_rate'] }}%"
                                     aria-valuenow="{{ $progress['completion_rate'] }}"
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">
                                    {{ $progress['completed'] }} of {{ $progress['total_participants'] }} completed
                                </small>
                                <small class="text-muted">
                                    {{ $progress['total_participants'] - $progress['completed'] }} pending
                                </small>
                            </div>
                        </div>
                        @if(!$loop->last)<hr>@endif
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-calendar-x display-1"></i>
                        <p class="mt-3">No events assigned yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Recent Activity
                </h5>
            </div>
            <div class="card-body">
                @if(count($recentSessions) > 0)
                    <div class="activity-list">
                        @foreach($recentSessions as $session)
                            <div class="activity-item">
                                <div class="activity-icon">
                                    @if($session->is_completed)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-clock text-warning"></i>
                                    @endif
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">{{ $session->participant_name }}</div>
                                    <div class="activity-description">
                                        {{ $session->event->name }}
                                        @if($session->is_completed)
                                            <span class="badge bg-success ms-1">Completed</span>
                                        @else
                                            <span class="badge bg-warning ms-1">In Progress</span>
                                        @endif
                                    </div>
                                    <div class="activity-time">
                                        {{ $session->updated_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox display-4"></i>
                        <p class="mt-3">No recent activity</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3 mt-4">
    <div class="col-md-4">
        <div class="quick-action-card">
            <div class="quick-action-icon">
                <i class="bi bi-calendar-plus"></i>
            </div>
            <div class="quick-action-content">
                <h6>Create Event</h6>
                <p class="text-muted">Set up a new assessment event</p>
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">
                    Get Started
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="quick-action-card">
            <div class="quick-action-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="quick-action-content">
                <h6>Manage Participants</h6>
                <p class="text-muted">View and manage event participants</p>
                <a href="{{ route('pic.participants.index') }}" class="btn btn-primary btn-sm">
                    View All
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

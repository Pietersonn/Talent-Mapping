@extends('admin.layouts.app')

@section('title', 'Event Management')
@section('page-title', 'Event Management')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Event Management</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total'] }}</h3>
                        <p>Total Events</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['active'] }}</h3>
                        <p>Active Events</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['ongoing'] }}</h3>
                        <p>Ongoing Events</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $stats['upcoming'] }}</h3>
                        <p>Upcoming Events</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-1"></i>
                    Events List
                </h3>

                @if(Auth::user()->role === 'admin')
                    <div class="card-tools">
                        <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Create New Event
                        </a>
                    </div>
                @endif
            </div>

            <div class="card-body">
                <!-- Search & Filters -->
                <form method="GET" action="{{ route('admin.events.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="Search events..."
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="pic_id" class="form-control">
                                    <option value="">All PICs</option>
                                    @foreach($pics as $pic)
                                        <option value="{{ $pic->id }}" {{ request('pic_id') == $pic->id ? 'selected' : '' }}>
                                            {{ $pic->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="date"
                                       name="date_from"
                                       class="form-control"
                                       placeholder="From Date"
                                       value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="date"
                                       name="date_to"
                                       class="form-control"
                                       placeholder="To Date"
                                       value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary ml-1">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </form>

                @if($events->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Code</th>
                                    <th>PIC</th>
                                    <th>Date Range</th>
                                    <th>Participants</th>
                                    <th>Status</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                    <tr>
                                        <td>
                                            <strong>{{ $event->name }}</strong>
                                            @if($event->description)
                                                <br><small class="text-muted">{{ Str::limit($event->description, 60) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $event->event_code }}</span>
                                        </td>
                                        <td>
                                            @if($event->pic)
                                                <span class="badge badge-info">{{ $event->pic->name }}</span>
                                            @else
                                                <span class="text-muted">No PIC</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $event->start_date->format('d M Y') }}
                                                <br>
                                                <i class="fas fa-calendar-check mr-1"></i>
                                                {{ $event->end_date->format('d M Y') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-primary">
                                                {{ $event->participants()->count() }}
                                                @if($event->max_participants)
                                                    / {{ $event->max_participants }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $event->status_badge }}">
                                                {{ $event->status_display }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.events.show', $event) }}"
                                                   class="btn btn-info btn-sm"
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if(Auth::user()->role === 'admin')
                                                    <a href="{{ route('admin.events.edit', $event) }}"
                                                       class="btn btn-warning btn-sm"
                                                       title="Edit Event">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <button type="button"
                                                            class="btn btn-{{ $event->is_active ? 'secondary' : 'success' }} btn-sm"
                                                            onclick="confirmToggleStatus('{{ $event->name }}', '{{ route('admin.events.toggle-status', $event) }}', {{ $event->is_active ? 'true' : 'false' }})"
                                                            title="{{ $event->is_active ? 'Deactivate' : 'Activate' }} Event">
                                                        <i class="fas fa-power-off"></i>
                                                    </button>

                                                    @if($event->participants()->count() == 0)
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="confirmDelete('{{ $event->name }}', '{{ route('admin.events.destroy', $event) }}')"
                                                                title="Delete Event">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} of {{ $events->total() }} events
                        </div>
                        <div>
                            {{ $events->withQueryString()->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Events Found</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'status', 'pic_id', 'date_from', 'date_to']))
                                No events match your current filters.
                                <br><a href="{{ route('admin.events.index') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-times"></i> Clear Filters
                                </a>
                            @else
                                Start by creating your first event.
                                @if(Auth::user()->role === 'admin')
                                    <br><a href="{{ route('admin.events.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus"></i> Create Event
                                    </a>
                                @endif
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Toggle status confirmation
        function confirmToggleStatus(eventName, toggleUrl, currentStatus) {
            const action = currentStatus ? 'deactivate' : 'activate';
            const actionText = currentStatus ? 'Deactivate' : 'Activate';

            customConfirm({
                title: `${actionText} Event?`,
                text: `Are you sure you want to ${action} event "${eventName}"?`,
                icon: 'question',
                confirmButtonText: `Yes, ${action.toLowerCase()}!`,
                confirmButtonColor: currentStatus ? '#d33' : '#28a745'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = toggleUrl;

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(csrfInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Delete confirmation
        function confirmDelete(eventName, deleteUrl) {
            customConfirm({
                title: 'Delete Event?',
                text: `Are you sure you want to delete event "${eventName}"? This action cannot be undone.`,
                icon: 'warning',
                confirmButtonText: 'Yes, delete it!',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(csrfInput);

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush

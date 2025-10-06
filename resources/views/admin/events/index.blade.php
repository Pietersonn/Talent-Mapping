@extends('admin.layouts.app')

@section('title', 'Event Management')
@section('page-title', 'Event Management')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Event Management</li>
@endsection

@section('content')
    <div class="container-fluid">

        {{-- =========================
             FILTERS FIRST (rapi)
        ========================== --}}
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-filter mr-1"></i> Filters
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.events.index') }}">
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-3">
                            <label for="search" class="mb-1">Search</label>
                            <input type="text" id="search" name="search" class="form-control"
                                placeholder="name, code, desc..." value="{{ request('search') }}">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="status" class="mb-1">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">All</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="pic_id" class="mb-1">PIC</label>
                            <select name="pic_id" id="pic_id" class="form-control">
                                <option value="">All</option>
                                @foreach ($pics as $pic)
                                    <option value="{{ $pic->id }}"
                                        {{ request('pic_id') == $pic->id ? 'selected' : '' }}>
                                        {{ $pic->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="date_from" class="mb-1">From</label>
                            <input type="date" id="date_from" name="date_from" class="form-control"
                                value="{{ request('date_from') }}">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="date_to" class="mb-1">To</label>
                            <input type="date" id="date_to" name="date_to" class="form-control"
                                value="{{ request('date_to') }}">
                        </div>

                        <div class="form-group col-md-1 text-right">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary btn-block mt-1">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- =========================
             EVENTS TABLE + TOOLS
        ========================== --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-1"></i>
                    Events List
                </h3>

                @if (Auth::user()->role === 'admin')
                    <div class="card-tools">
                        <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Create New Event
                        </a>
                        {{-- Export PDF (ikutkan filter aktif) --}}
                        <a href="{{ route('admin.events.export.pdf', request()->query()) }}"
                            class="btn btn-outline-danger btn-sm ml-1">
                            <i class="fas fa-file-pdf mr-1"></i> Export PDF
                        </a>
                    </div>
                @endif
            </div>

            <div class="card-body">
                @if ($events->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width:28%;">Event</th>
                                    <th style="width:18%;">Company</th>
                                    <th style="width:10%;">Code</th>
                                    <th style="width:14%;">PIC</th>
                                    <th style="width:14%;">Date Range</th>
                                    <th style="width:10%;" class="text-center">Participants</th>
                                    <th style="width:8%;">Status</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td>
                                            <div class="truncate-2">{{ $event->name }}</div>
                                            @if ($event->description)
                                                <div class="text-muted small text-truncate" style="max-width: 260px;">
                                                    {{ Str::limit($event->description, 80) }}
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Company --}}
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;">
                                                {{ $event->company ?? '—' }}
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge badge-secondary">{{ $event->event_code }}</span>
                                        </td>

                                        <td>
                                            @if ($event->pic)
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
                                                @if ($event->max_participants)
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
                                                    class="btn btn-info btn-sm" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if (Auth::user()->role === 'admin')
                                                    <a href="{{ route('admin.events.edit', $event) }}"
                                                        class="btn btn-warning btn-sm" title="Edit Event">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <button type="button"
                                                        class="btn btn-{{ $event->is_active ? 'secondary' : 'success' }} btn-sm"
                                                        onclick="confirmToggleStatus('{{ $event->name }}', '{{ route('admin.events.toggle-status', $event) }}', {{ $event->is_active ? 'true' : 'false' }})"
                                                        title="{{ $event->is_active ? 'Deactivate' : 'Activate' }} Event">
                                                        <i class="fas fa-power-off"></i>
                                                    </button>

                                                    @if ($event->participants()->count() == 0)
                                                        <button type="button" class="btn btn-danger btn-sm"
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

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} of
                            {{ $events->total() }} events
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
                            @if (request()->hasAny(['search', 'status', 'pic_id', 'date_from', 'date_to']))
                                No events match your current filters.
                                <br><a href="{{ route('admin.events.index') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-times"></i> Clear Filters
                                </a>
                            @else
                                Start by creating your first event.
                                @if (Auth::user()->role === 'admin')
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

@push('styles')
    <style>
        .truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            max-width: 260px;
        }
    </style>
@endpush

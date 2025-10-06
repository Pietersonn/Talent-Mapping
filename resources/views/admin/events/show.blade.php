@extends('admin.layouts.app')

@section('title', 'Event: ' . $event->name)
@section('page-title', 'Event: ' . $event->name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Event Management</a></li>
    <li class="breadcrumb-item active">{{ $event->name }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        {{-- Header ringkas: nama + company + status + code --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-start w-100">
                            <div>
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ $event->name }}
                                </h3>
                                <div class="text-muted small mt-1">
                                    <i class="fas fa-building mr-1"></i>
                                    {{ $event->company ?? '—' }}
                                </div>
                            </div>
                            <div class="card-tools">
                                <span class="badge badge-{{ $event->status_badge }} mr-2">{{ $event->status_display }}</span>
                                <span class="badge badge-secondary">{{ $event->event_code }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            {{-- Kiri: info singkat --}}
                            <div class="col-md-8">
                                @if($event->description)
                                    <p class="text-muted">{{ $event->description }}</p>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-calendar mr-1"></i> Date Range:</strong><br>
                                        {{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}
                                        <small class="text-muted d-block">
                                            ({{ $event->start_date->diffInDays($event->end_date) + 1 }} days)
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-user-tie mr-1"></i> Person in Charge:</strong><br>
                                        @if($event->pic)
                                            {{ $event->pic->name }}
                                            <small class="text-muted d-block">{{ $event->pic->email }}</small>
                                        @else
                                            <span class="text-muted">No PIC assigned</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-users mr-1"></i> Participants:</strong><br>
                                        {{ $stats['total_participants'] }}
                                        @if($event->max_participants)
                                            / {{ $event->max_participants }}
                                            <small class="text-muted d-block">Maximum allowed</small>
                                        @else
                                            <small class="text-muted d-block">Unlimited</small>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-calendar-plus mr-1"></i> Created:</strong><br>
                                        {{ $event->created_at->format('d M Y, H:i') }}
                                        <small class="text-muted d-block">{{ $event->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Kanan: actions ringkas --}}
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning btn-block btn-sm mb-2">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit Event
                                            </a>

                                            <button type="button"
                                                    class="btn btn-{{ $event->is_active ? 'secondary' : 'success' }} btn-block btn-sm mb-2"
                                                    onclick="confirmToggleStatus('{{ $event->name }}', '{{ route('admin.events.toggle-status', $event) }}', {{ $event->is_active ? 'true' : 'false' }})">
                                                <i class="fas fa-power-off mr-1"></i>
                                                {{ $event->is_active ? 'Deactivate' : 'Activate' }} Event
                                            </button>

                                            @if($stats['total_participants'] == 0)
                                                <button type="button"
                                                        class="btn btn-danger btn-block btn-sm mb-2"
                                                        onclick="confirmDelete('{{ $event->name }}', '{{ route('admin.events.destroy', $event) }}')">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Delete Event
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-outline-danger btn-block btn-sm mb-2" disabled title="Cannot delete event with participants">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Delete Event
                                                </button>
                                            @endif
                                        @endif

                                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary btn-block btn-sm">
                                            <i class="fas fa-arrow-left mr-1"></i>
                                            Back to Events
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> {{-- row --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- =========================
             Participants (tanpa cards warna)
        ========================== --}}
        @if($stats['total_participants'] > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-1"></i>
                        Event Participants ({{ $stats['total_participants'] }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width:22%;">Participant</th>
                                    <th style="width:24%;">Email</th>
                                    <th style="width:18%;">Registered</th>
                                    <th style="width:12%;">Test</th>
                                    <th style="width:12%;">Result</th>
                                    <th style="width:12%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($event->participants as $participant)
                                    <tr>
                                        <td><strong>{{ $participant->name }}</strong></td>
                                        <td>{{ $participant->email }}</td>
                                        <td>
                                            <small>{{ $participant->pivot->created_at->format('d M Y, H:i') }}</small>
                                        </td>
                                        <td>
                                            @if($participant->pivot->test_completed)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check mr-1"></i>Completed
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock mr-1"></i>Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($participant->pivot->results_sent)
                                                <span class="badge badge-info">
                                                    <i class="fas fa-paper-plane mr-1"></i>Sent
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-envelope mr-1"></i>Not Sent
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if(Auth::user()->role === 'admin')
                                                    <button type="button" class="btn btn-info btn-sm" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    @if($participant->pivot->test_completed && !$participant->pivot->results_sent)
                                                        <button type="button" class="btn btn-success btn-sm" title="Send Results">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    @endif

                                                    <button type="button" class="btn btn-danger btn-sm" title="Remove from Event">
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
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Participants Yet</h5>
                    <p class="text-muted">
                        This event doesn't have any participants registered yet.
                        @if(!$event->is_active)
                            <br><span class="text-warning">Event is inactive — users cannot register.</span>
                        @endif
                    </p>
                    @if(Auth::user()->role === 'admin' && !$event->is_active)
                        <button type="button"
                                class="btn btn-success mt-2"
                                onclick="confirmToggleStatus('{{ $event->name }}', '{{ route('admin.events.toggle-status', $event) }}', false)">
                            <i class="fas fa-power-off mr-1"></i>
                            Activate Event
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function confirmToggleStatus(eventName, toggleUrl, currentStatus) {
            const action = currentStatus ? 'deactivate' : 'activate';
            const actionText = currentStatus ? 'Deactivate' : 'Activate';
            let warningText = `Are you sure you want to ${action} event "${eventName}"?`;

            @if($stats['total_participants'] > 0)
                if (!currentStatus) {
                    warningText += '\n\nThis event has {{ $stats["total_participants"] }} participants.';
                }
            @endif

            customConfirm({
                title: `${actionText} Event?`,
                text: warningText,
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

        function confirmDelete(eventName, deleteUrl) {
            customConfirm({
                title: 'Delete Event?',
                text: `Are you sure you want to delete event "${eventName}"? This action cannot be undone and will remove all associated data.`,
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

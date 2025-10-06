@extends('admin.layouts.app')

@section('title', 'Edit Event: ' . $event->name)
@section('page-title', 'Edit Event: ' . $event->name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Event Management</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.events.show', $event) }}">{{ $event->name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit mr-1"></i>
                            Edit Event: {{ $event->name }}
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-{{ $event->status_badge }} mr-2">
                                {{ $event->status_display }}
                            </span>
                            <a href="{{ route('admin.events.show', $event) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Back to Event
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('admin.events.update', $event) }}" method="POST" id="editEventForm">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <!-- Event Information -->
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="name" class="required">Event Name</label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $event->name) }}"
                                               placeholder="Enter event name"
                                               maxlength="100"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="event_code" class="required">Event Code</label>
                                        <input type="text"
                                               class="form-control @error('event_code') is-invalid @enderror"
                                               id="event_code"
                                               name="event_code"
                                               value="{{ old('event_code', $event->event_code) }}"
                                               maxlength="15"
                                               required>
                                        @error('event_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if($event->participants()->count() > 0)
                                            <small class="form-text text-warning">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Event has {{ $event->participants()->count() }} participants. Change code carefully.
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- ADD: Company -->
                            <div class="form-group">
                                <label for="company">Company / Organizer</label>
                                <input type="text"
                                       class="form-control @error('company') is-invalid @enderror"
                                       id="company"
                                       name="company"
                                       value="{{ old('company', $event->company) }}"
                                       maxlength="100"
                                       placeholder="e.g., Dispora Kalsel / BCTI">
                                @error('company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="3"
                                          placeholder="Enter event description (optional)"
                                          maxlength="1000">{{ old('description', $event->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date Range -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date" class="required">Start Date</label>
                                        <input type="date"
                                               class="form-control @error('start_date') is-invalid @enderror"
                                               id="start_date"
                                               name="start_date"
                                               value="{{ old('start_date', $event->start_date->format('Y-m-d')) }}"
                                               required>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if($event->start_date <= now())
                                            <small class="form-text text-info">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Original start date: {{ $event->start_date->format('d M Y') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date" class="required">End Date</label>
                                        <input type="date"
                                               class="form-control @error('end_date') is-invalid @enderror"
                                               id="end_date"
                                               name="end_date"
                                               value="{{ old('end_date', $event->end_date->format('Y-m-d')) }}"
                                               required>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- PIC Assignment & Settings -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pic_id">Person in Charge (PIC)</label>
                                        <select class="form-control @error('pic_id') is-invalid @enderror"
                                                id="pic_id"
                                                name="pic_id">
                                            <option value="">No PIC assigned</option>
                                            @foreach($pics as $pic)
                                                <option value="{{ $pic->id }}"
                                                        {{ old('pic_id', $event->pic_id) == $pic->id ? 'selected' : '' }}>
                                                    {{ $pic->name }} ({{ $pic->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('pic_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if($event->pic)
                                            <small class="form-text text-info">
                                                Current PIC: {{ $event->pic->name }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="max_participants">Maximum Participants</label>
                                        <input type="number"
                                               class="form-control @error('max_participants') is-invalid @enderror"
                                               id="max_participants"
                                               name="max_participants"
                                               value="{{ old('max_participants', $event->max_participants) }}"
                                               min="1"
                                               max="1000">
                                        @error('max_participants')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if($event->participants()->count() > 0)
                                            <small class="form-text text-warning">
                                                Current participants: {{ $event->participants()->count() }}
                                                @if($event->max_participants && $event->participants()->count() > 0)
                                                    / {{ $event->max_participants }}
                                                @endif
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', $event->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        <strong>Event Active</strong>
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    @if($event->participants()->count() > 0)
                                        <i class="fas fa-users mr-1 text-warning"></i>
                                        Event has {{ $event->participants()->count() }} participants.
                                        Deactivating will hide the event but preserve data.
                                    @else
                                        Active events are visible to users and can accept participants
                                    @endif
                                </small>
                            </div>

                            <!-- Event Statistics -->
                            @if($event->participants()->count() > 0)
                                <div class="alert alert-info">
                                    <h5><i class="fas fa-info-circle mr-1"></i> Event Statistics</h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>{{ $event->participants()->count() }}</strong><br>
                                            <small>Total Participants</small>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ $event->participants()->where('test_completed', true)->count() }}</strong><br>
                                            <small>Completed Tests</small>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ $event->participants()->where('results_sent', true)->count() }}</strong><br>
                                            <small>Results Sent</small>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ $event->created_at->format('d M Y') }}</strong><br>
                                            <small>Created</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('admin.events.show', $event) }}" class="btn btn-secondary">
                                        <i class="fas fa-times mr-1"></i>
                                        Cancel
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i>
                                        Update Event
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Auto-uppercase event code
            $('#event_code').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            // Date validation
            $('#start_date').on('change', function() {
                const startDate = $(this).val();
                $('#end_date').attr('min', startDate);

                const endDate = $('#end_date').val();
                if (endDate && endDate < startDate) {
                    $('#end_date').val('');
                }
            });

            // Warn about participant limit changes
            $('#max_participants').on('change', function() {
                const newLimit = parseInt($(this).val());
                const currentParticipants = {{ $event->participants()->count() }};

                if (newLimit && newLimit < currentParticipants) {
                    showWarningToast(`Warning: New limit (${newLimit}) is less than current participants (${currentParticipants})`);
                }
            });

            // Form validation
            $('#editEventForm').on('submit', function(e) {
                const startDate = new Date($('#start_date').val());
                const endDate = new Date($('#end_date').val());

                if (endDate < startDate) {
                    e.preventDefault();
                    showErrorToast('End date must be after or equal to start date.');
                    return false;
                }

                // Check participant limit
                const maxParticipants = parseInt($('#max_participants').val());
                const currentParticipants = {{ $event->participants()->count() }};

                if (maxParticipants && maxParticipants < currentParticipants) {
                    e.preventDefault();
                    customConfirm({
                        title: 'Participant Limit Warning',
                        text: `The new limit (${maxParticipants}) is less than current participants (${currentParticipants}). Continue anyway?`,
                        icon: 'warning',
                        confirmButtonText: 'Yes, update anyway'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#editEventForm')[0].submit();
                        }
                    });
                    return false;
                }

                // Show loading
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Updating...');
            });
        });

        function showErrorToast(message) {
            Swal.fire({
                title: message,
                icon: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        }

        function showWarningToast(message) {
            Swal.fire({
                title: message,
                icon: 'warning',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 6000,
                timerProgressBar: true
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        .required::after {
            content: " *";
            color: red;
        }
    </style>
@endpush

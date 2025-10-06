@extends('admin.layouts.app')

@section('title', 'Create New Event')
@section('page-title', 'Create New Event')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Event Management</a></li>
    <li class="breadcrumb-item active">Create Event</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-plus mr-1"></i>
                            Create New Event
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Back to Events
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('admin.events.store') }}" method="POST" id="createEventForm">
                        @csrf

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
                                               value="{{ old('name') }}"
                                               placeholder="Enter event name"
                                               maxlength="100"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Maximum 100 characters</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="event_code" class="required">Event Code</label>
                                        <input type="text"
                                               class="form-control @error('event_code') is-invalid @enderror"
                                               id="event_code"
                                               name="event_code"
                                               value="{{ old('event_code') }}"
                                               placeholder="e.g., WORKSHOP2025"
                                               maxlength="15"
                                               required>
                                        @error('event_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Unique code, will be auto-uppercased</small>
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
                                       value="{{ old('company') }}"
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
                                          maxlength="1000">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Maximum 1000 characters</small>
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
                                               value="{{ old('start_date') }}"
                                               min="{{ now()->format('Y-m-d') }}"
                                               required>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date" class="required">End Date</label>
                                        <input type="date"
                                               class="form-control @error('end_date') is-invalid @enderror"
                                               id="end_date"
                                               name="end_date"
                                               value="{{ old('end_date') }}"
                                               min="{{ now()->format('Y-m-d') }}"
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
                                            <option value="">Select PIC (optional)</option>
                                            @foreach($pics as $pic)
                                                <option value="{{ $pic->id }}" {{ old('pic_id') == $pic->id ? 'selected' : '' }}>
                                                    {{ $pic->name }} ({{ $pic->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('pic_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            @if($pics->count() == 0)
                                                <span class="text-warning">No PICs available. Please create PIC users first.</span>
                                            @else
                                                Assign a Person in Charge to manage this event
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="max_participants">Maximum Participants</label>
                                        <input type="number"
                                               class="form-control @error('max_participants') is-invalid @enderror"
                                               id="max_participants"
                                               name="max_participants"
                                               value="{{ old('max_participants') }}"
                                               min="1"
                                               max="1000"
                                               placeholder="e.g., 100">
                                        @error('max_participants')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Leave empty for unlimited participants</small>
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
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        <strong>Event Active</strong>
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Active events are visible to users and can accept participants
                                </small>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times mr-1"></i>
                                        Cancel
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i>
                                        Create Event
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

            // Date validation - end date must be >= start date
            $('#start_date').on('change', function() {
                const startDate = $(this).val();
                $('#end_date').attr('min', startDate);

                // Clear end date if it's before start date
                const endDate = $('#end_date').val();
                if (endDate && endDate < startDate) {
                    $('#end_date').val('');
                }
            });

            // Form validation before submit
            $('#createEventForm').on('submit', function(e) {
                const startDate = new Date($('#start_date').val());
                const endDate = new Date($('#end_date').val());

                if (endDate < startDate) {
                    e.preventDefault();
                    showErrorToast('End date must be after or equal to start date.');
                    return false;
                }

                // Show loading
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Creating...');
            });

            // Character counters
            $('#name').on('input', function() {
                const maxLength = 100;
                const currentLength = $(this).val().length;
                const remaining = maxLength - currentLength;

                if (remaining < 20) {
                    $(this).next('.invalid-feedback').remove();
                    $(this).after(`<small class="form-text ${remaining < 0 ? 'text-danger' : 'text-warning'}">
                        ${remaining < 0 ? 'Exceeded by' : 'Remaining'}: ${Math.abs(remaining)} characters
                    </small>`);
                }
            });

            $('#description').on('input', function() {
                const maxLength = 1000;
                const currentLength = $(this).val().length;
                const remaining = maxLength - currentLength;

                if (remaining < 100) {
                    $(this).siblings('.form-text').remove();
                    $(this).after(`<small class="form-text ${remaining < 0 ? 'text-danger' : 'text-warning'}">
                        ${remaining < 0 ? 'Exceeded by' : 'Remaining'}: ${Math.abs(remaining)} characters
                    </small>`);
                }
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
    </script>
@endpush

@push('styles')
    <style>
        .required::after {
            content: " *";
            color: red;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-valid {
            border-color: #28a745;
        }
    </style>
@endpush

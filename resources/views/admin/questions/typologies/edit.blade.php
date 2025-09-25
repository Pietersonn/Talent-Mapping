@extends('admin.layouts.app')

@section('title', 'Edit Typology')
@section('page-title', 'Edit ST-30 Typology')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.typologies.index') }}">Typologies</a></li>
    <li class="breadcrumb-item active">Edit {{ $typology->typology_code }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit"></i> Edit Typology: {{ $typology->typology_code }}
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.questions.typologies.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <a href="{{ route('admin.questions.typologies.show', $typology) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('admin.questions.typologies.update', $typology) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <!-- Typology Code -->
                                    <div class="form-group">
                                        <label for="typology_code" class="required">Typology Code</label>
                                        <input type="text"
                                            class="form-control @error('typology_code') is-invalid @enderror"
                                            id="typology_code" name="typology_code"
                                            value="{{ old('typology_code', $typology->typology_code) }}"
                                            placeholder="e.g., AMB, COM, ADM" maxlength="10"
                                            style="text-transform: uppercase;">
                                        <small class="form-text text-muted">
                                            Use 2-3 letter code for the typology (will be converted to uppercase)
                                        </small>
                                        @error('typology_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Typology Name -->
                                    <div class="form-group">
                                        <label for="typology_name" class="required">Typology Name</label>
                                        <input type="text"
                                            class="form-control @error('typology_name') is-invalid @enderror"
                                            id="typology_name" name="typology_name"
                                            value="{{ old('typology_name', $typology->typology_name) }}"
                                            placeholder="e.g., Ambassador, Communicator">
                                        @error('typology_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div class="form-group">
                                        <label for="description" class="required">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="4" placeholder="Describe the main characteristics and traits of this typology...">{{ old('description', $typology->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Characteristics -->
                                    <div class="form-group">
                                        <label for="characteristics">Key Characteristics</label>
                                        <textarea class="form-control @error('characteristics') is-invalid @enderror" id="characteristics"
                                            name="characteristics" rows="3" placeholder="List the key behavioral characteristics and traits...">{{ old('characteristics', $typology->characteristics) }}</textarea>
                                        <small class="form-text text-muted">
                                            Optional: Describe specific behavioral patterns and characteristics
                                        </small>
                                        @error('characteristics')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <!-- Strengths -->
                                    <div class="form-group">
                                        <label for="strengths">Strengths</label>
                                        <textarea class="form-control @error('strengths') is-invalid @enderror" id="strengths" name="strengths" rows="4"
                                            placeholder="List the main strengths and advantages of this typology...">{{ old('strengths', $typology->strengths) }}</textarea>
                                        <small class="form-text text-muted">
                                            Optional: What are the positive aspects and advantages?
                                        </small>
                                        @error('strengths')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Weaknesses -->
                                    <div class="form-group">
                                        <label for="weaknesses">Areas for Development</label>
                                        <textarea class="form-control @error('weaknesses') is-invalid @enderror" id="weaknesses" name="weaknesses"
                                            rows="4" placeholder="List areas that may need development or attention...">{{ old('weaknesses', $typology->weaknesses) }}</textarea>
                                        <small class="form-text text-muted">
                                            Optional: What areas might need improvement or development?
                                        </small>
                                        @error('weaknesses')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Career Suggestions -->
                                    <div class="form-group">
                                        <label for="career_suggestions">Career Suggestions</label>
                                        <textarea class="form-control @error('career_suggestions') is-invalid @enderror" id="career_suggestions"
                                            name="career_suggestions" rows="3" placeholder="Suggest suitable career paths and roles...">{{ old('career_suggestions', $typology->career_suggestions) }}</textarea>
                                        <small class="form-text text-muted">
                                            Optional: Recommended career paths and professional roles
                                        </small>
                                        @error('career_suggestions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="is_active"
                                                name="is_active" value="1"
                                                {{ old('is_active', $typology->is_active) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_active">
                                                Active Status
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">
                                            Enable this typology for use in assessments
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Usage Statistics -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card bg-info">
                                        <div class="card-header">
                                            <h5 class="mb-0 text-white">
                                                <i class="fas fa-chart-bar"></i> Usage Statistics
                                            </h5>
                                        </div>
                                        <div class="card-body bg-white">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="info-box">
                                                        <span class="info-box-icon bg-info"><i
                                                                class="fas fa-question-circle"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">ST-30 Questions</span>
                                                            <span
                                                                class="info-box-number">{{ $typology->st30Questions()->count() }}</span>
                                                            <span class="info-box-desc">Questions using this
                                                                typology</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-box">
                                                        <span class="info-box-icon bg-success"><i
                                                                class="fas fa-user-check"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Test Responses</span>
                                                            <span class="info-box-number">0</span>
                                                            <span class="info-box-desc">Times selected in tests</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-box">
                                                        <span class="info-box-icon bg-warning"><i
                                                                class="fas fa-calendar-alt"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Last Updated</span>
                                                            <span
                                                                class="info-box-number text-sm">{{ $typology->updated_at->format('M d, Y') }}</span>
                                                            <span
                                                                class="info-box-desc">{{ $typology->updated_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Typology
                                    </button>
                                    <button type="reset" class="btn btn-secondary ml-2">
                                        <i class="fas fa-undo"></i> Reset Changes
                                    </button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="{{ route('admin.questions.typologies.show', $typology) }}" class="btn btn-info mr-2">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    <a href="{{ route('admin.questions.typologies.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
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
            // Character counters for text areas
            function updateCharacterCount(textareaId, maxLength) {
                const textarea = $('#' + textareaId);
                const helpText = textarea.siblings('.form-text').first();
                const currentLength = textarea.val().length;
                const remaining = maxLength - currentLength;

                if (remaining < 50) {
                    helpText.addClass('text-warning').removeClass('text-muted');
                } else {
                    helpText.addClass('text-muted').removeClass('text-warning');
                }

                helpText.text(helpText.data('original-text') + ` (${remaining} characters remaining)`);
            }

            // Initialize character counters
            $('.form-text').each(function() {
                $(this).data('original-text', $(this).text());
            });

            $('#strengths, #weaknesses, #career_suggestions').on('input', function() {
                const maxLength = 1000;
                updateCharacterCount($(this).attr('id'), maxLength);
            });

            // Track original form data for change detection
            const originalFormData = $('form').serialize();

            // Form submission confirmation with change detection
            $('form').on('submit', function(e) {
                e.preventDefault();

                const currentFormData = $('form').serialize();
                const hasChanges = originalFormData !== currentFormData;

                if (!hasChanges) {
                    showInfoToast('No changes detected.');
                    return;
                }

                const typologyName = $('#typology_name').val();
                const typologyCode = $('#typology_code').val();

                customConfirm({
                    title: 'Update Typology?',
                    text: `Save changes to typology "${typologyName}" (${typologyCode})?`,
                    icon: 'question',
                    confirmButtonText: 'Yes, update it!',
                    confirmButtonColor: '#ffc107'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading('Updating Typology...',
                            'Please wait while we save the changes...');
                        this.submit();
                    }
                });
            });

            // Validate typology code format
            $('#typology_code').on('input', function() {
                const code = $(this).val().toUpperCase();
                $(this).val(code);

                if (code.length > 5) {
                    showErrorToast('Typology code cannot exceed 5 characters.');
                    $(this).val(code.substring(0, 5));
                }
            });

            // Preview functionality
            $('#preview-btn').on('click', function(e) {
                e.preventDefault();

                const typologyData = {
                    name: $('#typology_name').val(),
                    code: $('#typology_code').val(),
                    strengths: $('#strengths').val(),
                    weaknesses: $('#weaknesses').val(),
                    career: $('#career_suggestions').val(),
                    status: $('#is_active').is(':checked') ? 'Active' : 'Inactive'
                };

                Swal.fire({
                    title: `${typologyData.name} (${typologyData.code})`,
                    html: `
                        <div class="text-left">
                            <div class="mb-2">
                                <span class="badge badge-${typologyData.status === 'Active' ? 'success' : 'secondary'}">${typologyData.status}</span>
                            </div>
                            <h6 class="text-success">Strengths:</h6>
                            <p class="small">${typologyData.strengths || 'Not specified'}</p>
                            <h6 class="text-warning">Areas for Development:</h6>
                            <p class="small">${typologyData.weaknesses || 'Not specified'}</p>
                            <h6 class="text-info">Career Suggestions:</h6>
                            <p class="small">${typologyData.career || 'Not specified'}</p>
                        </div>
                    `,
                    width: 600,
                    confirmButtonText: 'Close Preview'
                });
            });

            // Unsaved changes warning
            window.addEventListener('beforeunload', function(e) {
                const currentFormData = $('form').serialize();
                if (originalFormData !== currentFormData) {
                    e.preventDefault();
                    e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                    return 'You have unsaved changes. Are you sure you want to leave?';
                }
            });

            // Remove beforeunload on form submit
            $('form').on('submit', function() {
                window.removeEventListener('beforeunload', arguments.callee);
            });

            // Reset form
            $('#reset-btn').on('click', function(e) {
                e.preventDefault();

                customConfirm({
                    title: 'Reset Form?',
                    text: 'This will discard all unsaved changes. Are you sure?',
                    icon: 'warning',
                    confirmButtonText: 'Yes, reset!',
                    confirmButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('form')[0].reset();
                        showSuccessToast('Form reset successfully!');
                    }
                });
            });

            // Keyboard shortcuts
            $(document).keydown(function(e) {
                if (e.ctrlKey || e.metaKey) {
                    switch (e.which) {
                        case 83: // Ctrl+S for Save
                            e.preventDefault();
                            $('form').submit();
                            break;
                        case 80: // Ctrl+P for Preview
                            e.preventDefault();
                            $('#preview-btn').click();
                            break;
                        case 82: // Ctrl+R for Reset
                            e.preventDefault();
                            $('#reset-btn').click();
                            break;
                    }
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .required::after {
            content: " *";
            color: #dc3545;
        }

        .character-count {
            text-align: right;
            font-size: 0.75rem;
        }

        .info-box {
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            border-radius: .25rem;
            background-color: #fff;
            display: flex;
            margin-bottom: 1rem;
            min-height: 80px;
            padding: .5rem;
            position: relative;
            width: 100%;
        }

        .info-box-icon {
            border-radius: .25rem;
            align-items: center;
            display: flex;
            font-size: 1.875rem;
            justify-content: center;
            text-align: center;
            width: 70px;
            color: #fff;
        }

        .info-box-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            line-height: 1.8;
            margin-left: .5rem;
            padding: 0 .5rem;
        }

        .info-box-text {
            display: block;
            font-size: .875rem;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .info-box-number {
            display: block;
            font-weight: 700;
            font-size: 1.125rem;
        }

        .info-box-desc {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }
    </style>
@endpush

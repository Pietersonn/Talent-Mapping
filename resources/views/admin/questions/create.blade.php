@extends('admin.layouts.app')

@section('title', 'Create Question Version')
@section('page-title', 'Create New Question Version')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item active">Create Version</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-1"></i>
                        Create New Question Version
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.questions.index') }}" class="btn btn-tool">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.questions.store') }}" method="POST">
                    @csrf

                    <div class="card-body">

                        <!-- Version Type -->
                        <div class="form-group">
                            <label for="type">Question Type <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror"
                                required>
                                <option value="">Select Question Type</option>
                                <option value="st30" {{ old('type') === 'st30' ? 'selected' : '' }}>
                                    ST-30 (Strength Typology - 30 Questions)
                                </option>
                                <option value="sjt" {{ old('type') === 'sjt' ? 'selected' : '' }}>
                                    SJT (Situational Judgment Test - 50 Questions)
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Choose the type of questions this version will contain
                            </small>
                        </div>

                        <!-- Version Name -->
                        <div class="form-group">
                            <label for="name">Version Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="e.g., ST-30 Version 2.0" value="{{ old('name') }}" maxlength="50" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Give this version a descriptive name (max 50 characters)
                            </small>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                rows="3" placeholder="Describe what makes this version different..." maxlength="500">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Optional description of changes or improvements (max 500 characters)
                            </small>
                        </div>

                        <!-- Information Box -->
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Important Notes:</h5>
                            <ul class="mb-0">
                                <li>New versions are created as <strong>inactive</strong> by default</li>
                                <li>You need to add questions before you can activate a version</li>
                                <li><strong>ST-30</strong> requires exactly <strong>30 questions</strong> (one for each
                                    typology statement)</li>
                                <li><strong>SJT</strong> requires exactly <strong>50 questions</strong> (10 per page, 5
                                    options each)</li>
                                <li>Only one version per type can be active at a time</li>
                            </ul>
                        </div>

                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Cancel
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus mr-1"></i>
                                    Create Version
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <!-- Help Card -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-question-circle mr-1"></i>
                        Need Help?
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>ST-30 (Strength Typology)</h5>
                            <p class="text-sm">
                                Contains 30 personality statements that participants rank in 4 stages.
                                Used to identify dominant personality types and work preferences.
                            </p>
                            <p class="text-sm">
                                <strong>Typologies:</strong> AMB, ADM, ANA, ARR, CAR, CMD, COM, CRE, DES, DIS, EDU, EVA,
                                EXP, INT
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>SJT (Situational Judgment Test)</h5>
                            <p class="text-sm">
                                Contains 50 situational questions with 5 answer choices each.
                                Used to measure 10 core competencies through scenario-based questions.
                            </p>
                            <p class="text-sm">
                                <strong>Competencies:</strong> Self Management, Thinking Skills, Leadership, Problem
                                Solving, Self Esteem, Communication, Career Attitude, Work with Others, Professional Ethics,
                                General Hardskills
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h5>Version Management Workflow</h5>
                            <ol class="text-sm">
                                <li><strong>Create Version</strong> - Start with inactive version</li>
                                <li><strong>Add Questions</strong> - Use question management tools</li>
                                <li><strong>Review & Test</strong> - Ensure all questions are complete</li>
                                <li><strong>Activate Version</strong> - Make it available for tests</li>
                                <li><strong>Monitor Usage</strong> - Track responses and performance</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

{{-- GANTI BAGIAN @push('scripts') YANG ADA DENGAN INI: --}}
@push('scripts')
    <script>
        $(document).ready(function() {
            // Auto-generate version name based on type selection
            $('#type').on('change', function() {
                var type = $(this).val();
                var nameField = $('#name');

                if (type && !nameField.val()) {
                    if (type === 'st30') {
                        nameField.val('ST-30 Version 1.0');
                    } else if (type === 'sjt') {
                        nameField.val('SJT Version 1.0');
                    }
                }
            });

            // Character counter for description with enhanced feedback
            $('#description').on('input', function() {
                var maxLength = 500;
                var currentLength = $(this).val().length;
                var remaining = maxLength - currentLength;

                var helpText = $(this).next('.form-text');
                var baseText = 'Optional description of changes or improvements';

                if (remaining < 50) {
                    helpText.html(baseText +
                        ` (<span class="text-danger">${remaining} characters remaining</span>)`);
                } else if (remaining < 100) {
                    helpText.html(baseText +
                        ` (<span class="text-warning">${remaining} characters remaining</span>)`);
                } else {
                    helpText.html(baseText + ' (max 500 characters)');
                }

                helpText.removeClass('text-muted text-warning text-danger').addClass(
                    remaining < 50 ? 'text-danger' : remaining < 100 ? 'text-warning' : 'text-muted'
                );
            });

            // Enhanced form validation with SweetAlert2
            $('form').on('submit', function(e) {
                e.preventDefault();

                var type = $('#type').val();
                var name = $('#name').val().trim();
                var description = $('#description').val().trim();

                // Validation checks
                if (!type) {
                    showErrorToast('Please select a question type.');
                    $('#type').focus();
                    return;
                }

                if (!name) {
                    showErrorToast('Please enter a version name.');
                    $('#name').focus();
                    return;
                }

                if (name.length < 3) {
                    showErrorToast('Version name must be at least 3 characters long.');
                    $('#name').focus();
                    return;
                }

                // Confirmation dialog with detailed info
                const typeDisplay = type === 'st30' ? 'ST-30 (Strength Typology)' :
                    'SJT (Situational Judgment Test)';
                const questionsRequired = type === 'st30' ? '30 questions' : '50 questions';

                Swal.fire({
                    title: 'Create New Version?',
                    html: `
                <div class="text-left">
                    <p><strong>Type:</strong> ${typeDisplay}</p>
                    <p><strong>Name:</strong> ${name}</p>
                    <p><strong>Required Questions:</strong> ${questionsRequired}</p>
                    ${description ? `<p><strong>Description:</strong> ${description}</p>` : ''}
                    <hr>
                    <p class="text-muted small">The new version will be created as inactive. You can add questions and then activate it when ready.</p>
                </div>
            `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, create version!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        showLoading('Creating Version...',
                            'Please wait while we create the new question version...');

                        // Submit the form
                        this.submit();
                    }
                });
            });

            // Smart suggestions based on existing versions
            $('#name').on('input', function() {
                const name = $(this).val();
                const type = $('#type').val();

                if (type && name.length > 2) {
                    // Simple version number suggestion
                    const versionMatch = name.match(/(\d+\.?\d*)/);
                    if (!versionMatch && !name.toLowerCase().includes('version')) {
                        $(this).next('.form-text').after(
                            '<small class="text-info d-block mt-1 version-suggestion">💡 Consider adding version number (e.g., "' +
                            name + ' v1.0")</small>'
                        );
                    } else {
                        $('.version-suggestion').remove();
                    }
                }
            });

            // Remove suggestions when field loses focus
            $('#name').on('blur', function() {
                setTimeout(() => $('.version-suggestion').fadeOut(), 3000);
            });

            // Keyboard shortcuts
            $(document).keydown(function(e) {
                if (e.ctrlKey || e.metaKey) {
                    switch (e.which) {
                        case 83: // Ctrl+S for Save
                            e.preventDefault();
                            $('form').submit();
                            break;
                        case 27: // Escape for Cancel
                            e.preventDefault();
                            window.location.href = '{{ route('admin.questions.index') }}';
                            break;
                    }
                }
            });

            // Form reset confirmation
            $('button[type="reset"]').on('click', function(e) {
                e.preventDefault();

                customConfirm({
                    title: 'Reset Form?',
                    text: 'This will clear all entered data. Are you sure?',
                    icon: 'warning',
                    confirmButtonText: 'Yes, reset!',
                    confirmButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('form')[0].reset();
                        $('.version-suggestion').remove();
                        showSuccessToast('Form reset successfully!');
                    }
                });
            });

            // Auto-focus first empty field
            setTimeout(function() {
                if (!$('#type').val()) {
                    $('#type').focus();
                } else if (!$('#name').val()) {
                    $('#name').focus();
                }
            }, 100);
        });
    </script>
@endpush

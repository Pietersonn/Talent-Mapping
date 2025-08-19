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
                        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
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
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="e.g., ST-30 Version 2.0"
                               value="{{ old('name') }}"
                               maxlength="50"
                               required>
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
                        <textarea name="description"
                                  id="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Describe what makes this version different..."
                                  maxlength="500">{{ old('description') }}</textarea>
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
                            <li><strong>ST-30</strong> requires exactly <strong>30 questions</strong> (one for each typology statement)</li>
                            <li><strong>SJT</strong> requires exactly <strong>50 questions</strong> (10 per page, 5 options each)</li>
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
                            <strong>Typologies:</strong> AMB, ADM, ANA, ARR, CAR, CMD, COM, CRE, DES, DIS, EDU, EVA, EXP, INT
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5>SJT (Situational Judgment Test)</h5>
                        <p class="text-sm">
                            Contains 50 situational questions with 5 answer choices each.
                            Used to measure 10 core competencies through scenario-based questions.
                        </p>
                        <p class="text-sm">
                            <strong>Competencies:</strong> Self Management, Thinking Skills, Leadership, Problem Solving, Self Esteem, Communication, Career Attitude, Work with Others, Professional Ethics, General Hardskills
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

    // Character counter for description
    $('#description').on('input', function() {
        var maxLength = 500;
        var currentLength = $(this).val().length;
        var remaining = maxLength - currentLength;

        var helpText = $(this).next('.form-text');
        if (remaining < 50) {
            helpText.html('Optional description of changes or improvements (' + remaining + ' characters remaining)');
            helpText.removeClass('text-muted').addClass('text-warning');
        } else {
            helpText.html('Optional description of changes or improvements (max 500 characters)');
            helpText.removeClass('text-warning').addClass('text-muted');
        }
    });

    // Form validation
    $('form').on('submit', function(e) {
        var type = $('#type').val();
        var name = $('#name').val();

        if (!type) {
            e.preventDefault();
            alert('Please select a question type.');
            $('#type').focus();
            return false;
        }

        if (!name || name.length < 3) {
            e.preventDefault();
            alert('Please enter a valid version name (at least 3 characters).');
            $('#name').focus();
            return false;
        }

        // Show loading state
        $(this).find('button[type="submit"]').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin mr-1"></i> Creating...'
        );
    });

    // Character counter real-time update
    $('#name').on('input', function() {
        var maxLength = 50;
        var currentLength = $(this).val().length;
        var remaining = maxLength - currentLength;

        var helpText = $(this).next('.invalid-feedback').length ?
                      $(this).next('.invalid-feedback').next('.form-text') :
                      $(this).next('.form-text');

        if (remaining < 10) {
            helpText.html('Give this version a descriptive name (' + remaining + ' characters remaining)');
            helpText.removeClass('text-muted').addClass('text-warning');
        } else {
            helpText.html('Give this version a descriptive name (max 50 characters)');
            helpText.removeClass('text-warning').addClass('text-muted');
        }
    });

    // Prevent form double submission
    var formSubmitted = false;
    $('form').on('submit', function(e) {
        if (formSubmitted) {
            e.preventDefault();
            return false;
        }
        formSubmitted = true;
    });

    // Reset form if user goes back
    $(window).on('pageshow', function(e) {
        if (e.originalEvent.persisted) {
            formSubmitted = false;
            $('button[type="submit"]').prop('disabled', false).html(
                '<i class="fas fa-plus mr-1"></i> Create Version'
            );
        }
    });
});
</script>
@endpush

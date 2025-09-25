@extends('admin.layouts.app')

@section('title', 'Create New Typology')
@section('page-title', 'Create New ST-30 Typology')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.questions.typologies.index') }}">Typologies</a></li>
<li class="breadcrumb-item active">Create New</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle"></i> Create New Typology
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.questions.typologies.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.questions.typologies.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <!-- Typology Code -->
                                <div class="form-group">
                                    <label for="typology_code" class="required">Typology Code</label>
                                    <input type="text"
                                           class="form-control @error('typology_code') is-invalid @enderror"
                                           id="typology_code"
                                           name="typology_code"
                                           value="{{ old('typology_code') }}"
                                           placeholder="e.g., AMB, COM, ADM"
                                           maxlength="10"
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
                                           id="typology_name"
                                           name="typology_name"
                                           value="{{ old('typology_name') }}"
                                           placeholder="e.g., Ambassador, Communicator">
                                    @error('typology_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description" class="required">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              rows="4"
                                              placeholder="Describe the main characteristics and traits of this typology...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Characteristics -->
                                <div class="form-group">
                                    <label for="characteristics">Key Characteristics</label>
                                    <textarea class="form-control @error('characteristics') is-invalid @enderror"
                                              id="characteristics"
                                              name="characteristics"
                                              rows="3"
                                              placeholder="List the key behavioral characteristics and traits...">{{ old('characteristics') }}</textarea>
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
                                    <textarea class="form-control @error('strengths') is-invalid @enderror"
                                              id="strengths"
                                              name="strengths"
                                              rows="4"
                                              placeholder="List the main strengths and advantages of this typology...">{{ old('strengths') }}</textarea>
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
                                    <textarea class="form-control @error('weaknesses') is-invalid @enderror"
                                              id="weaknesses"
                                              name="weaknesses"
                                              rows="4"
                                              placeholder="List areas that may need development or attention...">{{ old('weaknesses') }}</textarea>
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
                                    <textarea class="form-control @error('career_suggestions') is-invalid @enderror"
                                              id="career_suggestions"
                                              name="career_suggestions"
                                              rows="3"
                                              placeholder="Suggest suitable career paths and roles...">{{ old('career_suggestions') }}</textarea>
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
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
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

                        <!-- ST-30 Category Info -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-info-circle"></i> ST-30 Typology Categories
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Main Categories:</h6>
                                                <ul class="list-unstyled">
                                                    <li><span class="badge badge-primary">H</span> <strong>Headman</strong> - Leadership & influencing others</li>
                                                    <li><span class="badge badge-info">N</span> <strong>Networking</strong> - Building relationships & collaboration</li>
                                                    <li><span class="badge badge-success">S</span> <strong>Servicing</strong> - Helping & caring for others</li>
                                                    <li><span class="badge badge-warning">Gi</span> <strong>Generating Ideas</strong> - Creative & innovative thinking</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Additional Categories:</h6>
                                                <ul class="list-unstyled">
                                                    <li><span class="badge badge-secondary">T</span> <strong>Thinking</strong> - Analytical & logical processing</li>
                                                    <li><span class="badge badge-dark">R</span> <strong>Reasoning</strong> - Problem-solving & decision making</li>
                                                    <li><span class="badge badge-danger">E</span> <strong>Elementary</strong> - Basic operational tasks</li>
                                                    <li><span class="badge badge-light text-dark">Te</span> <strong>Technical</strong> - Specialized technical skills</li>
                                                </ul>
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
                                    <i class="fas fa-save"></i> Create Typology
                                </button>
                                <button type="reset" class="btn btn-secondary ml-2">
                                    <i class="fas fa-undo"></i> Reset Form
                                </button>
                            </div>
                            <div class="col-md-6 text-right">
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

            // Form submission confirmation for important operations
            $('form').on('submit', function(e) {
                e.preventDefault();

                const typologyName = $('#typology_name').val();
                const typologyCode = $('#typology_code').val();

                if (!typologyName || !typologyCode) {
                    showErrorToast('Please fill in all required fields.');
                    return;
                }

                customConfirm({
                    title: 'Create New Typology?',
                    text: `Create typology "${typologyName}" with code "${typologyCode}"?`,
                    icon: 'question',
                    confirmButtonText: 'Yes, create it!',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading('Creating Typology...', 'Please wait while we save the typology...');
                        this.submit();
                    }
                });
            });

            // Auto-generate typology code based on name
            $('#typology_name').on('input', function() {
                const name = $(this).val();
                const code = name.substring(0, 3).toUpperCase();
                if (code && !$('#typology_code').val()) {
                    $('#typology_code').val(code);
                }
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
                    career: $('#career_suggestions').val()
                };

                if (!typologyData.name || !typologyData.code) {
                    showErrorToast('Please fill in name and code to preview.');
                    return;
                }

                Swal.fire({
                    title: `${typologyData.name} (${typologyData.code})`,
                    html: `
                        <div class="text-left">
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

            // Keyboard shortcuts
            $(document).keydown(function(e) {
                if (e.ctrlKey || e.metaKey) {
                    switch(e.which) {
                        case 83: // Ctrl+S for Save
                            e.preventDefault();
                            $('form').submit();
                            break;
                        case 80: // Ctrl+P for Preview
                            e.preventDefault();
                            $('#preview-btn').click();
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

.card.bg-light {
    border-left: 4px solid #007bff;
}

.form-group {
    margin-bottom: 1.5rem;
}

.badge {
    font-size: 0.75rem;
    margin-right: 0.25rem;
}

.list-unstyled li {
    margin-bottom: 0.25rem;
}
</style>
@endpush

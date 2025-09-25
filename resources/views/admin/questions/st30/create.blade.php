@extends('admin.layouts.app')

@section('title', 'Create ST-30 Question')
@section('page-title', 'Create New ST-30 Question')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.st30.index') }}">ST-30 Questions</a></li>
    <li class="breadcrumb-item active">Create Question</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus mr-1"></i>
                    Create ST-30 Question
                </h3>
            </div>
            <form action="{{ route('admin.questions.st30.store') }}" method="POST" id="st30Form">
                @csrf
                <div class="card-body">
                    <input type="hidden" name="version_id" value="{{ $selectedVersion->id }}">

                    <!-- Version Info -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        Creating question for version: <strong>{{ $selectedVersion->display_name }}</strong>
                    </div>

                    <!-- Question Number -->
                    <div class="form-group">
                        <label for="number">Question Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('number') is-invalid @enderror"
                               id="number" name="number" value="{{ old('number', $nextNumber) }}"
                               min="1" max="30" required>
                        @error('number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Question number in sequence (1-30)</small>
                    </div>

                    <!-- Statement -->
                    <div class="form-group">
                        <label for="statement">Statement <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('statement') is-invalid @enderror"
                                  id="statement" name="statement" rows="4" required
                                  maxlength="500" placeholder="Enter the strength typology statement...">{{ old('statement') }}</textarea>
                        @error('statement')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <span id="statement_char_count">0</span>/500 characters
                        </small>
                    </div>

                    <!-- Typology -->
                    <div class="form-group">
                        <label for="typology_code">Typology <span class="text-danger">*</span></label>
                        <select class="form-control @error('typology_code') is-invalid @enderror"
                                id="typology_code" name="typology_code" required>
                            <option value="">Select Typology...</option>
                            @foreach($typologies as $typology)
                                <option value="{{ $typology->typology_code }}"
                                        {{ old('typology_code') == $typology->typology_code ? 'selected' : '' }}>
                                    {{ $typology->typology_code }} - {{ $typology->typology_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('typology_code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Typology Description -->
                    <div id="typology_description" class="alert alert-light" style="display: none;">
                        <strong>Description:</strong>
                        <div id="typology_desc_text"></div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save mr-1"></i> Create Question
                    </button>
                    <a href="{{ route('admin.questions.st30.index', ['version' => $selectedVersion->id]) }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Panel -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-question-circle mr-1"></i>
                    ST-30 Guidelines
                </h3>
            </div>
            <div class="card-body">
                <h6>What is ST-30?</h6>
                <p class="text-sm">
                    Strength Typology-30 measures individual strength patterns across 14 different typologies to identify natural talents and preferences.
                </p>

                <h6>Question Structure:</h6>
                <ul class="text-sm">
                    <li><strong>Statement:</strong> Clear, behavior-based statement</li>
                    <li><strong>Typology:</strong> One of 14 personality typologies</li>
                    <li><strong>Scoring:</strong> Used in 4-stage selection process</li>
                </ul>

                <h6>Available Typologies:</h6>
                <div class="text-sm">
                    @foreach($typologies as $typology)
                        <span class="badge badge-outline-primary mr-1 mb-1" data-toggle="tooltip"
                              title="{{ $typology->strength_description }}">
                            {{ $typology->typology_code }}
                        </span>
                    @endforeach
                </div>

                <h6>Writing Tips:</h6>
                <ul class="text-sm">
                    <li>Use clear, specific behavior descriptions</li>
                    <li>Avoid ambiguous or complex statements</li>
                    <li>Focus on natural preferences and tendencies</li>
                    <li>Keep statements positive and strength-focused</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Typology descriptions
    const typologyDescriptions = {
        @foreach($typologies as $typology)
            '{{ $typology->typology_code }}': '{{ addslashes($typology->strength_description) }}',
        @endforeach
    };

    // Character counting
    $('#statement').on('input', function() {
        var count = $(this).val().length;
        $('#statement_char_count').text(count);

        if (count > 450) {
            $('#statement_char_count').addClass('text-warning');
        } else {
            $('#statement_char_count').removeClass('text-warning');
        }
    });

    // Show typology description
    $('#typology_code').on('change', function() {
        var selectedCode = $(this).val();
        if (selectedCode && typologyDescriptions[selectedCode]) {
            $('#typology_desc_text').text(typologyDescriptions[selectedCode]);
            $('#typology_description').show();
        } else {
            $('#typology_description').hide();
        }
    });

    // Form validation
    $('#st30Form').on('submit', function(e) {
        var statement = $('#statement').val().trim();
        var typology = $('#typology_code').val();

        if (!statement || !typology) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }

        // Disable submit button to prevent double submission
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Creating...');
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush

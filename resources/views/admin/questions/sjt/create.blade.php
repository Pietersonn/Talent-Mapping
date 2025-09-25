@extends('admin.layouts.app')

@section('title', 'Create SJT Question')
@section('page-title', 'Create New SJT Question')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.sjt.index') }}">SJT Questions</a></li>
    <li class="breadcrumb-item active">Create Question</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus mr-1"></i>
                    Create SJT Question
                </h3>
            </div>
            <form action="{{ route('admin.questions.sjt.store') }}" method="POST" id="sjtForm">
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
                               min="1" max="50" required>
                        @error('number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Question number in sequence (1-50)</small>
                    </div>

                    <!-- Question Text -->
                    <div class="form-group">
                        <label for="question_text">Situation Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('question_text') is-invalid @enderror"
                                  id="question_text" name="question_text" rows="5" required
                                  maxlength="1000" placeholder="Describe the situational scenario...">{{ old('question_text') }}</textarea>
                        @error('question_text')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <span id="question_char_count">0</span>/1000 characters
                        </small>
                    </div>

                    <!-- Competency -->
                    <div class="form-group">
                        <label for="competency_code">Target Competency <span class="text-danger">*</span></label>
                        <select class="form-control @error('competency_code') is-invalid @enderror"
                                id="competency_code" name="competency_code" required>
                            <option value="">Select Competency...</option>
                            @foreach($competencies as $competency)
                                <option value="{{ $competency->competency_code }}"
                                        {{ old('competency_code') == $competency->competency_code ? 'selected' : '' }}>
                                    {{ $competency->competency_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('competency_code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Answer Options -->
                    <div class="form-group">
                        <label>Answer Options <span class="text-danger">*</span></label>
                        <small class="form-text text-muted mb-3">
                            Provide 5 different response options (A-E) with scores 0-4 based on competency relevance.
                        </small>

                        @php $optionLetters = ['A', 'B', 'C', 'D', 'E']; @endphp
                        @for($i = 0; $i < 5; $i++)
                            <div class="card mb-3">
                                <div class="card-header py-2">
                                    <h6 class="card-title mb-0">
                                        <span class="badge badge-primary mr-2">{{ $optionLetters[$i] }}</span>
                                        Option {{ $optionLetters[$i] }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group mb-2">
                                                <label for="option_{{ $i }}_text">Response Text</label>
                                                <textarea class="form-control @error('options.'.$i.'.option_text') is-invalid @enderror"
                                                          id="option_{{ $i }}_text"
                                                          name="options[{{ $i }}][option_text]"
                                                          rows="3" required maxlength="500"
                                                          placeholder="What would you do in this situation?">{{ old('options.'.$i.'.option_text') }}</textarea>
                                                @error('options.'.$i.'.option_text')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-2">
                                                <label for="option_{{ $i }}_score">Score</label>
                                                <select class="form-control @error('options.'.$i.'.score') is-invalid @enderror"
                                                        id="option_{{ $i }}_score"
                                                        name="options[{{ $i }}][score]" required>
                                                    <option value="">Score...</option>
                                                    <option value="0" {{ old('options.'.$i.'.score') == '0' ? 'selected' : '' }}>0 - Poor Response</option>
                                                    <option value="1" {{ old('options.'.$i.'.score') == '1' ? 'selected' : '' }}>1 - Below Average</option>
                                                    <option value="2" {{ old('options.'.$i.'.score') == '2' ? 'selected' : '' }}>2 - Average</option>
                                                    <option value="3" {{ old('options.'.$i.'.score') == '3' ? 'selected' : '' }}>3 - Good Response</option>
                                                    <option value="4" {{ old('options.'.$i.'.score') == '4' ? 'selected' : '' }}>4 - Excellent Response</option>
                                                </select>
                                                @error('options.'.$i.'.score')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save mr-1"></i> Create Question
                    </button>
                    <a href="{{ route('admin.questions.sjt.index', ['version' => $selectedVersion->id]) }}" class="btn btn-secondary">
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
                    SJT Question Guidelines
                </h3>
            </div>
            <div class="card-body">
                <h6>What is SJT?</h6>
                <p class="text-sm">
                    Situational Judgment Test measures how candidates respond to workplace scenarios to assess specific competencies.
                </p>

                <h6>Question Structure:</h6>
                <ul class="text-sm">
                    <li><strong>Situation:</strong> Brief workplace scenario</li>
                    <li><strong>Options:</strong> 5 possible responses (A-E)</li>
                    <li><strong>Scoring:</strong> 0-4 based on competency alignment</li>
                </ul>

                <h6>Scoring Guide:</h6>
                <ul class="text-sm">
                    <li><span class="badge badge-danger">0</span> Counterproductive response</li>
                    <li><span class="badge badge-warning">1</span> Poor response</li>
                    <li><span class="badge badge-info">2</span> Acceptable response</li>
                    <li><span class="badge badge-primary">3</span> Good response</li>
                    <li><span class="badge badge-success">4</span> Excellent response</li>
                </ul>

                <h6>Target Competencies:</h6>
                <div class="text-sm">
                    @foreach($competencies as $competency)
                        <span class="badge badge-outline-primary mr-1 mb-1">{{ $competency->competency_code }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Character counting
    $('#question_text').on('input', function() {
        var count = $(this).val().length;
        $('#question_char_count').text(count);

        if (count > 900) {
            $('#question_char_count').addClass('text-warning');
        } else {
            $('#question_char_count').removeClass('text-warning');
        }
    });

    // Form validation
    $('#sjtForm').on('submit', function(e) {
        var isValid = true;

        // Check if all options have text and score
        for(var i = 0; i < 5; i++) {
            var optionText = $(`#option_${i}_text`).val().trim();
            var optionScore = $(`#option_${i}_score`).val();

            if (!optionText || !optionScore) {
                isValid = false;
                if (!optionText) $(`#option_${i}_text`).addClass('is-invalid');
                if (!optionScore) $(`#option_${i}_score`).addClass('is-invalid');
            }
        }

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all option texts and scores.');
            return false;
        }

        // Disable submit button to prevent double submission
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Creating...');
    });

    // Remove validation classes on input
    $('textarea, select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endpush

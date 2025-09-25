@extends('admin.layouts.app')

@section('title', 'Edit SJT Question')
@section('page-title', 'Edit SJT Question #' . $sjtQuestion->number)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.sjt.index') }}">SJT Questions</a></li>
    <li class="breadcrumb-item active">Edit Question</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i>
                    Edit SJT Question #{{ $sjtQuestion->number }}
                </h3>
            </div>
            <form action="{{ route('admin.questions.sjt.update', $sjtQuestion) }}" method="POST" id="sjtEditForm">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Version Info -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        Version: <strong>{{ $sjtQuestion->questionVersion->display_name }}</strong>
                    </div>

                    <!-- Question Number -->
                    <div class="form-group">
                        <label for="number">Question Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('number') is-invalid @enderror"
                               id="number" name="number" value="{{ old('number', $sjtQuestion->number) }}"
                               min="1" max="50" required>
                        @error('number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Question Text -->
                    <div class="form-group">
                        <label for="question_text">Situation Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('question_text') is-invalid @enderror"
                                  id="question_text" name="question_text" rows="5" required
                                  maxlength="1000">{{ old('question_text', $sjtQuestion->question_text) }}</textarea>
                        @error('question_text')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <span id="question_char_count">{{ strlen($sjtQuestion->question_text) }}</span>/1000 characters
                        </small>
                    </div>

                    <!-- Competency -->
                    <div class="form-group">
                        <label for="competency_code">Target Competency <span class="text-danger">*</span></label>
                        <select class="form-control @error('competency_code') is-invalid @enderror"
                                id="competency_code" name="competency_code" required>
                            @foreach($competencies as $competency)
                                <option value="{{ $competency->competency_code }}"
                                        {{ old('competency_code', $sjtQuestion->competency_code) == $competency->competency_code ? 'selected' : '' }}>
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
                        @php $optionLetters = ['A', 'B', 'C', 'D', 'E']; @endphp
                        @for($i = 0; $i < 5; $i++)
                            @php $option = $sjtQuestion->options->where('option_letter', strtolower($optionLetters[$i]))->first(); @endphp
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
                                                          rows="3" required maxlength="500">{{ old('options.'.$i.'.option_text', $option->option_text ?? '') }}</textarea>
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
                                                    <option value="0" {{ old('options.'.$i.'.score', $option->score ?? '') == '0' ? 'selected' : '' }}>0 - Poor Response</option>
                                                    <option value="1" {{ old('options.'.$i.'.score', $option->score ?? '') == '1' ? 'selected' : '' }}>1 - Below Average</option>
                                                    <option value="2" {{ old('options.'.$i.'.score', $option->score ?? '') == '2' ? 'selected' : '' }}>2 - Average</option>
                                                    <option value="3" {{ old('options.'.$i.'.score', $option->score ?? '') == '3' ? 'selected' : '' }}>3 - Good Response</option>
                                                    <option value="4" {{ old('options.'.$i.'.score', $option->score ?? '') == '4' ? 'selected' : '' }}>4 - Excellent Response</option>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update Question
                    </button>
                    <a href="{{ route('admin.questions.sjt.index', ['version' => $sjtQuestion->version_id]) }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                    <a href="{{ route('admin.questions.sjt.show', $sjtQuestion) }}" class="btn btn-info">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Question Info
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>{{ $sjtQuestion->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Version:</strong></td>
                        <td>{{ $sjtQuestion->questionVersion->display_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Usage Count:</strong></td>
                        <td>
                            <span class="badge badge-{{ $sjtQuestion->usage_count > 0 ? 'warning' : 'success' }}">
                                {{ $sjtQuestion->usage_count }} responses
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $sjtQuestion->created_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated:</strong></td>
                        <td>{{ $sjtQuestion->updated_at->format('M d, Y') }}</td>
                    </tr>
                </table>

                @if($sjtQuestion->usage_count > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <strong>Warning:</strong> This question has been used in {{ $sjtQuestion->usage_count }} test(s). Changes may affect existing results.
                    </div>
                @endif
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

    // Remove validation classes on input
    $('textarea, select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Edit Competency')
@section('page-title', 'Edit Competency Description')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.competencies.index') }}">Competencies</a></li>
    <li class="breadcrumb-item active">Edit {{ $competency->competency_code }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i>
                    Edit Competency: {{ $competency->competency_code }}
                </h3>
            </div>
            <form action="{{ route('admin.competencies.update', $competency) }}" method="POST" id="competencyForm">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Competency Code (Read-only) -->
                    <div class="form-group">
                        <label for="competency_code">Competency Code</label>
                        <input type="text" class="form-control" id="competency_code"
                               value="{{ $competency->competency_code }}" readonly>
                        <small class="form-text text-muted">Competency code cannot be changed</small>
                    </div>

                    <!-- Competency Name -->
                    <div class="form-group">
                        <label for="competency_name">Competency Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('competency_name') is-invalid @enderror"
                               id="competency_name" name="competency_name"
                               value="{{ old('competency_name', $competency->competency_name) }}"
                               maxlength="100" required>
                        @error('competency_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <span id="name_char_count">{{ strlen($competency->competency_name) }}</span>/100 characters
                        </small>
                    </div>

                    <!-- Strength Description -->
                    <div class="form-group">
                        <label for="strength_description">Strength Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('strength_description') is-invalid @enderror"
                                  id="strength_description" name="strength_description" rows="4"
                                  maxlength="1000" required>{{ old('strength_description', $competency->strength_description) }}</textarea>
                        @error('strength_description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Description when this competency is a strength
                            <span class="float-right">
                                <span id="strength_char_count">{{ strlen($competency->strength_description) }}</span>/1000 characters
                            </span>
                        </small>
                    </div>

                    <!-- Weakness Description -->
                    <div class="form-group">
                        <label for="weakness_description">Weakness Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('weakness_description') is-invalid @enderror"
                                  id="weakness_description" name="weakness_description" rows="4"
                                  maxlength="1000" required>{{ old('weakness_description', $competency->weakness_description) }}</textarea>
                        @error('weakness_description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Description when this competency needs development
                            <span class="float-right">
                                <span id="weakness_char_count">{{ strlen($competency->weakness_description) }}</span>/1000 characters
                            </span>
                        </small>
                    </div>

                    <!-- Improvement Activity -->
                    <div class="form-group">
                        <label for="improvement_activity">Improvement Activities <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('improvement_activity') is-invalid @enderror"
                                  id="improvement_activity" name="improvement_activity" rows="4"
                                  maxlength="1000" required>{{ old('improvement_activity', $competency->improvement_activity) }}</textarea>
                        @error('improvement_activity')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Recommended activities for competency development
                            <span class="float-right">
                                <span id="activity_char_count">{{ strlen($competency->improvement_activity) }}</span>/1000 characters
                            </span>
                        </small>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update Competency
                    </button>
                    <a href="{{ route('admin.competencies.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                    <a href="{{ route('admin.competencies.show', $competency) }}" class="btn btn-info">
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
                    Usage Information
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Code:</strong></td>
                        <td>{{ $competency->competency_code }}</td>
                    </tr>
                    <tr>
                        <td><strong>Questions:</strong></td>
                        <td>
                            <span class="badge badge-info">{{ $competency->questions_count }} questions</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($competency->isUsedInActiveQuestions())
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Last Updated:</strong></td>
                        <td>{{ $competency->updated_at->format('M d, Y') }}</td>
                    </tr>
                </table>

                @if($competency->isUsedInActiveQuestions())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        This competency is used in active question versions. Changes will affect test results.
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Writing Guidelines
                </h3>
            </div>
            <div class="card-body">
                <h6>Strength Description:</h6>
                <ul class="text-sm">
                    <li>Focus on positive behaviors and outcomes</li>
                    <li>Use clear, actionable language</li>
                    <li>Describe observable characteristics</li>
                </ul>

                <h6>Weakness Description:</h6>
                <ul class="text-sm">
                    <li>Describe areas for improvement</li>
                    <li>Avoid overly negative language</li>
                    <li>Focus on development opportunities</li>
                </ul>

                <h6>Improvement Activities:</h6>
                <ul class="text-sm">
                    <li>Provide specific, actionable steps</li>
                    <li>Include various learning methods</li>
                    <li>Make recommendations practical</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Character counting for all text fields
    function updateCharCount(fieldId, countId, maxLength) {
        const field = $('#' + fieldId);
        const counter = $('#' + countId);

        field.on('input', function() {
            const count = $(this).val().length;
            counter.text(count);

            if (count > maxLength * 0.9) {
                counter.addClass('text-warning');
            } else {
                counter.removeClass('text-warning');
            }
        });
    }

    updateCharCount('competency_name', 'name_char_count', 100);
    updateCharCount('strength_description', 'strength_char_count', 1000);
    updateCharCount('weakness_description', 'weakness_char_count', 1000);
    updateCharCount('improvement_activity', 'activity_char_count', 1000);
});
</script>
@endpush

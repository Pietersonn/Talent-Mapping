@extends('admin.layouts.app')

@section('title', 'Edit ST-30 Question')
@section('page-title', 'Edit ST-30 Question #' . $st30Question->number)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.st30.index') }}">ST-30 Questions</a></li>
    <li class="breadcrumb-item active">Edit Question</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i>
                    Edit ST-30 Question #{{ $st30Question->number }}
                </h3>
            </div>
            <form action="{{ route('admin.questions.st30.update', $st30Question) }}" method="POST" id="st30EditForm">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Version Info -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        Version: <strong>{{ $st30Question->questionVersion->display_name }}</strong>
                    </div>

                    <!-- Question Number -->
                    <div class="form-group">
                        <label for="number">Question Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('number') is-invalid @enderror"
                               id="number" name="number" value="{{ old('number', $st30Question->number) }}"
                               min="1" max="30" required>
                        @error('number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Statement -->
                    <div class="form-group">
                        <label for="statement">Statement <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('statement') is-invalid @enderror"
                                  id="statement" name="statement" rows="4" required
                                  maxlength="500">{{ old('statement', $st30Question->statement) }}</textarea>
                        @error('statement')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <span id="statement_char_count">{{ strlen($st30Question->statement) }}</span>/500 characters
                        </small>
                    </div>

                    <!-- Typology -->
                    <div class="form-group">
                        <label for="typology_code">Typology <span class="text-danger">*</span></label>
                        <select class="form-control @error('typology_code') is-invalid @enderror"
                                id="typology_code" name="typology_code" required>
                            @foreach($typologies as $typology)
                                <option value="{{ $typology->typology_code }}"
                                        {{ old('typology_code', $st30Question->typology_code) == $typology->typology_code ? 'selected' : '' }}>
                                    {{ $typology->typology_code }} - {{ $typology->typology_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('typology_code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Typology Description -->
                    <div id="typology_description" class="alert alert-light">
                        <strong>Current Typology:</strong>
                        <div>{{ $st30Question->typologyDescription->strength_description ?? 'No description available' }}</div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update Question
                    </button>
                    <a href="{{ route('admin.questions.st30.index', ['version' => $st30Question->version_id]) }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                    <a href="{{ route('admin.questions.st30.show', $st30Question) }}" class="btn btn-info">
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
                        <td>{{ $st30Question->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Version:</strong></td>
                        <td>{{ $st30Question->questionVersion->display_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Usage Count:</strong></td>
                        <td>
                            <span class="badge badge-{{ $st30Question->usage_count > 0 ? 'warning' : 'success' }}">
                                {{ $st30Question->usage_count }} responses
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $st30Question->created_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated:</strong></td>
                        <td>{{ $st30Question->updated_at->format('M d, Y') }}</td>
                    </tr>
                </table>

                @if($st30Question->usage_count > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <strong>Warning:</strong> This question has been used in {{ $st30Question->usage_count }} test(s). Changes may affect existing results.
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

    // Update typology description when changed
    $('#typology_code').on('change', function() {
        var selectedCode = $(this).val();
        if (selectedCode && typologyDescriptions[selectedCode]) {
            $('#typology_description div').text(typologyDescriptions[selectedCode]);
        }
    });
});
</script>
@endpush

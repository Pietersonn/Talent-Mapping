@extends('admin.layouts.app')

@section('title', 'ST-30 Questions')
@section('page-title', 'ST-30 Questions Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item active">ST-30 Questions</li>
@endsection

@section('content')
<div class="row">

    <!-- Version Selector & Actions -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-brain mr-1"></i>
                    ST-30 (Strength Typology) Questions
                </h3>
                <div class="card-tools">
                    @if(Auth::user()->role === 'admin' && $selectedVersion)
                        <a href="{{ route('admin.questions.st30.create', ['version' => $selectedVersion->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Question
                        </a>
                    @endif
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#importModal">
                        <i class="fas fa-upload"></i> Import
                    </button>
                    @if($questions->count() > 0)
                        <button type="button" class="btn btn-success btn-sm" onclick="exportQuestions()">
                            <i class="fas fa-download"></i> Export
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body">

                <!-- Version Selection -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="version_select">Select Version:</label>
                        <select id="version_select" class="form-control" onchange="changeVersion()">
                            <option value="">Choose Version...</option>
                            @foreach($versions as $version)
                                <option value="{{ $version->id }}"
                                        {{ $selectedVersion && $selectedVersion->id === $version->id ? 'selected' : '' }}>
                                    {{ $version->display_name }}
                                    @if($version->is_active) (ACTIVE) @endif
                                    - {{ $version->st30Questions()->count() }}/30 Questions
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8">
                        @if($selectedVersion)
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-{{ $selectedVersion->is_active ? 'success' : 'secondary' }}">
                                    <i class="fas fa-brain"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $selectedVersion->display_name }}</span>
                                    <span class="info-box-number">
                                        {{ $questions->count() }}/30 Questions
                                        @if($selectedVersion->is_active)
                                            <small class="badge badge-success ml-1">ACTIVE</small>
                                        @endif
                                    </span>
                                    <div class="progress">
                                        <div class="progress-bar bg-{{ $selectedVersion->is_active ? 'success' : 'info' }}"
                                             style="width: {{ ($questions->count()/30)*100 }}%"></div>
                                    </div>
                                    <span class="progress-description">
                                        {{ round(($questions->count()/30)*100, 1) }}% Complete
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@if($selectedVersion)
<!-- Typology Distribution -->

<!--
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Typology Distribution
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($typologies as $typology)
                        <div class="col-md-2 col-sm-3 col-6">
                            <div class="description-block">
                                <span class="description-percentage text-primary">
                                    {{ $typologyStats[$typology->typology_code] ?? 0 }}
                                </span>
                                <h5 class="description-header">{{ $typology->typology_code }}</h5>
                                <span class="description-text">{{ $typology->typology_name }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
-->

<!-- Questions List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-1"></i>
                    Questions List ({{ $questions->count() }})
                </h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <input type="text" id="searchQuestions" class="form-control float-right" placeholder="Search questions...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                @if($questions->count() > 0)
                    <table class="table table-hover text-nowrap" id="questionsTable">
                        <thead>
                            <tr>
                                <th width="80">No.</th>
                                <th >Statement</th>
                                <th width="120">Typology</th>
                                <th width="100">Status</th>
                                <th width="80">Used</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $question)
                            <tr data-question-id="{{ $question->id }}">
                                <td>
                                    <span >{{ $question->number }}</span>
                                </td>
                                <td>
                                    <div class="question-item">
                                        <span>{{ $question->statement_preview }}</span>
                                        @if(strlen($question->statement) > 100)
                                            <button type="button" class="btn btn-link btn-xs p-0"
                                                    onclick="showFullStatement('{{ $question->id }}')">
                                                Show full...
                                            </button>
                                        @endif
                                        <div id="full-statement-{{ $question->id }}" class="mt-2" style="display: none;">
                                            <div class="alert alert-light">
                                                {{ $question->statement }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="typology-tag">
                                        {{ $question->typology_code }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $question->typology_name }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $question->is_active ? 'success' : 'secondary' }}">
                                        {{ $question->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $question->usage_count > 0 ? 'info' : 'light' }}">
                                        {{ $question->usage_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.st30.show', $question) }}"
                                           class="btn btn-info btn-xs" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('admin.st30.edit', $question) }}"
                                               class="btn btn-warning btn-xs" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($question->usage_count === 0)
                                                <form action="{{ route('admin.st30.destroy', $question) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs btn-delete" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-secondary btn-xs" title="Cannot delete - has responses" disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Questions Found</h5>
                        <p class="text-muted">
                            @if($selectedVersion)
                                Start by adding ST-30 questions to this version.
                            @else
                                Please select a version to view questions.
                            @endif
                        </p>
                        @if(Auth::user()->role === 'admin' && $selectedVersion)
                            <a href="{{ route('admin.st30.create', ['version' => $selectedVersion->id]) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Question
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@else
    <!-- No Version Selected -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-brain fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No ST-30 Version Available</h5>
                    <p class="text-muted">Create a ST-30 version first to manage questions.</p>
                    <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create ST-30 Version
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import ST-30 Questions</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.st30.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if($selectedVersion)
                        <input type="hidden" name="version_id" value="{{ $selectedVersion->id }}">
                        <div class="form-group">
                            <label>Target Version:</label>
                            <input type="text" class="form-control" value="{{ $selectedVersion->display_name }}" readonly>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="import_file">Select File:</label>
                        <input type="file" name="import_file" id="import_file" class="form-control"
                               accept=".csv,.xlsx" required>
                        <small class="form-text text-muted">Supported formats: CSV, Excel</small>
                    </div>

                    <div class="alert alert-info">
                        <strong>File Format:</strong>
                        <ul class="mb-0">
                            <li>Column 1: number (1-30)</li>
                            <li>Column 2: statement</li>
                            <li>Column 3: typology_code</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import Questions</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Version change handler
function changeVersion() {
    var versionId = document.getElementById('version_select').value;
    if (versionId) {
        window.location.href = '{{ route("admin.st30.index") }}?version=' + versionId;
    } else {
        window.location.href = '{{ route("admin.st30.index") }}';
    }
}

// Export questions
function exportQuestions() {
    var versionId = '{{ $selectedVersion ? $selectedVersion->id : "" }}';
    if (versionId) {
        window.location.href = '{{ route("admin.st30.export") }}?version=' + versionId;
    } else {
        alert('Please select a version to export.');
    }
}

// Show full statement
function showFullStatement(questionId) {
    var element = document.getElementById('full-statement-' + questionId);
    if (element.style.display === 'none') {
        element.style.display = 'block';
    } else {
        element.style.display = 'none';
    }
}

$(document).ready(function() {
    // Search functionality
    $('#searchQuestions').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#questionsTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Delete confirmation
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');

        if (confirm('Are you sure you want to delete this question? This action cannot be undone.')) {
            form.submit();
        }
    });

    // Sortable questions (for reordering)
    @if(Auth::user()->role === 'admin' && $selectedVersion)
    $('#questionsTable tbody').sortable({
        handle: '.badge-primary',
        update: function(event, ui) {
            var questions = [];
            $('#questionsTable tbody tr').each(function(index) {
                questions.push({
                    id: $(this).data('question-id'),
                    number: index + 1
                });
            });

            // Send AJAX request to reorder
            $.post('{{ route("admin.st30.reorder") }}', {
                _token: '{{ csrf_token() }}',
                version_id: '{{ $selectedVersion ? $selectedVersion->id : "" }}',
                questions: questions
            }).done(function(response) {
                if (response.success) {
                    // Update the question numbers in the table
                    $('#questionsTable tbody tr').each(function(index) {
                        $(this).find('.badge-primary').text(index + 1);
                    });

                    // Show success message
                    toastr.success('Questions reordered successfully!');
                }
            }).fail(function() {
                toastr.error('Failed to reorder questions. Please refresh and try again.');
                location.reload();
            });
        }
    });
    @endif
});
</script>
@endpush

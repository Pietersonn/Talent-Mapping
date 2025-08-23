    @extends('admin.layouts.app')

    @section('title', 'SJT Questions')
    @section('page-title', 'SJT Questions Management')

    @section('breadcrumbs')
        <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
        <li class="breadcrumb-item active">SJT Questions</li>
    @endsection

    @section('content')
        <div class="row">

            <!-- Version Selector & Actions -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            SJT (Situational Judgment Test) Questions
                        </h3>
                        <div class="card-tools">
                            @if (Auth::user()->role === 'admin' && $selectedVersion)
                                <a href="{{ route('admin.sjt.create', ['version' => $selectedVersion->id]) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add Question
                                </a>
                            @endif
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                data-target="#importModal">
                                <i class="fas fa-upload"></i> Import
                            </button>
                            @if ($questions->count() > 0)
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
                                    @foreach ($versions as $version)
                                        <option value="{{ $version->id }}"
                                            {{ $selectedVersion && $selectedVersion->id === $version->id ? 'selected' : '' }}>
                                            {{ $version->display_name }}
                                            @if ($version->is_active)
                                                (ACTIVE)
                                            @endif
                                            - {{ $version->sjtQuestions()->count() }}/50 Questions
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                @if ($selectedVersion)
                                    <div class="info-box bg-light">
                                        <span
                                            class="info-box-icon bg-{{ $selectedVersion->is_active ? 'success' : 'secondary' }}">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ $selectedVersion->display_name }}</span>
                                            <span class="info-box-number">
                                                {{ $questions->count() }}/50 Questions
                                                @if ($selectedVersion->is_active)
                                                    <small class="badge badge-success ml-1">ACTIVE</small>
                                                @endif
                                            </span>
                                            <div class="progress">
                                                <div class="progress-bar bg-{{ $selectedVersion->is_active ? 'success' : 'info' }}"
                                                    style="width: {{ ($questions->count() / 50) * 100 }}%"></div>
                                            </div>
                                            <span class="progress-description">
                                                {{ round(($questions->count() / 50) * 100, 1) }}% Complete
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

        @if ($selectedVersion)

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
                                    <input type="text" id="searchQuestions" class="form-control float-right"
                                        placeholder="Search questions...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            @if ($questions->count() > 0)
                                <table class="table table-hover text-nowrap" id="questionsTable">
                                    <thead>
                                        <tr>
                                            <th width="60">No.</th>
                                            <th width="25%">Situation</th>
                                            <th width="100">Competency</th>
                                            <th width="80">Options</th>
                                            <th width="60">Used</th>
                                            <th width="180">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($questions as $question)
                                            <tr data-question-id="{{ $question->id }}">
                                                <td>
                                                    <span class="badge badge-light">{{ $question->number }}</span>
                                                </td>
                                                <td>
                                                    <div class="question-item">
                                                        <span>{{ Str::limit($question->question_text, 80) }}</span>
                                                        @if (strlen($question->question_text) > 80)
                                                            <button type="button" class="btn btn-link btn-xs p-0 ml-1"
                                                                onclick="showFullQuestion('{{ $question->id }}')">
                                                                <i class="fas fa-expand-alt"></i>
                                                            </button>
                                                        @endif
                                                        <div id="full-question-{{ $question->id }}" class="mt-2"
                                                            style="display: none;">
                                                            <div class="alert alert-light small">
                                                                {{ $question->question_text }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-secondary">{{ $question->competency }}</span>
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $question->competencyDescription->competency_name ?? 'Unknown' }}</small>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $question->options->count() === 5 ? 'success' : 'warning' }}">
                                                        {{ $question->options->count() }}/5
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $question->usage_count > 0 ? 'info' : 'light' }}">
                                                        {{ $question->usage_count }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('admin.sjt.show', $question) }}"
                                                            class="btn btn-info btn-sm" title="View Details">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                        @if (Auth::user()->role === 'admin')
                                                            <a href="{{ route('admin.sjt.edit', $question) }}"
                                                                class="btn btn-warning btn-sm" title="Edit Question">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                            @if ($question->usage_count === 0)
                                                                <button type="button" class="btn btn-danger btn-sm"
                                                                    onclick="confirmDelete('{{ $question->number }}', '{{ route('admin.sjt.destroy', $question) }}')"
                                                                    title="Delete Question">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            @else
                                                                <button class="btn btn-secondary btn-sm"
                                                                    title="Cannot delete - has responses" disabled>
                                                                    <i class="fas fa-lock"></i> Used
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
                                        @if ($selectedVersion)
                                            Start by adding SJT questions to this version.
                                        @else
                                            Please select a version to view questions.
                                        @endif
                                    </p>
                                    @if (Auth::user()->role === 'admin' && $selectedVersion)
                                        <a href="{{ route('admin.sjt.create', ['version' => $selectedVersion->id]) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Add First Question
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Competency Distribution -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Competency Distribution
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($competencies as $competency)
                                    <div class="col-md-2 col-sm-4 col-6">
                                        <div class="description-block">
                                            <span class="description-percentage text-primary">
                                                {{ $competencyStats[$competency->competency_code] ?? 0 }}
                                            </span>
                                            <h5 class="description-header">{{ $competency->competency_code }}</h5>
                                            <span
                                                class="description-text">{{ Str::limit($competency->competency_name, 20) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No SJT Version Available</h5>
                            <p class="text-muted">Create a SJT version first to manage questions.</p>
                            <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create SJT Version
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
                        <h5 class="modal-title">Import SJT Questions</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.sjt.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            @if ($selectedVersion)
                                <input type="hidden" name="version_id" value="{{ $selectedVersion->id }}">
                                <div class="form-group">
                                    <label>Target Version:</label>
                                    <input type="text" class="form-control"
                                        value="{{ $selectedVersion->display_name }}" readonly>
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
                                    <li>Column 1: number (1-50)</li>
                                    <li>Column 2: question_text</li>
                                    <li>Column 3: competency_code</li>
                                    <li>Column 4-8: option_a_text, option_b_text, etc</li>
                                    <li>Column 9-13: option_a_score, option_b_score, etc</li>
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
                    window.location.href = '{{ route('admin.sjt.index') }}?version=' + versionId;
                } else {
                    window.location.href = '{{ route('admin.sjt.index') }}';
                }
            }

            // Export questions
            function exportQuestions() {
                var versionId = '{{ $selectedVersion ? $selectedVersion->id : '' }}';
                if (versionId) {
                    window.location.href = '{{ route('admin.sjt.export') }}?version=' + versionId;
                } else {
                    alert('Please select a version to export.');
                }
            }

            // Show full question
            function showFullQuestion(questionId) {
                var element = document.getElementById('full-question-' + questionId);
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

                    if (confirm(
                            'Are you sure you want to delete this question? This action cannot be undone.')) {
                        form.submit();
                    }
                });
            });
        </script>
    @endpush

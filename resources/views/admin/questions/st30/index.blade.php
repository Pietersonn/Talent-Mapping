@extends('admin.layouts.app')

@section('title', 'ST-30 Questions')
@section('page-title', 'ST-30 Questions Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item active">ST-30 Questions</li>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            <!-- Version Selection & Actions -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-brain mr-1"></i>
                            ST-30 Questions
                            @if($selectedVersion)
                                - {{ $selectedVersion->name }}
                            @endif
                        </h3>
                        <div class="card-tools">
                            @if(Auth::user()->role === 'admin' && $selectedVersion)
                                <a href="{{ route('admin.questions.st30.create', ['version' => $selectedVersion->id]) }}"
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add Question
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="version_select">Select Version:</label>
                                <select id="version_select" class="form-control" onchange="changeVersion()">
                                    <option value="">-- Select Version --</option>
                                    @foreach($versions as $version)
                                        <option value="{{ $version->id }}"
                                                {{ $selectedVersion && $selectedVersion->id == $version->id ? 'selected' : '' }}>
                                            {{ $version->name }}
                                            @if($version->is_active)
                                                (Active)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="searchQuestions">Search Questions:</label>
                                <input type="text" id="searchQuestions" class="form-control"
                                       placeholder="Search by statement or typology...">
                            </div>
                            <div class="col-md-4">
                                <label>&nbsp;</label><br>
                                @if($selectedVersion)
                                    <button onclick="exportQuestions()" class="btn btn-success btn-sm">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                    @if(Auth::user()->role === 'admin')
                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#importModal">
                                            <i class="fas fa-upload"></i> Import
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($selectedVersion)
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $questions->count() }}</h3>
                            <p>Total Questions</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $selectedVersion->is_active ? 'Active' : 'Inactive' }}</h3>
                            <p>Version Status</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-{{ $selectedVersion->is_active ? 'check-circle' : 'times-circle' }}"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ count($typologyStats) }}</h3>
                            <p>Typologies Covered</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tags"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ 30 - $questions->count() }}</h3>
                            <p>Questions Needed</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Questions List</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            @if($questions->count() > 0)
                                <table id="questionsTable" class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th width="60">No.</th>
                                            <th width="45%">Statement</th>
                                            <th width="15%">Typology</th>
                                            <th width="10%">Status</th>
                                            <th width="15%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($questions as $question)
                                            <tr>
                                                <td>
                                                    <span class="badge badge-info badge-lg">{{ $question->number }}</span>
                                                </td>
                                                <td>
                                                    <div class="statement-text">
                                                        {{ Str::limit($question->statement, 80) }}
                                                        @if(strlen($question->statement) > 80)
                                                            <button class="btn btn-link btn-sm p-0"
                                                                    onclick="showFullStatement({{ $question->id }})">
                                                                <i class="fas fa-expand-alt"></i>
                                                            </button>
                                                            <div id="full-statement-{{ $question->id }}"
                                                                 style="display: none;" class="mt-2 text-muted">
                                                                {{ $question->statement }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-secondary">
                                                        {{ $question->typology_code }}
                                                    </span>
                                                    @if($question->typologyDescription)
                                                        <br><small class="text-muted">
                                                            {{ Str::limit($question->typologyDescription->typology_name, 20) }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $question->questionVersion->is_active ? 'success' : 'secondary' }}">
                                                        {{ $question->questionVersion->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('admin.questions.st30.show', $question) }}"
                                                           class="btn btn-info" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if(Auth::user()->role === 'admin')
                                                            <a href="{{ route('admin.questions.st30.edit', $question) }}"
                                                               class="btn btn-warning" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button type="button"
                                                                    class="btn btn-danger btn-delete"
                                                                    data-question-number="{{ $question->number }}"
                                                                    data-delete-url="{{ route('admin.questions.st30.destroy', $question) }}"
                                                                    title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Questions Found</h5>
                                    <p class="text-muted">
                                        @if($selectedVersion)
                                            This version doesn't have any questions yet.
                                            @if(Auth::user()->role === 'admin')
                                                <br><a href="{{ route('admin.questions.st30.create', ['version' => $selectedVersion->id]) }}"
                                                       class="btn btn-primary mt-2">
                                                    <i class="fas fa-plus"></i> Add First Question
                                                </a>
                                            @endif
                                        @else
                                            Please select a version to view questions.
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Typology Distribution -->
            @if($questions->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Typology Distribution</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($typologies as $typology)
                                        @php
                                            $count = $typologyStats[$typology->typology_code] ?? 0;
                                            $percentage = $questions->count() > 0 ? ($count / 30) * 100 : 0;
                                        @endphp
                                        <div class="col-md-3 mb-2">
                                            <div class="info-box info-box-sm">
                                                <span class="info-box-icon bg-{{ $count > 0 ? 'success' : 'secondary' }}">
                                                    {{ $typology->typology_code }}
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ $typology->typology_name }}</span>
                                                    <span class="info-box-number">
                                                        {{ $count }}/1
                                                        <small>({{ number_format($percentage, 1) }}%)</small>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Version change handler
        function changeVersion() {
            var versionId = document.getElementById('version_select').value;
            if (versionId) {
                window.location.href = '{{ route('admin.questions.st30.index') }}?version=' + versionId;
            } else {
                window.location.href = '{{ route('admin.questions.st30.index') }}';
            }
        }

        // Export questions
        function exportQuestions() {
            var versionId = '{{ $selectedVersion ? $selectedVersion->id : '' }}';
            if (versionId) {
                window.location.href = '{{ route('admin.questions.st30.export') }}?version=' + versionId;
            } else {
                showErrorToast('Please select a version to export.');
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

            // Delete confirmation with SweetAlert2
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                var questionNumber = $(this).data('question-number');
                var deleteUrl = $(this).data('delete-url');

                confirmDelete(
                    'Delete ST-30 Question?',
                    `Are you sure you want to delete Question #${questionNumber}? This action cannot be undone and may affect assessment results.`,
                    deleteUrl
                );
            });
        });
    </script>
@endpush

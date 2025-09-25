@extends('admin.layouts.app')

@section('title', 'SJT Questions')
@section('page-title', 'SJT Questions Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item active">SJT Questions</li>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            <!-- Version Selection & Actions -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clipboard-check mr-1"></i>
                            SJT Questions
                            @if($selectedVersion)
                                - {{ $selectedVersion->name }}
                            @endif
                        </h3>
                        <div class="card-tools">
                            @if(Auth::user()->role === 'admin' && $selectedVersion)
                                <a href="{{ route('admin.questions.sjt.create', ['version' => $selectedVersion->id]) }}"
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
                                       placeholder="Search by situation or competency...">
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
                            <h3>{{ count($competencyStats) }}</h3>
                            <p>Competencies Covered</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tags"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ 50 - $questions->count() }}</h3>
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
                                            <th width="35%">Situation</th>
                                            <th width="15%">Competency</th>
                                            <th width="10%">Options</th>
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
                                                    <div class="question-text">
                                                        {{ Str::limit($question->question_text, 80) }}
                                                        @if(strlen($question->question_text) > 80)
                                                            <button class="btn btn-link btn-sm p-0"
                                                                    onclick="showFullQuestion({{ $question->id }})">
                                                                <i class="fas fa-expand-alt"></i>
                                                            </button>
                                                            <div id="full-question-{{ $question->id }}"
                                                                 style="display: none;" class="mt-2 text-muted">
                                                                {{ $question->question_text }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-secondary">
                                                        {{ $question->competency }}
                                                    </span>
                                                    @if($question->competencyDescription)
                                                        <br><small class="text-muted">
                                                            {{ Str::limit($question->competencyDescription->competency_name, 20) }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $question->options->count() == 5 ? 'success' : 'warning' }}">
                                                        {{ $question->options->count() }}/5
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $question->questionVersion->is_active ? 'success' : 'secondary' }}">
                                                        {{ $question->questionVersion->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('admin.questions.sjt.show', $question) }}"
                                                           class="btn btn-info" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if(Auth::user()->role === 'admin')
                                                            <a href="{{ route('admin.questions.sjt.edit', $question) }}"
                                                               class="btn btn-warning" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button type="button"
                                                                    class="btn btn-danger btn-delete"
                                                                    data-question-number="{{ $question->number }}"
                                                                    data-delete-url="{{ route('admin.questions.sjt.destroy', $question) }}"
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
                                    <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Questions Found</h5>
                                    <p class="text-muted">
                                        @if($selectedVersion)
                                            This version doesn't have any SJT questions yet.
                                            @if(Auth::user()->role === 'admin')
                                                <br><a href="{{ route('admin.questions.sjt.create', ['version' => $selectedVersion->id]) }}"
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

            <!-- Competency Distribution -->
            @if($questions->count() > 0)
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
                                    @foreach($competencies as $competency)
                                        @php
                                            $count = $competencyStats[$competency->competency_code] ?? 0;
                                            $percentage = $questions->count() > 0 ? ($count / 50) * 100 : 0;
                                        @endphp
                                        <div class="col-md-2 col-sm-4 col-6">
                                            <div class="info-box info-box-sm">
                                                <span class="info-box-icon bg-{{ $count >= 5 ? 'success' : ($count > 0 ? 'warning' : 'secondary') }}">
                                                    {{ $competency->competency_code }}
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ $competency->competency_name }}</span>
                                                    <span class="info-box-number">
                                                        {{ $count }}/5
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
                window.location.href = '{{ route('admin.questions.sjt.index') }}?version=' + versionId;
            } else {
                window.location.href = '{{ route('admin.questions.sjt.index') }}';
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

            // Delete confirmation with SweetAlert2
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                var questionNumber = $(this).data('question-number');
                var deleteUrl = $(this).data('delete-url');

                confirmDelete(
                    'Delete SJT Question?',
                    `Are you sure you want to delete Question #${questionNumber}? This action cannot be undone and may affect assessment results.`,
                    deleteUrl
                );
            });
        });
    </script>
@endpush

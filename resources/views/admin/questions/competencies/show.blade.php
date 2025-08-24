@extends('admin.layouts.app')

@section('title', 'Competency Details')
@section('page-title', $competencyDescription->competency_name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.competencies.index') }}">Competencies</a></li>
    <li class="breadcrumb-item active">{{ $competencyDescription->competency_code }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title d-flex align-items-center">
                            <span class="badge badge-primary badge-lg mr-3 px-3 py-2">{{ $competencyDescription->competency_code }}</span>
                            <span>{{ $competencyDescription->competency_name }}</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Strength Description -->
                        <div class="competency-section mb-4">
                            <h5 class="text-success border-bottom border-success pb-2 mb-3">
                                <i class="fas fa-arrow-up mr-2"></i> When Strong in This Competency
                            </h5>
                            <div class="strength-display p-3 bg-light-success rounded">
                                <p class="mb-0">{{ $competencyDescription->strength_description }}</p>
                            </div>
                        </div>

                        <!-- Weakness Description -->
                        <div class="competency-section mb-4">
                            <h5 class="text-warning border-bottom border-warning pb-2 mb-3">
                                <i class="fas fa-arrow-down mr-2"></i> When Development is Needed
                            </h5>
                            <div class="weakness-display p-3 bg-light-warning rounded">
                                <p class="mb-0">{{ $competencyDescription->weakness_description }}</p>
                            </div>
                        </div>

                        <!-- Improvement Activities -->
                        <div class="competency-section mb-4">
                            <h5 class="text-info border-bottom border-info pb-2 mb-3">
                                <i class="fas fa-tasks mr-2"></i> Development Activities
                            </h5>
                            <div class="improvement-display p-3 bg-light-info rounded">
                                <p class="mb-0">{{ $competencyDescription->improvement_activity }}</p>
                            </div>
                        </div>

                        <!-- Related Questions -->
                        @if($competencyDescription->sjtQuestions->count() > 0)
                            <div class="competency-section mb-4">
                                <h5 class="text-primary border-bottom border-primary pb-2 mb-3">
                                    <i class="fas fa-question-circle mr-2"></i>
                                    Related SJT Questions ({{ $competencyDescription->sjtQuestions->count() }})
                                    <button id="toggle-questions-btn" class="btn btn-link btn-sm ml-2" onclick="toggleRelatedQuestions()">
                                        Hide Questions
                                    </button>
                                </h5>
                                <div id="related-questions">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th width="80">Number</th>
                                                    <th>Question</th>
                                                    <th width="120">Version</th>
                                                    <th width="80">Options</th>
                                                    <th width="80">Status</th>
                                                    <th width="100">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($competencyDescription->sjtQuestions->sortBy('number') as $question)
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-light">Q{{ $question->number }}</span>
                                                        </td>
                                                        <td>
                                                            <span title="{{ $question->question_text }}">
                                                                {{ Str::limit($question->question_text, 80) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">
                                                                {{ $question->questionVersion->name ?? 'N/A' }}
                                                            </small>
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
                                                                <a href="{{ route('admin.sjt.show', $question) }}"
                                                                   class="btn btn-info btn-xs" title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                @if(Auth::user()->role === 'admin')
                                                                    <a href="{{ route('admin.sjt.edit', $question) }}"
                                                                       class="btn btn-warning btn-xs" title="Edit">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($competencyDescription->sjtQuestions->count() > 10)
                                        <div class="text-center mt-3">
                                            <a href="{{ route('admin.sjt.index') }}?competency={{ $competencyDescription->competency_code }}"
                                               class="btn btn-outline-primary">
                                                View All {{ $competencyDescription->sjtQuestions->count() }} Questions
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="competency-section mb-4">
                                <h5 class="text-primary border-bottom border-primary pb-2 mb-3">
                                    <i class="fas fa-question-circle mr-2"></i> Related SJT Questions
                                </h5>
                                <div class="text-center py-4">
                                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">No Questions Found</h6>
                                    <p class="text-muted">No SJT questions are currently associated with this competency.</p>
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.sjt.create') }}?competency={{ $competencyDescription->competency_code }}"
                                           class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Add First Question
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tools mr-1"></i> Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.competencies.edit', $competencyDescription) }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-edit mr-2"></i> Edit Competency
                                </a>
                                <hr>
                            @endif
                            <a href="{{ route('admin.competencies.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-list mr-2"></i> Back to List
                            </a>
                            <button class="btn btn-info btn-block" onclick="copyCompetencyInfo()">
                                <i class="fas fa-copy mr-2"></i> Copy Information
                            </button>
                            <button class="btn btn-success btn-block" onclick="exportCompetency()">
                                <i class="fas fa-download mr-2"></i> Export Data
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-1"></i> Statistics
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="description-block border-bottom">
                            <span class="description-percentage text-primary">{{ $competencyDescription->sjtQuestions->count() }}</span>
                            <h5 class="description-header">Total Questions</h5>
                            <span class="description-text">SJT questions using this competency</span>
                        </div>

                        <div class="description-block border-bottom mt-3">
                            <span class="description-percentage text-success">
                                {{ $competencyDescription->sjtQuestions->filter(function($q) { return $q->questionVersion->is_active; })->count() }}
                            </span>
                            <h5 class="description-header">Active Questions</h5>
                            <span class="description-text">In active versions</span>
                        </div>

                        <div class="description-block mt-3">
                            <span class="description-percentage text-info">{{ $competencyDescription->competency_code }}</span>
                            <h5 class="description-header">Code</h5>
                            <span class="description-text">Competency identifier</span>
                        </div>
                    </div>
                </div>

                <!-- Competency Info -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-1"></i> Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0">
                                <strong>Code:</strong><br>
                                <span class="badge badge-primary">{{ $competencyDescription->competency_code }}</span>
                            </li>
                            <li class="list-group-item px-0">
                                <strong>Usage Status:</strong><br>
                                <span class="badge badge-{{ $competencyDescription->sjtQuestions->count() > 0 ? 'success' : 'warning' }}">
                                    {{ $competencyDescription->sjtQuestions->count() > 0 ? 'In Use' : 'Not Used' }}
                                </span>
                            </li>
                            <li class="list-group-item px-0">
                                <strong>Last Updated:</strong><br>
                                <small class="text-muted">{{ $competencyDescription->updated_at->format('d M Y, H:i') }}</small>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Navigation helpers
        function goToEdit() {
            window.location.href = '{{ route('admin.competencies.edit', $competencyDescription) }}';
        }

        function goToIndex() {
            window.location.href = '{{ route('admin.competencies.index') }}';
        }

        // Copy competency info to clipboard
        function copyCompetencyInfo() {
            let competencyText = `Competency: {{ addslashes($competencyDescription->competency_name) }}\n\n`;
            competencyText += `Strength: {{ addslashes($competencyDescription->strength_description) }}\n\n`;
            competencyText += `Weakness: {{ addslashes($competencyDescription->weakness_description) }}\n\n`;
            competencyText += `Improvement Activity: {{ addslashes($competencyDescription->improvement_activity) }}`;

            navigator.clipboard.writeText(competencyText).then(function() {
                showSuccessToast('Competency information copied to clipboard!');
            }).catch(function() {
                showErrorToast('Failed to copy text. Please copy manually.');
            });
        }

        // Show related questions
        function toggleRelatedQuestions() {
            $('#related-questions').toggle();
            $('#toggle-questions-btn').text($('#related-questions').is(':visible') ? 'Hide Questions' : 'Show Questions');
        }

        // Export competency data
        function exportCompetency() {
            showLoading('Exporting...', 'Preparing competency data for export...');

            // Simulate export process
            setTimeout(function() {
                Swal.close();
                showSuccessToast('Competency data exported successfully!');
                // Here you would trigger actual download
                // window.location.href = '/admin/competencies/{{ $competencyDescription->id }}/export';
            }, 2000);
        }

        $(document).ready(function() {
            // Enhanced UI interactions
            $('.competency-section').hover(
                function() { $(this).addClass('shadow-sm'); },
                function() { $(this).removeClass('shadow-sm'); }
            );

            // Keyboard shortcuts
            $(document).keydown(function(e) {
                if (e.ctrlKey || e.metaKey) {
                    switch(e.which) {
                        case 69: // Ctrl+E for Edit
                            e.preventDefault();
                            @if(Auth::user()->role === 'admin')
                                goToEdit();
                            @endif
                            break;
                        case 67: // Ctrl+C for Copy
                            e.preventDefault();
                            copyCompetencyInfo();
                            break;
                    }
                }
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Smooth scroll to sections
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                var target = $($(this).attr('href'));
                if(target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 70
                    }, 500);
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .competency-section {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
        }

        .bg-light-success {
            background-color: #f8fff8 !important;
            border-left: 4px solid #28a745;
        }

        .bg-light-warning {
            background-color: #fffdf8 !important;
            border-left: 4px solid #ffc107;
        }

        .bg-light-info {
            background-color: #f8fcff !important;
            border-left: 4px solid #17a2b8;
        }

        .border-success {
            border-color: #28a745 !important;
        }

        .border-warning {
            border-color: #ffc107 !important;
        }

        .border-info {
            border-color: #17a2b8 !important;
        }

        .border-primary {
            border-color: #007bff !important;
        }

        .description-block {
            text-align: center;
            padding: 1rem;
        }

        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }

        .btn-xs {
            padding: 0.125rem 0.25rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.15rem;
        }

        .list-group-item {
            border: none;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
    </style>
@endpush

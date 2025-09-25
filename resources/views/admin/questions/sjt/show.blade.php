@extends('admin.layouts.app')

@section('title', 'SJT Question Details')
@section('page-title', 'SJT Question #' . $sjtQuestion->number)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.sjt.index') }}">SJT Questions</a></li>
    <li class="breadcrumb-item active">Question #{{ $sjtQuestion->number }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title d-flex align-items-center">
                            <span class="badge badge-info badge-lg mr-3 px-3 py-2">Q{{ $sjtQuestion->number }}</span>
                            <span>SJT Question Details</span>
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-{{ $sjtQuestion->questionVersion->is_active ? 'success' : 'secondary' }} badge-lg">
                                {{ $sjtQuestion->questionVersion->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Situation Description -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-scenario mr-2"></i> Situation
                            </h5>
                            <div class="situation-display p-3 bg-light rounded">
                                <p class="mb-0 lead">{{ $sjtQuestion->question_text }}</p>
                            </div>
                            <button class="btn btn-link btn-sm mt-2" onclick="copyToClipboard('{{ addslashes($sjtQuestion->question_text) }}')">
                                <i class="fas fa-copy mr-1"></i> Copy Situation
                            </button>
                        </div>

                        <!-- Answer Options -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-list-ol mr-2"></i> Answer Options
                            </h5>
                            @if($sjtQuestion->options->count() > 0)
                                <div class="options-list">
                                    @foreach($sjtQuestion->options->sortBy('option_letter') as $option)
                                        <div class="card option-card mb-2">
                                            <div class="card-body py-2">
                                                <div class="row align-items-center">
                                                    <div class="col-md-1">
                                                        <span class="badge badge-primary badge-lg">{{ $option->option_letter }}</span>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <p class="mb-0">{{ $option->option_text }}</p>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <span class="badge badge-{{ $option->score >= 3 ? 'success' : ($option->score >= 2 ? 'warning' : 'danger') }} float-right">
                                                            Score: {{ $option->score }}
                                                        </span>
                                                    </div>
                                                    <div class="col-md-1 text-right">
                                                        <button class="btn btn-link btn-sm" onclick="toggleOptionDetails('{{ $option->id }}')">
                                                            <i class="fas fa-info-circle"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="option-details-{{ $option->id }}" class="mt-2" style="display: none;">
                                                    <small class="text-muted">
                                                        <strong>Competency Target:</strong> {{ $option->competency_target }}<br>
                                                        <strong>Score Rationale:</strong>
                                                        @if($option->score >= 3)
                                                            Most effective response - demonstrates strong competency
                                                        @elseif($option->score >= 2)
                                                            Moderately effective response - some competency shown
                                                        @else
                                                            Less effective response - minimal competency demonstrated
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button class="btn btn-outline-primary btn-sm mt-2" onclick="copyAllOptions()">
                                    <i class="fas fa-copy mr-1"></i> Copy All Options
                                </button>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    No options defined for this question. Please add options to make this question complete.
                                </div>
                            @endif
                        </div>

                        <!-- Competency Information -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-award mr-2"></i> Competency Information
                            </h5>
                            @if($sjtQuestion->competencyDescription)
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span class="badge badge-secondary badge-lg px-3 py-2">
                                                    {{ $sjtQuestion->competency }}
                                                </span>
                                            </div>
                                            <div class="col-md-9">
                                                <h6 class="mb-2">{{ $sjtQuestion->competencyDescription->competency_name }}</h6>
                                                <p class="text-muted mb-2">
                                                    <strong>Strength:</strong> {{ $sjtQuestion->competencyDescription->strength_description }}
                                                </p>
                                                <p class="text-muted mb-0">
                                                    <strong>Development Activity:</strong> {{ $sjtQuestion->competencyDescription->improvement_activity }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Competency description not found for code: {{ $sjtQuestion->competency }}
                                </div>
                            @endif
                        </div>

                        <!-- Scoring Analysis -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-chart-line mr-2"></i> Scoring Analysis
                            </h5>
                            @if($sjtQuestion->options->count() > 0)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="card-title mb-0">Score Distribution</h6>
                                            </div>
                                            <div class="card-body">
                                                @php
                                                    $scoreDistribution = $sjtQuestion->options->groupBy('score')->map->count();
                                                @endphp
                                                @for($score = 0; $score <= 4; $score++)
                                                    <div class="progress mb-1" style="height: 20px;">
                                                        <div class="progress-bar bg-{{ $score >= 3 ? 'success' : ($score >= 2 ? 'warning' : 'danger') }}"
                                                             style="width: {{ ($scoreDistribution[$score] ?? 0) * 20 }}%">
                                                            Score {{ $score }}: {{ $scoreDistribution[$score] ?? 0 }} options
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="card-title mb-0">Question Statistics</h6>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled mb-0">
                                                    <li><strong>Total Options:</strong> {{ $sjtQuestion->options->count() }}/5</li>
                                                    <li><strong>Max Score:</strong> {{ $sjtQuestion->options->max('score') ?? 0 }}</li>
                                                    <li><strong>Min Score:</strong> {{ $sjtQuestion->options->min('score') ?? 0 }}</li>
                                                    <li><strong>Page Number:</strong> {{ $sjtQuestion->page_number }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
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
                                <a href="{{ route('admin.questions.sjt.edit', $sjtQuestion) }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-edit mr-2"></i> Edit Question
                                </a>
                                <button class="btn btn-danger btn-block"
                                        onclick="confirmDelete('{{ $sjtQuestion->number }}', '{{ route('admin.questions.sjt.destroy', $sjtQuestion) }}')">
                                    <i class="fas fa-trash mr-2"></i> Delete Question
                                </button>
                                <hr>
                            @endif
                            <a href="{{ route('admin.questions.sjt.index', ['version' => $sjtQuestion->version_id]) }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-list mr-2"></i> Back to List
                            </a>
                            <button class="btn btn-info btn-block" onclick="copyAllOptions()">
                                <i class="fas fa-copy mr-2"></i> Copy Question & Options
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
                        <div class="description-block border-right">
                            <span class="description-percentage text-info">Q{{ $sjtQuestion->number }}</span>
                            <h5 class="description-header">Question Number</h5>
                            <span class="description-text">of 50 total</span>
                        </div>

                        <div class="description-block border-right mt-3">
                            <span class="description-percentage text-{{ $sjtQuestion->options->count() == 5 ? 'success' : 'warning' }}">
                                {{ $sjtQuestion->options->count() }}/5
                            </span>
                            <h5 class="description-header">Options</h5>
                            <span class="description-text">
                                {{ $sjtQuestion->options->count() == 5 ? 'Complete' : 'Incomplete' }}
                            </span>
                        </div>

                        <div class="description-block mt-3">
                            <span class="description-percentage text-secondary">{{ $sjtQuestion->competency }}</span>
                            <h5 class="description-header">Competency</h5>
                            <span class="description-text">{{ $sjtQuestion->competencyDescription->competency_name ?? 'Unknown' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Version Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-1"></i> Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0">
                                <strong>Version:</strong><br>
                                <small class="text-muted">{{ $sjtQuestion->questionVersion->name }}</small>
                            </li>
                            <li class="list-group-item px-0">
                                <strong>Page:</strong><br>
                                <small class="text-muted">Page {{ $sjtQuestion->page_number }} of 5</small>
                            </li>
                            <li class="list-group-item px-0">
                                <strong>Created:</strong><br>
                                <small class="text-muted">{{ $sjtQuestion->created_at->format('d M Y, H:i') }}</small>
                            </li>
                            <li class="list-group-item px-0">
                                <strong>Last Updated:</strong><br>
                                <small class="text-muted">{{ $sjtQuestion->updated_at->format('d M Y, H:i') }}</small>
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
        // Delete confirmation with SweetAlert2
        function confirmDelete(questionNumber, deleteUrl) {
            confirmDelete(
                'Delete SJT Question?',
                `Are you sure you want to delete Question #${questionNumber}? This action cannot be undone and may affect assessment results.`,
                deleteUrl
            );
        }

        // Navigation helpers
        function goToEdit() {
            window.location.href = '{{ route('admin.questions.sjt.edit', $sjtQuestion) }}';
        }

        function goToIndex() {
            window.location.href = '{{ route('admin.questions.sjt.index', ['version' => $sjtQuestion->version_id]) }}';
        }

        // Copy question to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                showSuccessToast('Question text copied to clipboard!');
            }).catch(function() {
                showErrorToast('Failed to copy text. Please copy manually.');
            });
        }

        // Show option details
        function toggleOptionDetails(optionId) {
            $('#option-details-' + optionId).toggle();
        }

        // Copy all options to clipboard
        function copyAllOptions() {
            let optionsText = '{{ addslashes($sjtQuestion->question_text) }}\n\n';
            @foreach($sjtQuestion->options as $option)
                optionsText += '{{ $option->option_letter }}. {{ addslashes($option->option_text) }} (Score: {{ $option->score }})\n';
            @endforeach

            navigator.clipboard.writeText(optionsText).then(function() {
                showSuccessToast('Question with all options copied to clipboard!');
            }).catch(function() {
                showErrorToast('Failed to copy text. Please copy manually.');
            });
        }

        $(document).ready(function() {
            // Enhanced option display
            $('.option-card').hover(
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
                            copyAllOptions();
                            break;
                    }
                }
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush

@push('styles')
    <style>
        .situation-display {
            font-size: 1.1rem;
            line-height: 1.6;
            border-left: 4px solid #17a2b8;
        }

        .option-card {
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
        }

        .option-card:hover {
            border-color: #007bff;
        }

        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }

        .progress {
            background-color: #f8f9fa;
        }

        .description-block {
            text-align: center;
        }

        .list-group-item {
            border: none;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
    </style>
@endpush

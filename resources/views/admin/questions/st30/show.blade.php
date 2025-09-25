@extends('admin.layouts.app')

@section('title', 'ST-30 Question Details')
@section('page-title', 'ST-30 Question #' . $st30Question->number)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.st30.index') }}">ST-30 Questions</a></li>
    <li class="breadcrumb-item active">Question #{{ $st30Question->number }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title d-flex align-items-center">
                            <span class="badge badge-primary badge-lg mr-3 px-3 py-2">Q{{ $st30Question->number }}</span>
                            <span>ST-30 Question Details</span>
                        </h3>
                        <div class="card-tools">
                            <span
                                class="badge badge-{{ $st30Question->questionVersion->is_active ? 'success' : 'secondary' }} badge-lg">
                                {{ $st30Question->questionVersion->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Question Statement -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-quote-left mr-2"></i> Statement
                            </h5>
                            <div class="statement-display p-3 bg-light rounded">
                                <p class="mb-0 lead">{{ $st30Question->statement }}</p>
                            </div>
                            <button class="btn btn-link btn-sm mt-2"
                                onclick="copyToClipboard('{{ addslashes($st30Question->statement) }}')">
                                <i class="fas fa-copy mr-1"></i> Copy to Clipboard
                            </button>
                        </div>

                        <!-- Typology Information -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-tag mr-2"></i> Typology Information
                            </h5>
                            @if ($st30Question->typologyDescription)
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span class="badge badge-secondary badge-lg px-3 py-2">
                                                    {{ $st30Question->typology_code }}
                                                </span>
                                            </div>
                                            <div class="col-md-9">
                                                <h6 class="mb-2">{{ $st30Question->typologyDescription->typology_name }}
                                                </h6>
                                                <p class="text-muted mb-2">
                                                    <strong>Strength:</strong>
                                                    {{ $st30Question->typologyDescription->strength_description }}
                                                </p>
                                                <p class="text-muted mb-0">
                                                    <strong>Weakness:</strong>
                                                    {{ $st30Question->typologyDescription->weakness_description }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Typology description not found for code: {{ $st30Question->typology_code }}
                                </div>
                            @endif
                        </div>

                        <!-- Version Information -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-code-branch mr-2"></i> Version Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info">
                                            <i class="fas fa-tag"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Version</span>
                                            <span class="info-box-number">{{ $st30Question->questionVersion->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span
                                            class="info-box-icon bg-{{ $st30Question->questionVersion->is_active ? 'success' : 'secondary' }}">
                                            <i
                                                class="fas fa-{{ $st30Question->questionVersion->is_active ? 'check' : 'times' }}"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Status</span>
                                            <span
                                                class="info-box-number">{{ $st30Question->questionVersion->is_active ? 'Active' : 'Inactive' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="border-top pt-3 mt-4">
                            <div class="row">
                                <div class="col-md-4">
                                    @if (isset($prevQuestion))
                                        <a href="{{ route('admin.questions.st30.show', $prevQuestion) }}"
                                            class="btn btn-outline-secondary">
                                            <i class="fas fa-chevron-left mr-1"></i> Previous
                                        </a>
                                    @endif
                                </div>
                                <div class="col-md-4 text-center">
                                    <button class="btn btn-link keyboard-shortcuts" title="View keyboard shortcuts">
                                        <i class="fas fa-keyboard mr-1"></i> Shortcuts
                                    </button>
                                </div>
                                <div class="col-md-4 text-right">
                                    @if (isset($nextQuestion))
                                        <a href="{{ route('admin.questions.st30.show', $nextQuestion) }}"
                                            class="btn btn-outline-secondary">
                                            Next <i class="fas fa-chevron-right ml-1"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
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
                            @if (Auth::user()->role === 'admin')
                                <a href="{{ route('admin.questions.st30.edit', $st30Question) }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-edit mr-2"></i> Edit Question
                                </a>
                                <button class="btn btn-danger btn-block"
                                    onclick="confirmDelete('{{ $st30Question->number }}', '{{ route('admin.questions.st30.destroy', $st30Question) }}')">
                                    <i class="fas fa-trash mr-2"></i> Delete Question
                                </button>
                                <hr>
                            @endif
                            <a href="{{ route('admin.questions.st30.index', ['version' => $st30Question->version_id]) }}"
                                class="btn btn-secondary btn-block">
                                <i class="fas fa-list mr-2"></i> Back to List
                            </a>
                            <button class="btn btn-info btn-block"
                                onclick="copyToClipboard('{{ addslashes($st30Question->statement) }}')">
                                <i class="fas fa-copy mr-2"></i> Copy Statement
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
                            <span class="description-percentage text-primary">Q{{ $st30Question->number }}</span>
                            <h5 class="description-header">Question Number</h5>
                            <span class="description-text">of 30 total</span>
                        </div>

                        <div class="description-block border-right mt-3">
                            <span class="description-percentage text-success">
                                <i
                                    class="fas fa-{{ $st30Question->questionVersion->is_active ? 'check-circle' : 'times-circle' }}"></i>
                            </span>
                            <h5 class="description-header">Status</h5>
                            <span class="description-text">
                                {{ $st30Question->questionVersion->is_active ? 'Version Active' : 'Version Inactive' }}
                            </span>
                        </div>

                        <div class="description-block mt-3">
                            <span class="description-percentage text-info">{{ $st30Question->typology_code }}</span>
                            <h5 class="description-header">Typology</h5>
                            <span
                                class="description-text">{{ $st30Question->typologyDescription->typology_name ?? 'Unknown' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-1"></i> Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0">
                                <strong>Created:</strong><br>
                                <small class="text-muted">{{ $st30Question->created_at->format('d M Y, H:i') }}</small>
                            </li>
                            <li class="list-group-item px-0">
                                <strong>Last Updated:</strong><br>
                                <small class="text-muted">{{ $st30Question->updated_at->format('d M Y, H:i') }}</small>
                            </li>
                            <li class="list-group-item px-0">
                                <strong>Version:</strong><br>
                                <small class="text-muted">{{ $st30Question->questionVersion->name }}</small>
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
                'Delete ST-30 Question?',
                `Are you sure you want to delete Question #${questionNumber}? This action cannot be undone and may affect assessment results.`,
                deleteUrl
            );
        }

        // Navigation helpers
        function goToEdit() {
            window.location.href = '{{ route('admin.questions.st30.edit', $st30Question) }}';
        }

        function goToIndex() {
            window.location.href = '{{ route('admin.questions.st30.index', ['version' => $st30Question->version_id]) }}';
        }

        function goToNextQuestion() {
            @if (isset($nextQuestion))
                window.location.href = '{{ route('admin.questions.st30.show', $nextQuestion) }}';
            @else
                showInfoToast('This is the last question in this version.');
            @endif
        }

        function goToPrevQuestion() {
            @if (isset($prevQuestion))
                window.location.href = '{{ route('admin.questions.st30.show', $prevQuestion) }}';
            @else
                showInfoToast('This is the first question in this version.');
            @endif
        }

        // Copy question to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                showSuccessToast('Question statement copied to clipboard!');
            }).catch(function() {
                showErrorToast('Failed to copy text. Please copy manually.');
            });
        }

        // Quick actions
        $(document).ready(function() {
            // Keyboard shortcuts
            $(document).keydown(function(e) {
                if (e.ctrlKey || e.metaKey) {
                    switch (e.which) {
                        case 69: // Ctrl+E for Edit
                            e.preventDefault();
                            @if (Auth::user()->role === 'admin')
                                goToEdit();
                            @endif
                            break;
                        case 67: // Ctrl+C for Copy
                            e.preventDefault();
                            copyToClipboard('{{ addslashes($st30Question->statement) }}');
                            break;
                    }
                }
                // Arrow key navigation
                switch (e.which) {
                    case 37: // Left arrow
                        e.preventDefault();
                        goToPrevQuestion();
                        break;
                    case 39: // Right arrow
                        e.preventDefault();
                        goToNextQuestion();
                        break;
                }
            });

            // Show keyboard shortcuts info
            $('.keyboard-shortcuts').on('click', function() {
                Swal.fire({
                    title: 'Keyboard Shortcuts',
                    html: `
                        <div class="text-left">
                            <p><kbd>Ctrl+E</kbd> - Edit Question</p>
                            <p><kbd>Ctrl+C</kbd> - Copy Statement</p>
                            <p><kbd>←</kbd> - Previous Question</p>
                            <p><kbd>→</kbd> - Next Question</p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Got it!'
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .statement-display {
            font-size: 1.1rem;
            line-height: 1.6;
            border-left: 4px solid #007bff;
        }

        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }

        .info-box {
            min-height: 90px;
        }

        .description-block {
            text-align: center;
        }

        .keyboard-shortcuts {
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
        }

        kbd {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            padding: 0.125rem 0.25rem;
            font-size: 0.75rem;
        }
    </style>
@endpush

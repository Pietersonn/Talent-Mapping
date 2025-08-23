@extends('admin.layouts.app')

@section('title', 'ST-30 Question Details')
@section('page-title', 'ST-30 Question #' . $st30Question->number)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.st30.index') }}">ST-30 Questions</a></li>
    <li class="breadcrumb-item active">Question #{{ $st30Question->number }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

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
                            <span class="badge badge-{{ $st30Question->is_active ? 'success' : 'secondary' }} badge-lg">
                                {{ $st30Question->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Question Statement -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-quote-left mr-2"></i> Statement
                            </h5>
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <p class="mb-0 lead">{{ $st30Question->statement }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Typology Information -->
                        <div class="mb-4">
                            <h5 class="text-info border-bottom pb-2 mb-3">
                                <i class="fas fa-brain mr-2"></i> Typology Information
                            </h5>
                            <div class="card border-left-info">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Typology Code:</strong><br>
                                            <span
                                                class="badge badge-secondary badge-lg">{{ $st30Question->typology_code }}</span>
                                        </div>
                                        <div class="col-md-8">
                                            <strong>Typology Name:</strong><br>
                                            @if ($st30Question->typologyDescription)
                                                <span
                                                    class="text-muted">{{ $st30Question->typologyDescription->typology_name }}</span>
                                            @else
                                                <span class="text-muted">Typology description not found</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Typology Strengths -->
                        @if ($st30Question->typologyDescription && $st30Question->typologyDescription->strength_description)
                            <div class="mb-4">
                                <h5 class="text-success border-bottom pb-2 mb-3">
                                    <i class="fas fa-thumbs-up mr-2"></i> Strengths
                                </h5>
                                <div class="card border-left-success">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $st30Question->typologyDescription->strength_description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Areas for Development -->
                        @if ($st30Question->typologyDescription && $st30Question->typologyDescription->weakness_description)
                            <div class="mb-4">
                                <h5 class="text-warning border-bottom pb-2 mb-3">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> Areas for Development
                                </h5>
                                <div class="card border-left-warning">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $st30Question->typologyDescription->weakness_description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Version Information -->
                        <div class="mb-4">
                            <h5 class="text-success border-bottom pb-2 mb-3">
                                <i class="fas fa-code-branch mr-2"></i> Version Information
                            </h5>
                            <div class="card border-left-success">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Version:</strong><br>
                                            <span class="text-muted">{{ $st30Question->questionVersion->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Version Status:</strong><br>
                                            @if ($st30Question->questionVersion)
                                                <span
                                                    class="badge badge-{{ $st30Question->questionVersion->is_active ? 'success' : 'secondary' }}">
                                                    {{ $st30Question->questionVersion->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            @else
                                                <span class="badge badge-warning">Unknown</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Usage Statistics -->
                        <div class="mb-4">
                            <h5 class="text-warning border-bottom pb-2 mb-3">
                                <i class="fas fa-chart-line mr-2"></i> Usage Statistics
                            </h5>
                            <div class="card border-left-warning">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Times Used:</strong><br>
                                            <span class="h4 text-primary">{{ $st30Question->usage_count }}</span>
                                            <small class="text-muted d-block">In completed assessments</small>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Selection Rate:</strong><br>
                                            <span class="h4 text-success">0%</span>
                                            <small class="text-muted d-block">Participants selection rate</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="row text-muted small">
                            <div class="col-md-6">
                                <i class="fas fa-calendar-plus mr-1"></i> Created:
                                {{ $st30Question->created_at->format('M d, Y H:i') }}
                            </div>
                            <div class="col-md-6 text-right">
                                <i class="fas fa-calendar-edit mr-1"></i> Updated:
                                {{ $st30Question->updated_at->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Actions -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cogs mr-2"></i> Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('admin.st30.edit', $st30Question) }}" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit mr-2"></i> Edit Question
                        </a>

                        <button type="button" class="btn btn-danger btn-block mb-2"
                            onclick="confirmDelete('{{ $st30Question->number }}', '{{ route('admin.st30.destroy', $st30Question) }}')">
                            <i class="fas fa-trash mr-2"></i> Delete Question
                        </button>

                        <a href="{{ route('admin.st30.index') }}" class="btn btn-outline-secondary btn-block">
                            <i class="fas fa-arrow-left mr-2"></i> Back to List
                        </a>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar mr-2"></i> Quick Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="card border-left-primary h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="text-primary">
                                                <i class="fas fa-hashtag fa-2x"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Question Number
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $st30Question->number }}
                                            </div>
                                            <div class="small text-muted">Out of 30 questions</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="card border-left-info h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="text-info">
                                                <i class="fas fa-brain fa-2x"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Typology
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $st30Question->typology_code }}
                                            </div>
                                            <div class="small text-muted">
                                                {{ $st30Question->typologyDescription->typology_name ?? 'Unknown' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-navigation mr-2"></i> Navigation
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                @if ($st30Question->number > 1)
                                    @php
                                        $prevQuestion = \App\Models\ST30Question::where(
                                            'version_id',
                                            $st30Question->version_id,
                                        )
                                            ->where('number', $st30Question->number - 1)
                                            ->first();
                                    @endphp
                                    @if ($prevQuestion)
                                        <a href="{{ route('admin.st30.show', $prevQuestion) }}"
                                            class="btn btn-outline-primary btn-sm btn-block">
                                            <i class="fas fa-chevron-left mr-1"></i> Q{{ $prevQuestion->number }}
                                        </a>
                                    @endif
                                @endif
                            </div>
                            <div class="col-6">
                                @if ($st30Question->number < 30)
                                    @php
                                        $nextQuestion = \App\Models\ST30Question::where(
                                            'version_id',
                                            $st30Question->version_id,
                                        )
                                            ->where('number', $st30Question->number + 1)
                                            ->first();
                                    @endphp
                                    @if ($nextQuestion)
                                        <a href="{{ route('admin.st30.show', $nextQuestion) }}"
                                            class="btn btn-outline-primary btn-sm btn-block">
                                            Q{{ $nextQuestion->number }} <i class="fas fa-chevron-right ml-1"></i>
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                    </div>
                    <p class="text-center">Are you sure you want to delete ST-30 Question <strong
                            id="deleteQuestionNumber"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle mr-2"></i>
                        This action cannot be undone and may affect assessment results.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(questionNumber, deleteUrl) {
            document.getElementById('deleteQuestionNumber').textContent = '#' + questionNumber;
            document.getElementById('deleteForm').action = deleteUrl;
            $('#deleteModal').modal('show');
        }

        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
@endpush

@push('styles')
    <style>
        .border-left-primary {
            border-left: 0.25rem solid #007bff !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #28a745 !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid #ffc107 !important;
        }

        .border-left-info {
            border-left: 0.25rem solid #17a2b8 !important;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .text-gray-800 {
            color: #5a5c69 !important;
        }

        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }

        .border-bottom {
            border-bottom: 2px solid #e9ecef !important;
        }
    </style>
@endpush

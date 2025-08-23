@extends('admin.layouts.app')

@section('title', 'SJT Question Details')
@section('page-title', 'SJT Question #' . $sjtQuestion->number)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.sjt.index') }}">SJT Questions</a></li>
    <li class="breadcrumb-item active">Question #{{ $sjtQuestion->number }}</li>
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
                            <span class="badge badge-info badge-lg mr-3 px-3 py-2">Q{{ $sjtQuestion->number }}</span>
                            <span>SJT Question Details</span>
                        </h3>
                        <div class="card-tools">
                            <span
                                class="badge badge-{{ $sjtQuestion->questionVersion->is_active ? 'success' : 'secondary' }} badge-lg">
                                {{ $sjtQuestion->questionVersion->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Question Text -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-question-circle mr-2"></i> Question
                            </h5>
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <p class="mb-0 lead">{{ $sjtQuestion->question_text }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Competency Information -->
                        <div class="mb-4">
                            <h5 class="text-info border-bottom pb-2 mb-3">
                                <i class="fas fa-target mr-2"></i> Competency Information
                            </h5>
                            <div class="card border-left-info">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Competency Code:</strong><br>
                                            <span
                                                class="badge badge-secondary badge-lg">{{ $sjtQuestion->competency }}</span>
                                        </div>
                                        <div class="col-md-8">
                                            <strong>Competency Name:</strong><br>
                                            @if ($sjtQuestion->competencyDescription)
                                                <span
                                                    class="text-muted">{{ $sjtQuestion->competencyDescription->competency_name }}</span>
                                                <br>
                                                <small
                                                    class="text-muted">{{ Str::limit($sjtQuestion->competencyDescription->description, 100) }}</small>
                                            @else
                                                <span class="text-muted">Competency description not found</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Answer Options -->
                        <div class="mb-4">
                            <h5 class="text-success border-bottom pb-2 mb-3">
                                <i class="fas fa-list-ol mr-2"></i> Answer Options
                            </h5>
                            <div class="card border-left-success">
                                <div class="card-body">
                                    @if ($sjtQuestion->options->count() > 0)
                                        @foreach ($sjtQuestion->options->sortBy('option_letter') as $option)
                                            <div
                                                class="card mb-3 {{ $option->score >= 3 ? 'border-success' : ($option->score == 2 ? 'border-warning' : 'border-danger') }}">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="flex-grow-1">
                                                            <span
                                                                class="badge badge-dark mr-2 font-weight-bold">{{ strtoupper($option->option_letter) }}</span>
                                                            <span>{{ $option->option_text }}</span>
                                                        </div>
                                                        <div class="ml-3 text-right">
                                                            <span
                                                                class="badge badge-{{ $option->score >= 3 ? 'success' : ($option->score == 2 ? 'warning' : 'danger') }} badge-lg">
                                                                Score: {{ $option->score }}
                                                            </span>
                                                            <br>
                                                            <small
                                                                class="text-muted">{{ $option->competency_target }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            No answer options found for this question.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Version Information -->
                        <div class="mb-4">
                            <h5 class="text-warning border-bottom pb-2 mb-3">
                                <i class="fas fa-code-branch mr-2"></i> Version Information
                            </h5>
                            <div class="card border-left-warning">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Version:</strong><br>
                                            <span
                                                class="text-muted">{{ $sjtQuestion->questionVersion->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Version Status:</strong><br>
                                            @if ($sjtQuestion->questionVersion)
                                                <span
                                                    class="badge badge-{{ $sjtQuestion->questionVersion->is_active ? 'success' : 'secondary' }}">
                                                    {{ $sjtQuestion->questionVersion->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            @else
                                                <span class="badge badge-warning">Unknown</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
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
                        <a href="{{ route('admin.sjt.edit', $sjtQuestion) }}" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit mr-2"></i> Edit Question
                        </a>

                        <button type="button" class="btn btn-danger btn-block mb-2"
                            onclick="confirmDelete('{{ $sjtQuestion->number }}', '{{ route('admin.sjt.destroy', $sjtQuestion) }}')">
                            <i class="fas fa-trash mr-2"></i> Delete Question
                        </button>

                        <a href="{{ route('admin.sjt.index') }}" class="btn btn-outline-secondary btn-block">
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
                                <div class="card border-left-info h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="text-info">
                                                <i class="fas fa-hashtag fa-2x"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Question Number
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $sjtQuestion->number }}
                                            </div>
                                            <div class="small text-muted">Out of 50 questions</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="card border-left-primary h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="text-primary">
                                                <i class="fas fa-list-ol fa-2x"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Answer Options
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $sjtQuestion->options->count() }}
                                            </div>
                                            <div class="small text-muted">Available choices</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="card border-left-secondary h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="text-secondary">
                                                <i class="fas fa-target fa-2x"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                Competency
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $sjtQuestion->competency }}
                                            </div>
                                            <div class="small text-muted">
                                                {{ $sjtQuestion->competencyDescription->competency_name ?? 'Unknown' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card border-left-success h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="text-success">
                                                <i class="fas fa-chart-line fa-2x"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Usage Count
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $sjtQuestion->usage_count }}
                                            </div>
                                            <div class="small text-muted">Times answered</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Score Distribution -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie mr-2"></i> Score Distribution
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($sjtQuestion->options->count() > 0)
                            @php
                                $scoreGroups = $sjtQuestion->options->groupBy('score');
                            @endphp
                            @foreach ($scoreGroups->sortKeysDesc() as $score => $options)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Score {{ $score }}:</span>
                                    <span
                                        class="badge badge-{{ $score >= 3 ? 'success' : ($score == 2 ? 'warning' : 'danger') }}">
                                        {{ $options->count() }} option(s)
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted mb-0">No options available</p>
                        @endif
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
                                @if ($sjtQuestion->number > 1)
                                    @php
                                        $prevQuestion = \App\Models\SJTQuestion::where(
                                            'version_id',
                                            $sjtQuestion->version_id,
                                        )
                                            ->where('number', $sjtQuestion->number - 1)
                                            ->first();
                                    @endphp
                                    @if ($prevQuestion)
                                        <a href="{{ route('admin.sjt.show', $prevQuestion) }}"
                                            class="btn btn-outline-info btn-sm btn-block">
                                            <i class="fas fa-chevron-left mr-1"></i> Q{{ $prevQuestion->number }}
                                        </a>
                                    @endif
                                @endif
                            </div>
                            <div class="col-6">
                                @if ($sjtQuestion->number < 50)
                                    @php
                                        $nextQuestion = \App\Models\SJTQuestion::where(
                                            'version_id',
                                            $sjtQuestion->version_id,
                                        )
                                            ->where('number', $sjtQuestion->number + 1)
                                            ->first();
                                    @endphp
                                    @if ($nextQuestion)
                                        <a href="{{ route('admin.sjt.show', $nextQuestion) }}"
                                            class="btn btn-outline-info btn-sm btn-block">
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
                    <p class="text-center">Are you sure you want to delete SJT Question <strong
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

        .border-left-secondary {
            border-left: 0.25rem solid #6c757d !important;
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

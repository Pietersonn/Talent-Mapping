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
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
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
                        <span class="badge badge-primary badge-lg mr-3 px-3 py-2">{{ $competencyDescription->competency_code }}</span>
                        <span>{{ $competencyDescription->competency_name }}</span>
                    </h3>
                    <div class="card-tools">
                        @if($competencyDescription->sjtQuestions->count() > 0)
                            <span class="badge badge-info badge-lg">
                                {{ $competencyDescription->sjtQuestions->count() }} Questions
                            </span>
                        @else
                            <span class="badge badge-light badge-lg">No Questions</span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <!-- Strength Description -->
                    <div class="mb-4">
                        <h5 class="text-success border-bottom pb-2 mb-3">
                            <i class="fas fa-thumbs-up mr-2"></i> When This Is Your Strength
                        </h5>
                        <div class="card bg-light border-left-success">
                            <div class="card-body">
                                <p class="mb-0">{{ $competencyDescription->strength_description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Weakness Description -->
                    @if($competencyDescription->weakness_description)
                    <div class="mb-4">
                        <h5 class="text-warning border-bottom pb-2 mb-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Areas for Development
                        </h5>
                        <div class="card bg-light border-left-warning">
                            <div class="card-body">
                                <p class="mb-0">{{ $competencyDescription->weakness_description }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Improvement Activities -->
                    @if($competencyDescription->improvement_activity)
                    <div class="mb-4">
                        <h5 class="text-info border-bottom pb-2 mb-3">
                            <i class="fas fa-tasks mr-2"></i> Recommended Development Activities
                        </h5>
                        <div class="card bg-light border-left-info">
                            <div class="card-body">
                                <p class="mb-0">{{ $competencyDescription->improvement_activity }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Related Questions -->
                    @if($competencyDescription->sjtQuestions->count() > 0)
                    <div class="mb-4">
                        <h5 class="text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-question-circle mr-2"></i> Related SJT Questions ({{ $competencyDescription->sjtQuestions->count() }})
                        </h5>
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th width="80">Number</th>
                                            <th>Question</th>
                                            <th width="120">Version</th>
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
                                                <span>{{ Str::limit($question->question_text, 80) }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $question->questionVersion->name ?? 'N/A' }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $question->is_active ? 'success' : 'secondary' }} badge-sm">
                                                    {{ $question->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.sjt.show', $question) }}"
                                                   class="btn btn-info btn-xs" title="View Question">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
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
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.competencies.edit', $competencyDescription) }}" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit mr-2"></i> Edit Descriptions
                        </a>
                    @endif

                    <a href="{{ route('admin.competencies.index') }}" class="btn btn-outline-secondary btn-block">
                        <i class="fas fa-arrow-left mr-2"></i> Back to List
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar mr-2"></i> Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="card border-left-primary h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="text-primary">
                                            <i class="fas fa-question-circle fa-2x"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Questions
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $competencyDescription->sjtQuestions->count() }}
                                        </div>
                                        <div class="small text-muted">SJT questions using this competency</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="card border-left-success h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="text-success">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Active Questions
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $competencyDescription->sjtQuestions->where('is_active', true)->count() }}
                                        </div>
                                        <div class="small text-muted">Currently in use</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border-left-info h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="text-info">
                                            <i class="fas fa-chart-line fa-2x"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Last Updated
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $competencyDescription->updated_at->format('M d') }}
                                        </div>
                                        <div class="small text-muted">{{ $competencyDescription->updated_at->format('Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Competency Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle mr-2"></i> Competency Information
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Code:</strong></td>
                            <td>{{ $competencyDescription->competency_code }}</td>
                        </tr>
                        <tr>
                            <td><strong>Full Name:</strong></td>
                            <td>{{ $competencyDescription->competency_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Usage Status:</strong></td>
                            <td>
                                @if($competencyDescription->sjtQuestions->where('is_active', true)->count() > 0)
                                    <span class="badge badge-success">Active</span>
                                @elseif($competencyDescription->sjtQuestions->count() > 0)
                                    <span class="badge badge-warning">Has Questions</span>
                                @else
                                    <span class="badge badge-light">No Questions</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td>{{ $competencyDescription->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Modified:</strong></td>
                            <td>{{ $competencyDescription->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>

                    @if($competencyDescription->sjtQuestions->where('is_active', true)->count() > 0)
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Active Usage:</strong> This competency is currently being used in active SJT questions.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
@endpush

@push('styles')
<style>
.border-left-primary { border-left: 0.25rem solid #007bff !important; }
.border-left-success { border-left: 0.25rem solid #28a745 !important; }
.border-left-warning { border-left: 0.25rem solid #ffc107 !important; }
.border-left-info { border-left: 0.25rem solid #17a2b8 !important; }

.text-xs { font-size: 0.75rem; }
.text-gray-800 { color: #5a5c69 !important; }

.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

.border-bottom {
    border-bottom: 2px solid #e9ecef !important;
}
</style>
@endpush

@extends('admin.layouts.app')

@section('title', 'Typology Details')
@section('page-title', 'Typology Details: ' . $typology->typology_code)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.questions.typologies.index') }}">Typologies</a></li>
<li class="breadcrumb-item active">{{ $typology->typology_code }}</li>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title d-flex align-items-center">
                        <span class="badge badge-secondary badge-lg mr-3 px-3 py-2">{{ $typology->typology_code }}</span>
                        <span>{{ $typology->typology_name }}</span>
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $typology->is_active ? 'success' : 'secondary' }} badge-lg">
                            {{ $typology->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-info-circle mr-2"></i> Description
                        </h5>
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <p class="mb-0 lead">{{ $typology->description }}</p>
                            </div>
                        </div>
                    </div>

                    @if($typology->characteristics)
                    <div class="mb-4">
                        <h5 class="text-info border-bottom pb-2 mb-3">
                            <i class="fas fa-list-alt mr-2"></i> Key Characteristics
                        </h5>
                        <div class="card border-left-info">
                            <div class="card-body">
                                <p class="mb-0">{{ $typology->characteristics }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($typology->strengths)
                    <div class="mb-4">
                        <h5 class="text-success border-bottom pb-2 mb-3">
                            <i class="fas fa-thumbs-up mr-2"></i> Strengths
                        </h5>
                        <div class="card border-left-success">
                            <div class="card-body">
                                <p class="mb-0">{{ $typology->strengths }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($typology->weaknesses)
                    <div class="mb-4">
                        <h5 class="text-warning border-bottom pb-2 mb-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Areas for Development
                        </h5>
                        <div class="card border-left-warning">
                            <div class="card-body">
                                <p class="mb-0">{{ $typology->weaknesses }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($typology->career_suggestions)
                    <div class="mb-4">
                        <h5 class="text-purple border-bottom pb-2 mb-3">
                            <i class="fas fa-briefcase mr-2"></i> Career Suggestions
                        </h5>
                        <div class="card border-left-purple">
                            <div class="card-body">
                                <p class="mb-0">{{ $typology->career_suggestions }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-light">
                    <div class="row text-muted small">
                        <div class="col-md-6">
                            <i class="fas fa-calendar-plus mr-1"></i> Created: {{ $typology->created_at->format('M d, Y H:i') }}
                        </div>
                        <div class="col-md-6 text-right">
                            <i class="fas fa-calendar-edit mr-1"></i> Updated: {{ $typology->updated_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs mr-2"></i> Actions
                    </h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.questions.typologies.edit', $typology) }}" class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-edit mr-2"></i> Edit Typology
                    </a>

                    <button type="button" class="btn btn-danger btn-block mb-2"
                            onclick="confirmDelete('{{ $typology->typology_code }}', '{{ route('admin.questions.typologies.destroy', $typology) }}')">
                        <i class="fas fa-trash mr-2"></i> Delete Typology
                    </button>

                    <a href="{{ route('admin.questions.typologies.index') }}" class="btn btn-outline-secondary btn-block">
                        <i class="fas fa-arrow-left mr-2"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar mr-2"></i> Usage Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="card border-left-info h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="text-info">
                                            <i class="fas fa-question-circle fa-2x"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            ST-30 Questions
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $typology->st30Questions()->count() }}
                                        </div>
                                        <div class="small text-muted">Questions using this typology</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="card border-left-success h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="text-success">
                                            <i class="fas fa-user-check fa-2x"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Active Questions
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $typology->st30Questions()->where('is_active', true)->count() }}
                                        </div>
                                        <div class="small text-muted">Currently active questions</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border-left-warning h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="text-warning">
                                            <i class="fas fa-percentage fa-2x"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Usage Rate
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">0%</div>
                                        <div class="small text-muted">In completed assessments</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tags mr-2"></i> Category Information
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $category = '';
                        $categoryDesc = '';
                        $categoryClass = '';
                        $firstChar = substr($typology->typology_code, 0, 1);
                        switch($firstChar) {
                            case 'H':
                                $category = 'Headman';
                                $categoryDesc = 'Leadership & influencing others';
                                $categoryClass = 'primary';
                                break;
                            case 'N':
                                $category = 'Networking';
                                $categoryDesc = 'Building relationships & collaboration';
                                $categoryClass = 'info';
                                break;
                            case 'S':
                                $category = 'Servicing';
                                $categoryDesc = 'Helping & caring for others';
                                $categoryClass = 'success';
                                break;
                            case 'G':
                                $category = 'Generating Ideas';
                                $categoryDesc = 'Creative & innovative thinking';
                                $categoryClass = 'warning';
                                break;
                            case 'T':
                                $category = 'Thinking';
                                $categoryDesc = 'Analytical & logical processing';
                                $categoryClass = 'secondary';
                                break;
                            case 'R':
                                $category = 'Reasoning';
                                $categoryDesc = 'Problem-solving & decision making';
                                $categoryClass = 'dark';
                                break;
                            case 'E':
                                $category = 'Elementary';
                                $categoryDesc = 'Basic operational tasks';
                                $categoryClass = 'danger';
                                break;
                            default:
                                $category = 'Technical';
                                $categoryDesc = 'Specialized technical skills';
                                $categoryClass = 'light';
                        }
                    @endphp

                    <div class="text-center mb-3">
                        <span class="badge badge-{{ $categoryClass }} badge-lg px-3 py-2">{{ $category }}</span>
                        <p class="text-muted mt-2 mb-0">{{ $categoryDesc }}</p>
                    </div>

                    <hr>

                    <div class="text-muted small">
                        <strong>ST-30 Framework:</strong><br>
                        This typology is part of the ST-30 (Strength Typology-30) assessment framework,
                        which maps individual strengths and potential based on 8 core behavioral categories.
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($typology->st30Questions()->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-question-circle mr-2"></i> Related ST-30 Questions
                    </h3>
                    <span class="badge badge-info">{{ $typology->st30Questions()->count() }} questions</span>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="10%">Number</th>
                                    <th width="60%">Statement</th>
                                    <th width="15%">Version</th>
                                    <th width="15%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($typology->st30Questions()->orderBy('number')->get() as $question)
                                <tr>
                                    <td>
                                        <span class="badge badge-secondary">{{ $question->number }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.questions.st30.show', $question) }}" class="text-decoration-none">
                                            {{ Str::limit($question->statement, 80) }}
                                        </a>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $question->questionVersion->name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $question->is_active ? 'success' : 'secondary' }}">
                                            {{ $question->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

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
                <p class="text-center">Are you sure you want to delete typology <strong id="deleteTypologyName"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle mr-2"></i>
                    This action cannot be undone and may affect related ST-30 questions.
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
function confirmDelete(typologyCode, deleteUrl) {
    document.getElementById('deleteTypologyName').textContent = typologyCode;
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
.border-left-primary { border-left: 0.25rem solid #007bff !important; }
.border-left-success { border-left: 0.25rem solid #28a745 !important; }
.border-left-warning { border-left: 0.25rem solid #ffc107 !important; }
.border-left-info { border-left: 0.25rem solid #17a2b8 !important; }
.border-left-purple { border-left: 0.25rem solid #6f42c1 !important; }

.text-xs { font-size: 0.75rem; }
.text-gray-800 { color: #5a5c69 !important; }
.text-purple { color: #6f42c1 !important; }

.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

.border-bottom {
    border-bottom: 2px solid #e9ecef !important;
}
</style>
@endpush

@extends('admin.layouts.app')

@section('title', 'Version Details - ' . $questionVersion->display_name)
@section('page-title', $questionVersion->display_name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item active">{{ $questionVersion->display_name }}</li>
@endsection

@section('content')
<div class="row">

    <!-- Version Info Header -->
    <div class="col-12">
        <div class="card card-{{ $questionVersion->is_active ? 'success' : 'secondary' }}">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-{{ $questionVersion->type === 'st30' ? 'brain' : 'clipboard-check' }} mr-1"></i>
                    {{ $questionVersion->display_name }}
                    @if($questionVersion->is_active)
                        <span class="badge badge-success ml-2">ACTIVE</span>
                    @else
                        <span class="badge badge-secondary ml-2">INACTIVE</span>
                    @endif
                </h3>
                <div class="card-tools">
                    @if(Auth::user()->role === 'admin')
                        @if(!$questionVersion->is_active && $statistics['total_questions'] >= ($questionVersion->type === 'st30' ? 30 : 50))
                            <form action="{{ route('admin.questions.activate', $questionVersion) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Activate Version
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.questions.edit', $questionVersion) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-info btn-sm" onclick="cloneVersion()">
                            <i class="fas fa-copy"></i> Clone
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Type:</strong></td>
                                <td>{{ $questionVersion->type_display }}</td>
                            </tr>
                            <tr>
                                <td><strong>Version:</strong></td>
                                <td>{{ $questionVersion->version }}</td>
                            </tr>
                            @if($questionVersion->description)
                            <tr>
                                <td><strong>Description:</strong></td>
                                <td>{{ $questionVersion->description }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $questionVersion->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $questionVersion->updated_at->format('d M Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-light">
                            <span class="info-box-icon">
                                <i class="fas fa-question"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Questions</span>
                                <span class="info-box-number">
                                    {{ $statistics['total_questions'] }}
                                    <small>/ {{ $questionVersion->type === 'st30' ? '30' : '50' }}</small>
                                </span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $questionVersion->type === 'st30' ? ($statistics['total_questions']/30)*100 : ($statistics['total_questions']/50)*100 }}%"></div>
                                </div>
                                <span class="progress-description">
                                    {{ round($questionVersion->type === 'st30' ? ($statistics['total_questions']/30)*100 : ($statistics['total_questions']/50)*100, 1) }}% Complete
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Statistics Row -->
<div class="row">

    <!-- Usage Statistics -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Usage Statistics
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="description-block border-right">
                            <span class="description-percentage text-success">
                                <i class="fas fa-caret-up"></i> {{ $statistics['usage_stats']['st30_responses'] }}
                            </span>
                            <h5 class="description-header">ST30</h5>
                            <span class="description-text">Responses</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="description-block">
                            <span class="description-percentage text-info">
                                <i class="fas fa-caret-up"></i> {{ $statistics['usage_stats']['sjt_responses'] }}
                            </span>
                            <h5 class="description-header">SJT</h5>
                            <span class="description-text">Responses</span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-muted text-sm">
                        <strong>Total Usage:</strong> {{ $statistics['usage_stats']['total_usage'] }} test sessions
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Distribution -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Question Distribution
                </h3>
            </div>
            <div class="card-body">
                @if($questionVersion->type === 'st30')
                    @if(isset($typologyStats) && count($typologyStats) > 0)
                        <div class="row">
                            @foreach($typologyStats as $typology => $count)
                            <div class="col-md-3 col-6">
                                <div class="description-block">
                                    <span class="description-percentage text-primary">{{ $count }}</span>
                                    <h5 class="description-header">{{ $typology }}</h5>
                                    <span class="description-text">Questions</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No questions added yet</p>
                    @endif
                @else
                    @if(isset($competencyStats) && count($competencyStats) > 0)
                        <div class="row">
                            @foreach($competencyStats as $competency => $count)
                            <div class="col-md-4 col-6">
                                <div class="description-block">
                                    <span class="description-percentage text-info">{{ $count }}</span>
                                    <h5 class="description-header">{{ \Str::limit($competency, 15) }}</h5>
                                    <span class="description-text">Questions</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No questions added yet</p>
                    @endif
                @endif
            </div>
        </div>
    </div>

</div>

<!-- Questions Preview -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-1"></i>
                    Questions Preview
                </h3>
                <div class="card-tools">
                    @if(Auth::user()->role === 'admin')
                        @if($questionVersion->type === 'st30')
                            <a href="{{ route('admin.st30.create', ['version' => $questionVersion->id]) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add ST-30 Question
                            </a>
                        @else
                            <a href="{{ route('admin.sjt.create', ['version' => $questionVersion->id]) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add SJT Question
                            </a>
                        @endif
                    @endif
                    <a href="{{ route('admin.' . $questionVersion->type . '.index') }}?version={{ $questionVersion->id }}" class="btn btn-success btn-sm">
                        <i class="fas fa-eye"></i> View All Questions
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($questionVersion->type === 'st30')
                    @if($questionVersion->st30Questions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="80">No.</th>
                                        <th>Statement</th>
                                        <th width="100">Typology</th>
                                        <th width="80">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($questionVersion->st30Questions->take(10) as $question)
                                    <tr>
                                        <td>
                                            <span class="badge badge-primary">{{ $question->number }}</span>
                                        </td>
                                        <td>
                                            {{ $question->statement_preview }}
                                        </td>
                                        <td>
                                            <span class="typology-tag">{{ $question->typology_code }}</span>
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
                        @if($questionVersion->st30Questions->count() > 10)
                            <p class="text-center mt-3">
                                <a href="{{ route('admin.st30.index') }}?version={{ $questionVersion->id }}" class="btn btn-outline-primary">
                                    View All {{ $questionVersion->st30Questions->count() }} Questions
                                </a>
                            </p>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Questions Added Yet</h5>
                            <p class="text-muted">Start by adding ST-30 questions to this version.</p>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.st30.create', ['version' => $questionVersion->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add First Question
                                </a>
                            @endif
                        </div>
                    @endif
                @else
                    @if($questionVersion->sjtQuestions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="80">No.</th>
                                        <th>Question</th>
                                        <th width="150">Competency</th>
                                        <th width="80">Page</th>
                                        <th width="80">Options</th>
                                        <th width="80">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($questionVersion->sjtQuestions->take(10) as $question)
                                    <tr>
                                        <td>
                                            <span class="badge badge-info">{{ $question->number }}</span>
                                        </td>
                                        <td>
                                            {{ $question->question_preview }}
                                        </td>
                                        <td>
                                            <span class="competency-tag">{{ \Str::limit($question->competency, 15) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $question->page_number }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $question->hasCompleteOptions() ? 'success' : 'warning' }}">
                                                {{ $question->options->count() }}/5
                                            </span>
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
                        @if($questionVersion->sjtQuestions->count() > 10)
                            <p class="text-center mt-3">
                                <a href="{{ route('admin.sjt.index') }}?version={{ $questionVersion->id }}" class="btn btn-outline-primary">
                                    View All {{ $questionVersion->sjtQuestions->count() }} Questions
                                </a>
                            </p>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Questions Added Yet</h5>
                            <p class="text-muted">Start by adding SJT questions to this version.</p>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.sjt.create', ['version' => $questionVersion->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add First Question
                                </a>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function cloneVersion() {
    if (confirm('Are you sure you want to clone this version? This will create a new version with all the same questions.')) {
        // Create a form and submit it
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.questions.clone", $questionVersion) }}';

        var token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = '{{ csrf_token() }}';
        form.appendChild(token);

        document.body.appendChild(form);
        form.submit();
    }
}

$(document).ready(function() {
    // Auto-refresh statistics every 30 seconds
    setInterval(function() {
        $.get('{{ route("admin.questions.statistics", $questionVersion) }}')
            .done(function(data) {
                // Update statistics if needed
                console.log('Stats updated:', data);
            });
    }, 30000);
});
</script>
@endpush

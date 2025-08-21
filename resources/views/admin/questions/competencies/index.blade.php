@extends('admin.layouts.app')

@section('title', 'Competency Management')
@section('page-title', 'Competency Descriptions Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item active">Competencies</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cogs mr-1"></i>
                    SJT Competency Descriptions ({{ $competencies->count() }})
                </h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <input type="text" id="searchCompetencies" class="form-control float-right" placeholder="Search competencies...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="competenciesTable">
                    <thead>
                        <tr>
                            <th width="120">Code</th>
                            <th>Competency Name</th>
                            <th width="100">Questions</th>
                            <th width="150">Usage Status</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($competencies as $competency)
                        <tr>
                            <td>
                                <span class="badge badge-primary badge-lg">{{ $competency->competency_code }}</span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $competency->competency_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $competency->short_description }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $competency->sjt_questions_count > 0 ? 'info' : 'light' }}">
                                    {{ $competency->sjt_questions_count }} questions
                                </span>
                            </td>
                            <td>
                                @if($competency->isUsedInActiveQuestions())
                                    <span class="badge badge-success">Active Usage</span>
                                @elseif($competency->sjt_questions_count > 0)
                                    <span class="badge badge-warning">Has Questions</span>
                                @else
                                    <span class="badge badge-light">No Questions</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.competencies.show', $competency) }}"
                                       class="btn btn-info btn-xs" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.competencies.edit', $competency) }}"
                                           class="btn btn-warning btn-xs" title="Edit Description">
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
        </div>
    </div>
</div>

<!-- Competency Overview Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-cogs"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Competencies</span>
                <span class="info-box-number">{{ $competencies->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">With Questions</span>
                <span class="info-box-number">{{ $competencies->where('sjt_questions_count', '>', 0)->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-question"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Questions</span>
                <span class="info-box-number">{{ $competencies->sum('sjt_questions_count') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Avg Questions</span>
                <span class="info-box-number">{{ $competencies->avg('sjt_questions_count') ? round($competencies->avg('sjt_questions_count'), 1) : 0 }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchCompetencies').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#competenciesTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
</script>
@endpush

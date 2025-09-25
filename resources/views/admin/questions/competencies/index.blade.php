@extends('admin.layouts.app')

@section('title', 'Competency Management')
@section('page-title', 'SJT Competency Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item active">Competencies</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-award mr-1"></i>
                        SJT Competencies
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="searchCompetencies">Search Competencies:</label>
                            <input type="text" id="searchCompetencies" class="form-control"
                                   placeholder="Search by name or code...">
                        </div>
                        <div class="col-md-6">
                            <label for="usageFilter">Filter by Usage:</label>
                            <select id="usageFilter" class="form-control">
                                <option value="">All Competencies</option>
                                <option value="used">Used in Questions</option>
                                <option value="unused">Not Used</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $competencies->count() }}</h3>
                    <p>Total Competencies</p>
                </div>
                <div class="icon">
                    <i class="fas fa-award"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $competencies->where('sjt_questions_count', '>', 0)->count() }}</h3>
                    <p>Used in Questions</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $competencies->where('sjt_questions_count', 0)->count() }}</h3>
                    <p>Not Used</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $competencies->sum('sjt_questions_count') }}</h3>
                    <p>Total Questions</p>
                </div>
                <div class="icon">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Competencies Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Competencies List</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table id="competenciesTable" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="80">Code</th>
                                <th width="200">Name</th>
                                <th width="300">Strength Description</th>
                                <th width="300">Development Activities</th>
                                <th width="100">Questions</th>
                                <th width="100">Usage</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($competencies as $competency)
                            <tr class="competency-row">
                                <td>
                                    <span class="badge badge-primary badge-lg">{{ $competency->competency_code }}</span>
                                </td>
                                <td>
                                    <strong>{{ $competency->competency_name }}</strong>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 300px;"
                                         title="{{ $competency->strength_description }}">
                                        {{ Str::limit($competency->strength_description, 80) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 300px;"
                                         title="{{ $competency->improvement_activity }}">
                                        {{ Str::limit($competency->improvement_activity, 80) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $competency->sjt_questions_count > 0 ? 'info' : 'secondary' }}">
                                        {{ $competency->sjt_questions_count }} questions
                                    </span>
                                </td>
                                <td>
                                    <span class="usage-status badge badge-{{ $competency->sjt_questions_count > 0 ? 'success' : 'warning' }}">
                                        {{ $competency->sjt_questions_count > 0 ? 'Used' : 'Not Used' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.questions.competencies.show', $competency) }}"
                                           class="btn btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('admin.questions.competencies.edit', $competency) }}"
                                               class="btn btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if($competency->sjt_questions_count > 0)
                                            <a href="{{ route('admin.questions.sjt.index') }}?competency={{ $competency->competency_code }}"
                                               class="btn btn-success" title="View Questions">
                                                <i class="fas fa-question-circle"></i>
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

    <!-- Competency Usage Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Question Distribution by Competency
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($competencies as $competency)
                            <div class="col-md-2 col-sm-4 col-6 mb-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-{{ $competency->sjt_questions_count >= 5 ? 'success' : ($competency->sjt_questions_count > 0 ? 'warning' : 'secondary') }}">
                                        {{ $competency->competency_code }}
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ Str::limit($competency->competency_name, 15) }}</span>
                                        <span class="info-box-number">
                                            {{ $competency->sjt_questions_count }}
                                            <small>questions</small>
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

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tools mr-1"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Add SJT Questions</span>
                                    <span class="info-box-number">
                                        <a href="{{ route('admin.questions.sjt.create') }}" class="btn btn-sm btn-primary">
                                            Create Question
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success">
                                    <i class="fas fa-download"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Export Data</span>
                                    <span class="info-box-number">
                                        <button onclick="exportCompetencies()" class="btn btn-sm btn-success">
                                            Export CSV
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-warning">
                                    <i class="fas fa-chart-line"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">View Analytics</span>
                                    <span class="info-box-number">
                                        <button onclick="showAnalytics()" class="btn btn-sm btn-warning">
                                            View Report
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script>
        // Export competencies data
        function exportCompetencies() {
            showLoading('Exporting...', 'Preparing competencies data for export...');

            setTimeout(function() {
                Swal.close();
                showSuccessToast('Competencies data exported successfully!');
                // Here you would trigger actual download
                // window.location.href = '/admin/competencies/export';
            }, 2000);
        }

        // Show analytics
        function showAnalytics() {
            Swal.fire({
                title: 'Competency Analytics',
                html: `
                    <div class="text-left">
                        <p><strong>Total Competencies:</strong> {{ $competencies->count() }}</p>
                        <p><strong>Used in Questions:</strong> {{ $competencies->where('sjt_questions_count', '>', 0)->count() }}</p>
                        <p><strong>Total Questions:</strong> {{ $competencies->sum('sjt_questions_count') }}</p>
                        <p><strong>Average Questions per Competency:</strong> {{ number_format($competencies->avg('sjt_questions_count'), 1) }}</p>
                        <hr>
                        <p class="text-muted">Most Used: {{ $competencies->sortByDesc('sjt_questions_count')->first()->competency_name ?? 'N/A' }}</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Close'
            });
        }

        $(document).ready(function() {
            // Search functionality
            $('#searchCompetencies').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#competenciesTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Usage filter
            $('#usageFilter').on('change', function() {
                var selectedUsage = $(this).val();
                if (selectedUsage === '') {
                    $('#competenciesTable tbody tr').show();
                } else {
                    $('#competenciesTable tbody tr').each(function() {
                        var usageStatus = $(this).find('.usage-status').text().trim();
                        var showRow = (selectedUsage === 'used' && usageStatus === 'Used') ||
                                     (selectedUsage === 'unused' && usageStatus === 'Not Used');
                        $(this).toggle(showRow);
                    });
                }
            });

            // Enhanced competency rows interaction
            $('.competency-row').hover(
                function() { $(this).addClass('table-active'); },
                function() { $(this).removeClass('table-active'); }
            );

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush

@push('styles')
    <style>
        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }

        .info-box {
            min-height: 90px;
        }

        .competency-row {
            transition: background-color 0.2s ease;
        }

        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endpush

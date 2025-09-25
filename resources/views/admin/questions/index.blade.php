@extends('admin.layouts.app')

@section('title', 'Question Bank')
@section('page-title', 'Question Bank Management')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Question Bank</li>
@endsection

@section('content')
<div class="row">

    <!-- Page Header Actions -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-question-circle mr-1"></i>
                    Question Versions Management
                </h3>
                <div class="card-tools">
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.questions.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create New Version
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Active Versions Overview -->
<div class="row">

    <!-- ST-30 Active Version -->
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-brain mr-1"></i>
                    ST-30 (Strength Typology) - Active Version
                </h3>
            </div>
            <div class="card-body">
                @if($activeVersions['st30'])
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-success">
                                    <i class="fas fa-check-circle"></i> ACTIVE
                                </span>
                                <h5 class="description-header">{{ $activeVersions['st30']->name }}</h5>
                                <span class="description-text">Version {{ $activeVersions['st30']->version }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="description-block">
                                <span class="description-percentage text-info">
                                    <i class="fas fa-question"></i> {{ $activeVersions['st30']->questions_count }}
                                </span>
                                <h5 class="description-header">Questions</h5>
                                <span class="description-text">30 Expected</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.questions.show', $activeVersions['st30']) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <a href="{{ route('admin.questions.st30.index') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-edit"></i> Manage Questions
                        </a>
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <p>No active ST-30 version found</p>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.questions.create') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-plus"></i> Create ST-30 Version
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- SJT Active Version -->
    <div class="col-md-6">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-check mr-1"></i>
                    SJT (Situational Judgment Test) - Active Version
                </h3>
            </div>
            <div class="card-body">
                @if($activeVersions['sjt'])
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-success">
                                    <i class="fas fa-check-circle"></i> ACTIVE
                                </span>
                                <h5 class="description-header">{{ $activeVersions['sjt']->name }}</h5>
                                <span class="description-text">Version {{ $activeVersions['sjt']->version }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="description-block">
                                <span class="description-percentage text-info">
                                    <i class="fas fa-question"></i> {{ $activeVersions['sjt']->questions_count }}
                                </span>
                                <h5 class="description-header">Questions</h5>
                                <span class="description-text">50 Expected</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.questions.show', $activeVersions['sjt']) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <a href="{{ route('admin.questions.sjt.index') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-edit"></i> Manage Questions
                        </a>
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <p>No active SJT version found</p>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.questions.create') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-plus"></i> Create SJT Version
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- All Versions Tables -->
<div class="row">

    <!-- ST-30 Versions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-brain mr-1"></i>
                    ST-30 Versions
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Version</th>
                            <th>Name</th>
                            <th>Questions</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($st30Versions as $version)
                        <tr class="{{ $version->is_active ? 'table-success' : '' }}">
                            <td>
                                <span class="badge badge-{{ $version->is_active ? 'success' : 'secondary' }}">
                                    V{{ $version->version }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $version->name }}</strong>
                                @if($version->is_active)
                                    <small class="badge badge-success ml-1">ACTIVE</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $version->st30_questions_count }}</span>
                                <small class="text-muted">/ 30</small>
                            </td>
                            <td>
                                @if($version->st30_questions_count >= 30)
                                    <span class="badge badge-success">Complete</span>
                                @elseif($version->st30_questions_count > 0)
                                    <span class="badge badge-warning">Incomplete</span>
                                @else
                                    <span class="badge badge-danger">Empty</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.questions.show', $version) }}" class="btn btn-primary btn-xs">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(Auth::user()->role === 'admin')
                                        @if(!$version->is_active && $version->st30_questions_count >= 30)
                                            <button class="btn btn-success btn-xs btn-activate"
                                                    data-version-id="{{ $version->id }}"
                                                    data-version-name="{{ $version->name }}"
                                                    data-version-type="ST-30"
                                                    title="Activate">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.questions.edit', $version) }}" class="btn btn-warning btn-xs">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!$version->is_active && !$version->hasResponses())
                                            <button class="btn btn-danger btn-xs btn-delete"
                                                    data-version-id="{{ $version->id }}"
                                                    data-version-name="{{ $version->name }}"
                                                    data-version-type="ST-30"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                No ST-30 versions found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SJT Versions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-check mr-1"></i>
                    SJT Versions
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Version</th>
                            <th>Name</th>
                            <th>Questions</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sjtVersions as $version)
                        <tr class="{{ $version->is_active ? 'table-info' : '' }}">
                            <td>
                                <span class="badge badge-{{ $version->is_active ? 'info' : 'secondary' }}">
                                    V{{ $version->version }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $version->name }}</strong>
                                @if($version->is_active)
                                    <small class="badge badge-info ml-1">ACTIVE</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $version->sjt_questions_count }}</span>
                                <small class="text-muted">/ 50</small>
                            </td>
                            <td>
                                @if($version->sjt_questions_count >= 50)
                                    <span class="badge badge-success">Complete</span>
                                @elseif($version->sjt_questions_count > 0)
                                    <span class="badge badge-warning">Incomplete</span>
                                @else
                                    <span class="badge badge-danger">Empty</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.questions.show', $version) }}" class="btn btn-primary btn-xs">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(Auth::user()->role === 'admin')
                                        @if(!$version->is_active && $version->sjt_questions_count >= 50)
                                            <button class="btn btn-success btn-xs btn-activate"
                                                    data-version-id="{{ $version->id }}"
                                                    data-version-name="{{ $version->name }}"
                                                    data-version-type="SJT"
                                                    title="Activate">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.questions.edit', $version) }}" class="btn btn-warning btn-xs">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!$version->is_active && !$version->hasResponses())
                                            <button class="btn btn-danger btn-xs btn-delete"
                                                    data-version-id="{{ $version->id }}"
                                                    data-version-name="{{ $version->name }}"
                                                    data-version-type="SJT"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                No SJT versions found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
                    <i class="fas fa-bolt mr-1"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('admin.questions.st30.index') }}" class="btn btn-success btn-block">
                            <i class="fas fa-brain mr-1"></i>
                            Manage ST-30 Questions
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.questions.sjt.index') }}" class="btn btn-info btn-block">
                            <i class="fas fa-clipboard-check mr-1"></i>
                            Manage SJT Questions
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.questions.typologies.index') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-tags mr-1"></i>
                            Manage Typologies
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.questions.competencies.index') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-star mr-1"></i>
                            Manage Competencies
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // SweetAlert2 Activate Version Confirmation
    $('.btn-activate').on('click', function(e) {
        e.preventDefault();
        var versionId = $(this).data('version-id');
        var versionName = $(this).data('version-name');
        var versionType = $(this).data('version-type');

        customConfirm({
            title: `Activate ${versionType} Version?`,
            text: `Are you sure you want to activate "${versionName}"? This will deactivate the current active version.`,
            icon: 'question',
            confirmButtonText: 'Yes, activate!',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('admin.questions.activate', ':id') }}'.replace(':id', versionId);
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // SweetAlert2 Delete Version Confirmation
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var versionId = $(this).data('version-id');
        var versionName = $(this).data('version-name');
        var versionType = $(this).data('version-type');

        confirmDelete(
            `Delete ${versionType} Version?`,
            `Are you sure you want to delete "${versionName}"? This action cannot be undone and will remove all questions in this version.`,
            '{{ route('admin.questions.destroy', ':id') }}'.replace(':id', versionId)
        );
    });

    // Auto refresh version statistics every 30 seconds
    setInterval(function() {
        // You can add AJAX calls here to refresh version stats if needed
    }, 30000);
});
</script>
@endpush

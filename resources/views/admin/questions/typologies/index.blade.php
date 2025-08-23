@extends('admin.layouts.app')

@section('title', 'Typology Management')
@section('page-title', 'ST-30 Typology Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item active">Typologies</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Success/Error Messages -->
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="card-title mb-0">ST-30 Typologies</h3>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('admin.typologies.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add New Typology
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card border-left-primary h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="text-primary">
                                                <i class="fas fa-brain fa-2x"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Typologies
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $typologies->total() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-left-success h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="text-success">
                                                <i class="fas fa-check-circle fa-2x"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Active Typologies
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $typologies->where('is_active', true)->count() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-left-warning h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="text-warning">
                                                <i class="fas fa-pause-circle fa-2x"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Inactive Typologies
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $typologies->where('is_active', false)->count() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-left-info h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="text-info">
                                                <i class="fas fa-layer-group fa-2x"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                ST-30 Categories
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                                            <div class="small text-muted">H, N, S, Gi, T, R, E, Te</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Typologies Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="10%">Code</th>
                                        <th width="20%">Typology Name</th>
                                        <th width="35%">Description</th>
                                        <th width="15%">Category</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($typologies as $typology)
                                        <tr>
                                            <td>
                                                <span class="badge badge-secondary font-weight-bold">
                                                    {{ $typology->typology_code }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $typology->typology_name }}</strong>
                                            </td>
                                            <td>
                                                <p class="mb-0 text-muted">
                                                    {{ Str::limit($typology->description, 120) }}
                                                </p>
                                            </td>
                                            <td>
                                                @php
                                                    $category = '';
                                                    $categoryClass = '';
                                                    $firstChar = substr($typology->typology_code, 0, 1);
                                                    switch ($firstChar) {
                                                        case 'H':
                                                            $category = 'Headman';
                                                            $categoryClass = 'primary';
                                                            break;
                                                        case 'N':
                                                            $category = 'Networking';
                                                            $categoryClass = 'info';
                                                            break;
                                                        case 'S':
                                                            $category = 'Servicing';
                                                            $categoryClass = 'success';
                                                            break;
                                                        case 'G':
                                                            $category = 'Generating Ideas';
                                                            $categoryClass = 'warning';
                                                            break;
                                                        case 'T':
                                                            $category = 'Thinking';
                                                            $categoryClass = 'secondary';
                                                            break;
                                                        case 'R':
                                                            $category = 'Reasoning';
                                                            $categoryClass = 'dark';
                                                            break;
                                                        case 'E':
                                                            $category = 'Elementary';
                                                            $categoryClass = 'danger';
                                                            break;
                                                        default:
                                                            $category = 'Technical';
                                                            $categoryClass = 'light';
                                                    }
                                                @endphp
                                                <span class="badge badge-{{ $categoryClass }}">{{ $category }}</span>
                                            </td>
                                            <td>
                                                @if ($typology->is_active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.typologies.show', $typology) }}"
                                                        class="btn btn-info btn-sm" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.typologies.edit', $typology) }}"
                                                        class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                   
                                                    <button type="button" class="btn btn-danger btn-sm" title="Delete"
                                                        onclick="confirmDelete('{{ $typology->typology_code }}', '{{ route('admin.typologies.destroy', $typology) }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-brain fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No Typologies Found</h5>
                                                    <p class="text-muted">Get started by creating your first typology.</p>
                                                    <a href="{{ route('admin.typologies.create') }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Create Typology
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Bootstrap Pagination -->
                        @if ($typologies->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div>
                                    <p class="small text-muted mb-0">
                                        Showing {{ $typologies->firstItem() }} to {{ $typologies->lastItem() }} of
                                        {{ $typologies->total() }} results
                                    </p>
                                </div>
                                <div>
                                    <nav aria-label="Typologies pagination">
                                        <ul class="pagination pagination-sm mb-0">
                                            {{-- Previous Page Link --}}
                                            @if ($typologies->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">
                                                        <i class="fas fa-chevron-left"></i>
                                                    </span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $typologies->previousPageUrl() }}"
                                                        rel="prev">
                                                        <i class="fas fa-chevron-left"></i>
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($typologies->getUrlRange(1, $typologies->lastPage()) as $page => $url)
                                                @if ($page == $typologies->currentPage())
                                                    <li class="page-item active">
                                                        <span class="page-link">{{ $page }}</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                            href="{{ $url }}">{{ $page }}</a>
                                                    </li>
                                                @endif
                                            @endforeach

                                            {{-- Next Page Link --}}
                                            @if ($typologies->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $typologies->nextPageUrl() }}"
                                                        rel="next">
                                                        <i class="fas fa-chevron-right"></i>
                                                    </a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <span class="page-link">
                                                        <i class="fas fa-chevron-right"></i>
                                                    </span>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        @endif
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
                    <p>Are you sure you want to delete typology <strong id="deleteTypologyName"></strong>?</p>
                    <p class="text-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        This action cannot be undone and may affect related ST-30 questions.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
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

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
@endpush

@push('styles')
    <style>
        /* Custom border left colors for stat cards */
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

        /* Text utilities */
        .text-xs {
            font-size: 0.75rem;
        }

        .text-gray-800 {
            color: #5a5c69 !important;
        }

        /* Empty state styling */
        .empty-state {
            padding: 2rem;
        }

        /* Button group spacing */
        .btn-group-sm>.btn {
            margin-right: 2px;
        }

        .btn-group-sm>.btn:last-child {
            margin-right: 0;
        }

        /* Pagination styling */
        .pagination-sm .page-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
        }
    </style>
@endpush

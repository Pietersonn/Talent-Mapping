@extends('admin.layouts.app')

@section('title', 'Typology Management')
@section('page-title', 'ST-30 Typology Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item active">Typologies</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title mb-0">ST-30 Typologies</h3>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.questions.typologies.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add New Typology
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="searchTypologies">Search Typologies:</label>
                            <input type="text" id="searchTypologies" class="form-control"
                                   placeholder="Search by name or code...">
                        </div>
                        <div class="col-md-4">
                            <label for="categoryFilter">Filter by Category:</label>
                            <select id="categoryFilter" class="form-control">
                                <option value="">All Categories</option>
                                <option value="H">H - Human Relations</option>
                                <option value="N">N - Nurturing</option>
                                <option value="S">S - Social</option>
                                <option value="Gi">Gi - Giving</option>
                                <option value="T">T - Thinking</option>
                                <option value="R">R - Rational</option>
                                <option value="E">E - Enterprise</option>
                                <option value="Te">Te - Technical</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="statusFilter">Filter by Status:</label>
                            <select id="statusFilter" class="form-control">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
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
                    <h3>{{ $typologies->count() }}</h3>
                    <p>Total Typologies</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $typologies->where('is_active', true)->count() }}</h3>
                    <p>Active Typologies</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $typologies->where('is_active', false)->count() }}</h3>
                    <p>Inactive Typologies</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $typologies->groupBy('category')->count() }}</h3>
                    <p>Categories</p>
                </div>
                <div class="icon">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Typologies Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Typologies List</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table id="typologiesTable" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="80">Code</th>
                                <th width="200">Name</th>
                                <th width="80">Category</th>
                                <th width="300">Strength Description</th>
                                <th width="100">Questions</th>
                                <th width="80">Status</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($typologies as $typology)
                            <tr>
                                <td>
                                    <span class="badge badge-primary badge-lg">{{ $typology->typology_code }}</span>
                                </td>
                                <td>
                                    <strong>{{ $typology->typology_name }}</strong>
                                </td>
                                <td>
                                    <span class="typology-category badge badge-secondary">
                                        {{ substr($typology->typology_code, 0, strpos($typology->typology_code, ' ') ?: 2) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 300px;"
                                         title="{{ $typology->strength_description }}">
                                        {{ Str::limit($typology->strength_description, 60) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $typology->st30_questions_count ?? 0 }} questions
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $typology->is_active ? 'success' : 'secondary' }}">
                                        {{ $typology->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.questions.typologies.show', $typology) }}"
                                           class="btn btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.questions.typologies.edit', $typology) }}"
                                           class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                        
                                        <button type="button" class="btn btn-danger"
                                                onclick="confirmDelete('{{ $typology->typology_code }}', '{{ route('admin.questions.typologies.destroy', $typology) }}')"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

    <!-- Category Breakdown -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Category Breakdown
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $categories = [
                                'H' => 'Human Relations',
                                'N' => 'Nurturing',
                                'S' => 'Social',
                                'Gi' => 'Giving',
                                'T' => 'Thinking',
                                'R' => 'Rational',
                                'E' => 'Enterprise',
                                'Te' => 'Technical'
                            ];
                        @endphp
                        @foreach($categories as $code => $name)
                            @php
                                $count = $typologies->filter(function($t) use ($code) {
                                    return strpos($t->typology_code, $code) === 0;
                                })->count();
                            @endphp
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-{{ $count > 0 ? 'primary' : 'secondary' }}">
                                        {{ $code }}
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $name }}</span>
                                        <span class="info-box-number">{{ $count }} typologies</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script>
        // Delete confirmation with SweetAlert2
        function confirmDelete(typologyCode, deleteUrl) {
            confirmDelete(
                'Delete Typology?',
                `Are you sure you want to delete typology "${typologyCode}"? This action cannot be undone and may affect assessment results.`,
                deleteUrl
            );
        }

        // Toggle status confirmation
        function confirmToggleStatus(typologyCode, toggleUrl, currentStatus) {
            const action = currentStatus ? 'deactivate' : 'activate';
            const actionText = currentStatus ? 'Deactivate' : 'Activate';

            confirmToggleStatus(
                `${actionText} Typology?`,
                `Are you sure you want to ${action} typology "${typologyCode}"?`,
                toggleUrl,
                currentStatus
            );
        }

        $(document).ready(function() {
            // Search functionality
            $('#searchTypologies').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#typologiesTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Category filter
            $('#categoryFilter').on('change', function() {
                var selectedCategory = $(this).val();
                if (selectedCategory === '') {
                    $('#typologiesTable tbody tr').show();
                } else {
                    $('#typologiesTable tbody tr').each(function() {
                        var rowCategory = $(this).find('.typology-category').text().trim();
                        if (rowCategory === selectedCategory) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
            });

            // Status filter
            $('#statusFilter').on('change', function() {
                var selectedStatus = $(this).val();
                if (selectedStatus === '') {
                    $('#typologiesTable tbody tr').show();
                } else {
                    $('#typologiesTable tbody tr').each(function() {
                        var isActive = $(this).find('.badge-success').length > 0;
                        var showRow = (selectedStatus === 'active' && isActive) ||
                                     (selectedStatus === 'inactive' && !isActive);
                        $(this).toggle(showRow);
                    });
                }
            });
        });
    </script>
@endpush

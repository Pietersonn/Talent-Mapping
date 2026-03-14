@extends('admin.layouts.app')

@section('title', 'ST-30 Questions')

@push('styles')
<style>
    /* --- SEARCH & BUTTONS STYLE --- */
    .search-group { position: relative; width: 320px; }
    .search-input { width: 100%; height: 46px; padding: 10px 45px 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; background: #ffffff; transition: all 0.3s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); color: #334155; }
    .search-input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15); }
    .loading-spinner { position: absolute; right: 14px; top: 33%; transform: translateY(-50%); display: none; color: #22c55e; font-size: 1.1rem; pointer-events: none; }
    .search-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1rem; pointer-events: none; transition: opacity 0.2s; }

    .select-version { height: 46px; padding: 0 35px 0 12px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; background: white; cursor: pointer; outline: none; min-width: 200px; color: #334155; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; }
    .select-version:focus { border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15); }

    .btn-print { width: 46px; height: 46px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all 0.2s; text-decoration: none; }
    .btn-print:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; transform: translateY(-1px); }

    .btn-add { height: 46px; padding: 0 24px; background: #22c55e; color: white; border-radius: 12px; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; border: none; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3); transition: all 0.2s; }
    .btn-add:hover { background: #16a34a; transform: translateY(-1px); }

    /* --- TABLE STYLE --- */
    .table-card { background: white; border: 1px solid #f1f5f9; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 1.5rem; }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { text-align: left; padding: 1.25rem; background: #f8fafc; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 1.25rem; border-bottom: 1px solid #f1f5f9; vertical-align: top; font-size: 0.9rem; color: #334155; background: white; }
    .custom-table tr:hover td { background-color: #f8fafc; }

    /* --- PAGINATION (GREEN STYLE) --- */
    .pagination-wrapper { padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .btn-paginate { background: white; border: 1px solid #e2e8f0; color: #22c55e; padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 0.85rem; text-decoration: none; transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-paginate:hover:not(.disabled) { background: #f0fdf4; border-color: #22c55e; color: #15803d; transform: translateY(-1px); }
    .btn-paginate.disabled { color: #94a3b8; background: #f8fafc; cursor: not-allowed; opacity: 0.7; }

    /* Stats Adjustment */
    .stats-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .stat-card { background: white; padding: 1.25rem; border-radius: 16px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; justify-content: space-between; }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: #0f172a; }
    .stat-label { font-size: 0.875rem; color: #64748b; }
    .stat-icon { font-size: 1.5rem; color: #e2e8f0; align-self: flex-end; margin-top: -1.5rem; }

    /* --- COMPONENTS --- */
    .statement-text { font-size: 0.95rem; color: #0f172a; line-height: 1.5; }
    .text-expand-btn { color: #22c55e; border: none; background: none; font-size: 0.8rem; font-weight: 600; cursor: pointer; padding: 0; margin-left: 5px; }
    .text-expand-btn:hover { text-decoration: underline; }

    .badge-typology { background: #eff6ff; color: #2563eb; padding: 4px 8px; border-radius: 6px; font-weight: 600; font-size: 0.8rem; border: 1px solid #dbeafe; }
    .typology-desc { display: block; font-size: 0.75rem; color: #64748b; margin-top: 4px; }

    .status-dot { height: 8px; width: 8px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    .dot-active { background-color: #22c55e; }
    .dot-inactive { background-color: #cbd5e1; }

    .action-buttons { display: flex; gap: 8px; }
    .btn-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .btn-view { background: #ecfdf5; color: #059669; }
    .btn-edit { background: #eff6ff; color: #2563eb; }
    .btn-delete { background: #fef2f2; color: #dc2626; }
    .btn-icon:hover { opacity: 0.8; transform: scale(1.05); }

    @media print { body { display: none; } }
</style>
@endpush

@section('header')
<div class="header-wrapper">
    <div>
        <h1 class="page-title">
            <i class="fas fa-brain" style="color: #22c55e; background: #dcfce7; padding: 10px; border-radius: 12px; font-size: 1.1rem; margin-right: 10px;"></i>
            ST-30
        </h1>
        <p style="font-size: 0.9rem; color: #64748b; margin-left: 54px; margin-top: -5px;">
            Manajemen pertanyaan dan versi tes ST-30
        </p>
    </div>

    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div>
            <select id="version_select" class="select-version" onchange="changeVersion()">
                <option value="">-- Pilih Versi --</option>
                @foreach($versions as $version)
                    <option value="{{ $version->id }}" {{ $selectedVersion && $selectedVersion->id == $version->id ? 'selected' : '' }}>
                        {{ $version->name }} {{ $version->is_active ? '(Active)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="search-group">
            <input type="text" id="searchQuestions" class="search-input" placeholder="Cari di halaman ini..." autocomplete="off">
            <i class="fas fa-search search-icon"></i>
            <i class="fas fa-circle-notch fa-spin loading-spinner"></i>
        </div>

        @if($selectedVersion)
            <button onclick="exportQuestions()" class="btn-print" title="Export Data">
                <i class="fas fa-print"></i>
            </button>

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.questions.st30.create', ['version' => $selectedVersion->id]) }}" class="btn-add">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            @endif
        @endif
    </div>
</div>
@endsection

@section('content')
    @if($selectedVersion)
        <div class="stats-container">
            <div class="stat-card">
                <div>
                    {{-- Gunakan total() karena sekarang $questions adalah object paginator --}}
                    <div class="stat-value">{{ $questions instanceof \Illuminate\Pagination\LengthAwarePaginator ? $questions->total() : $questions->count() }}</div>
                    <div class="stat-label">Total Pertanyaan</div>
                </div>
                <div class="stat-icon"><i class="fas fa-question-circle"></i></div>
            </div>
            <div class="stat-card">
                <div>
                    <div class="stat-value" style="color: {{ $selectedVersion->is_active ? '#22c55e' : '#94a3b8' }}">
                        {{ $selectedVersion->is_active ? 'Active' : 'Inactive' }}
                    </div>
                    <div class="stat-label">Status Versi</div>
                </div>
                <div class="stat-icon"><i class="fas fa-toggle-on"></i></div>
            </div>
            <div class="stat-card">
                <div>
                    <div class="stat-value">{{ count($typologyStats) }}</div>
                    <div class="stat-label">Typologies</div>
                </div>
                <div class="stat-icon"><i class="fas fa-tags"></i></div>
            </div>
            <div class="stat-card">
                <div>
                    @php
                        $totalQ = $questions instanceof \Illuminate\Pagination\LengthAwarePaginator ? $questions->total() : $questions->count();
                    @endphp
                    <div class="stat-value" style="color: {{ 30 - $totalQ > 0 ? '#ef4444' : '#22c55e' }}">
                        {{ 30 - $totalQ }}
                    </div>
                    <div class="stat-label">Kekurangan Soal</div>
                </div>
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
        </div>

        <div class="table-card">
            <div style="overflow-x: auto;">
                @if($questions->count() > 0)
                    <table class="custom-table" id="questionsTable">
                        <thead>
                            <tr>
                                <th width="80">No.</th>
                                <th width="50%">Statement (Pernyataan)</th>
                                <th width="20%">Tipologi</th>
                                <th width="10%">Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="questionsTableBody">
                            @foreach($questions as $question)
                                <tr>
                                    <td>
                                        <span style="font-family: monospace; font-weight: 600; color: #64748b;">
                                            #{{ str_pad($question->number, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="statement-text">
                                            <span class="short-text">{{ Str::limit($question->statement, 70) }}</span>
                                            @if(strlen($question->statement) > 70)
                                                <button class="text-expand-btn" onclick="toggleStatement(this)">
                                                    Lihat
                                                </button>
                                                <span class="full-text" style="display: none;">
                                                    {{ $question->statement }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-typology">
                                            {{ $question->typology_code }}
                                        </span>
                                        @if($question->typologyDescription)
                                            <span class="typology-desc">
                                                {{ Str::limit($question->typologyDescription->typology_name, 25) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($question->questionVersion->is_active)
                                            <span class="status-dot dot-active"></span> <span style="font-size: 0.85rem; color: #0f172a;">Aktif</span>
                                        @else
                                            <span class="status-dot dot-inactive"></span> <span style="font-size: 0.85rem; color: #94a3b8;">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.questions.st30.show', $question) }}" class="btn-icon btn-view" title="View">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            @if(Auth::user()->role === 'admin')
                                                <a href="{{ route('admin.questions.st30.edit', $question) }}" class="btn-icon btn-edit" title="Edit">
                                                    <i class="fas fa-pen text-xs"></i>
                                                </a>
                                                <button type="button" class="btn-icon btn-delete"
                                                        data-question-number="{{ $question->number }}"
                                                        data-delete-url="{{ route('admin.questions.st30.destroy', $question) }}"
                                                        title="Delete">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- --- CUSTOM PAGINATION (GREEN STYLE) --- --}}
                    @if($questions instanceof \Illuminate\Pagination\LengthAwarePaginator && $questions->hasPages())
                    <div class="pagination-wrapper">
                        <div style="font-size: 0.85rem; color: #64748b;">
                            Halaman <span style="font-weight: 700; color: #22c55e;">{{ $questions->currentPage() }}</span> dari {{ $questions->lastPage() }}
                        </div>
                        <div style="display: flex; gap: 10px;">
                            {{-- Tombol Sebelumnya --}}
                            @if ($questions->onFirstPage())
                                <span class="btn-paginate disabled"><i class="fas fa-chevron-left"></i> Sebelumnya</span>
                            @else
                                {{-- Gunakan appends() agar query params (version/search) terbawa --}}
                                <a href="{{ $questions->appends(request()->query())->previousPageUrl() }}" class="btn-paginate">
                                    <i class="fas fa-chevron-left"></i> Sebelumnya
                                </a>
                            @endif

                            {{-- Tombol Selanjutnya --}}
                            @if ($questions->hasMorePages())
                                <a href="{{ $questions->appends(request()->query())->nextPageUrl() }}" class="btn-paginate">
                                    Selanjutnya <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="btn-paginate disabled">Selanjutnya <i class="fas fa-chevron-right"></i></span>
                            @endif
                        </div>
                    </div>
                    @endif

                @else
                    <div style="text-align: center; padding: 4rem;">
                        <div style="background: #f1f5f9; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <i class="fas fa-folder-open fa-2x" style="color: #94a3b8;"></i>
                        </div>
                        <h5 style="color: #0f172a; font-weight: 700; margin-bottom: 0.5rem;">Belum ada Data</h5>
                        <p style="color: #64748b;">Versi ini belum memiliki pertanyaan.</p>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.questions.st30.create', ['version' => $selectedVersion->id]) }}" class="btn-add" style="margin-top: 1rem;">
                                <i class="fas fa-plus"></i> Tambah Soal Pertama
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    @else
        <div style="text-align: center; padding: 5rem 1rem;">
            <div style="background: #f0fdf4; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.1);">
                <i class="fas fa-brain fa-3x" style="color: #22c55e;"></i>
            </div>
            <h3 style="font-weight: 800; color: #0f172a; margin-bottom: 1rem;">Pilih Versi ST-30</h3>
            <p style="color: #64748b; max-width: 400px; margin: 0 auto 2rem;">Silakan pilih versi soal di atas untuk melihat, mengelola, atau menambah pertanyaan ST-30.</p>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        // Version change handler
        function changeVersion() {
            var versionId = document.getElementById('version_select').value;
            if (versionId) {
                window.location.href = '{{ route('admin.questions.st30.index') }}?version=' + versionId;
            } else {
                window.location.href = '{{ route('admin.questions.st30.index') }}';
            }
        }

        // Export questions
        function exportQuestions() {
            var versionId = '{{ $selectedVersion ? $selectedVersion->id : '' }}';
            var searchValue = $('#searchQuestions').val();

            if (versionId) {
                var url = '{{ route('admin.questions.st30.export') }}';
                var params = new URLSearchParams();
                params.append('version', versionId);
                if(searchValue) {
                    params.append('search', searchValue);
                }
                window.open(url + '?' + params.toString(), '_blank');
            } else {
                Swal.fire('Error', 'Please select a version to export.', 'error');
            }
        }

        // Toggle Expand Text Logic
        function toggleStatement(btn) {
            const shortText = $(btn).siblings('.short-text');
            const fullText = $(btn).siblings('.full-text');

            if (fullText.is(':visible')) {
                fullText.hide();
                shortText.show();
                $(btn).text('Lihat');
            } else {
                fullText.show();
                shortText.hide();
                $(btn).text('Tutup');
            }
        }

        $(document).ready(function() {
            let searchTimeout;
            const searchInput = $('#searchQuestions');

            // Client-side Search Functionality
            searchInput.on('keyup', function() {
                const query = $(this).val().toLowerCase();

                // Tampilkan loading spinner
                $('.loading-spinner').show();
                $('.search-icon').hide();

                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    $('#questionsTable tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(query) > -1);
                    });

                    // Sembunyikan spinner setelah selesai
                    $('.loading-spinner').hide();
                    $('.search-icon').show();
                }, 300);
            });

            // Delete confirmation with SweetAlert2
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var questionNumber = $(this).data('question-number');
                var deleteUrl = $(this).data('delete-url');

                Swal.fire({
                    title: 'Hapus Soal?',
                    html: `Yakin ingin menghapus Soal <b>#${questionNumber}</b>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#f1f5f9',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: '<span style="color:black">Batal</span>',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-4 py-2',
                        cancelButton: 'rounded-xl px-4 py-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form = $('<form>', {
                            'method': 'POST',
                            'action': deleteUrl
                        });
                        form.append('{{ csrf_field() }}');
                        form.append('<input type="hidden" name="_method" value="DELETE">');
                        $('body').append(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush

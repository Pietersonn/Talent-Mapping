@extends('admin.layouts.app')

@section('title', 'Manajemen Versi Soal')

@push('styles')
<style>
    /* CSS Variables & Theme */
    :root { --primary-color: #0f172a; --st30-color: #8b5cf6; --st30-bg: #f5f3ff; --sjt-color: #0ea5e9; --sjt-bg: #f0f9ff; }

    /* Header */
    .header-wrapper { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
    .page-title { font-size: 1.5rem; font-weight: 800; color: var(--primary-color); display: flex; align-items: center; gap: 12px; }
    .page-icon { background: #e2e8f0; color: var(--primary-color); padding: 10px; border-radius: 12px; font-size: 1.2rem; }

    /* Search & Buttons */
    .search-group { position: relative; width: 320px; }
    .search-input { width: 100%; height: 46px; padding: 10px 45px 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; background: #ffffff; transition: all 0.3s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); color: #334155; }
    .search-input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15); }
    .loading-spinner { position: absolute; right: 14px; top: 33%; transform: translateY(-50%); display: none; color: #22c55e; font-size: 1.1rem; pointer-events: none; }
    .search-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1rem; pointer-events: none; transition: opacity 0.2s; }

    .btn-print { width: 46px; height: 46px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all 0.2s; text-decoration: none; }
    .btn-print:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; transform: translateY(-1px); }

    .btn-add { height: 46px; padding: 0 24px; background: #22c55e; color: white; border-radius: 12px; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; border: none; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3); transition: all 0.2s; }
    .btn-add:hover { background: #16a34a; transform: translateY(-1px); }

    /* Grid & Card */
    .dashboard-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    @media (max-width: 992px) { .dashboard-grid { grid-template-columns: 1fr; } }
    .content-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden; display: flex; flex-direction: column; height: 100%; }

    /* Hero Header */
    .card-hero { padding: 1.5rem; position: relative; border-bottom: 1px solid #f1f5f9; }
    .hero-st30 { background: linear-gradient(135deg, #fff 0%, var(--st30-bg) 100%); }
    .hero-sjt { background: linear-gradient(135deg, #fff 0%, var(--sjt-bg) 100%); }
    .hero-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
    .hero-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .icon-st30 { background: white; color: var(--st30-color); }
    .icon-sjt { background: white; color: var(--sjt-color); }
    .version-title { font-size: 1.1rem; font-weight: 800; color: #1e293b; margin-bottom: 4px; }
    .version-subtitle { font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }

    /* Table */
    .list-wrapper { flex: 1; overflow: hidden; display: flex; flex-direction: column; }
    .list-header { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: white; display: flex; justify-content: space-between; align-items: center; }
    .table-scroll { overflow-y: auto; max-height: 400px; }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { position: sticky; top: 0; background: #f8fafc; z-index: 10; padding: 0.75rem 1.5rem; text-align: left; font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 0.75rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; color: #334155; }
    .custom-table tr:hover td { background: #f8fafc; }
    .row-active { background-color: #f0fdf4 !important; }
    .row-active td { color: #166534; font-weight: 600; }

    /* Action Buttons (Matched with User Mgmt) */
    .action-buttons { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .btn-view { background: #ecfdf5; color: #059669; }
    .btn-edit { background: #eff6ff; color: #2563eb; }
    .btn-delete { background: #fef2f2; color: #dc2626; }
    .btn-icon:hover { opacity: 0.8; transform: scale(1.05); }

    .btn-activate { padding: 0 12px; height: 32px; font-size: 0.75rem; border-radius: 8px; border: 1px solid #cbd5e1; background: white; color: #475569; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; }
    .btn-activate:hover { border-color: #10b981; background: #ecfdf5; color: #047857; transform: translateY(-1px); }

    .badge-active { background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; border: 1px solid #bbf7d0; display: inline-flex; align-items: center; gap: 4px; }
</style>
@endpush

@section('header')
<div class="header-wrapper">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
            <i class="fas fa-layer-group" style="color: #22c55e; background: #dcfce7; padding: 8px; border-radius: 8px; margin-right: 8px;"></i>
            Manajemen Versi Soal
        </h1>
    </div>

    <div style="display: flex; gap: 12px; align-items: center;">
        <div class="search-group">
            <input type="text" id="versionSearch" class="search-input" placeholder="Cari (Nama, Tipe, Status)..." autocomplete="off">
            <i class="fas fa-search search-icon"></i>
            <i class="fas fa-circle-notch fa-spin loading-spinner"></i>
        </div>

        {{-- Print Button --}}
        <a href="{{ route('admin.questions.export.pdf') }}" id="btnExportPdf" target="_blank" class="btn-print" title="Cetak Rekap">
            <i class="fas fa-print"></i>
        </a>

        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.questions.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Versi Baru
            </a>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="dashboard-grid">

    {{-- CARD ST-30 --}}
    <div class="content-card">
        <div class="card-hero hero-st30">
            <div class="hero-top">
                <div>
                    <div class="version-subtitle">PERSONALITY</div>
                    <div class="version-title">Strength Typology (ST-30)</div>
                </div>
                <div class="hero-icon icon-st30"><i class="fas fa-brain"></i></div>
            </div>

            @if($activeVersions['st30'])
                <div class="bg-white/60 backdrop-blur-sm p-3 rounded-xl border border-violet-100">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="badge-active"><i class="fas fa-check-circle"></i> SEDANG AKTIF</span>
                        <span class="text-sm font-bold text-slate-700">{{ $activeVersions['st30']->name }}</span>
                    </div>
                    <div class="text-xs text-slate-500 pl-1">
                        Versi {{ $activeVersions['st30']->version }} &bull; {{ $activeVersions['st30']->st30_questions_count }}/30 Soal
                    </div>
                </div>
            @else
                <div class="text-sm text-slate-400 italic">Belum ada versi aktif.</div>
            @endif
        </div>

        <div class="list-wrapper">
            <div class="list-header">
                <span class="text-xs font-bold text-slate-400 uppercase">Daftar Versi</span>
                <a href="{{ route('admin.questions.st30.index') }}" class="text-xs font-bold text-violet-600 hover:text-violet-800">
                    KELOLA SOAL <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="table-scroll">
                <table class="custom-table" id="st30Table">
                    <thead>
                        <tr>
                            <th>Ver</th>
                            <th>Nama Versi</th>
                            <th class="text-center">Soal</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($st30Versions as $version)
                        <tr class="{{ $version->is_active ? 'row-active' : '' }}">
                            <td><span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded text-slate-600">v{{ $version->version }}</span></td>
                            <td>
                                <div class="font-semibold">{{ $version->name }}</div>
                                <div class="text-xs text-slate-400">{{ $version->created_at->format('d/m/Y') }}</div>
                                <span style="display:none;">{{ $version->is_active ? 'aktif' : 'tidak aktif' }} ST30</span>
                            </td>
                            <td class="text-center">
                                @if($version->st30_questions_count == 30)
                                    <span class="text-green-600 font-bold text-xs"><i class="fas fa-check"></i> 30</span>
                                @else
                                    <span class="text-orange-500 font-bold text-xs">{{ $version->st30_questions_count }}/30</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="action-buttons">
                                    @if(Auth::user()->role === 'admin' && !$version->is_active)
                                        <button class="btn-activate" onclick="checkAndActivate('{{ $version->id }}', '{{ $version->name }}', 'ST-30', {{ $version->st30_questions_count }}, 30)">
                                            Gunakan
                                        </button>
                                    @endif

                                    <a href="{{ route('admin.questions.show', $version->id) }}" class="btn-icon btn-view" title="Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.questions.edit', $version->id) }}" class="btn-icon btn-edit" title="Edit">
                                            <i class="fas fa-pen text-xs"></i>
                                        </a>
                                        @if(!$version->is_active && !$version->hasResponses())
                                            <button class="btn-icon btn-delete" title="Hapus" onclick="deleteVersion('{{ $version->id }}', '{{ $version->name }}')">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-slate-400">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CARD SJT --}}
    <div class="content-card">
        <div class="card-hero hero-sjt">
            <div class="hero-top">
                <div>
                    <div class="version-subtitle">JUDGMENT TEST</div>
                    <div class="version-title">Situational Judgment (SJT)</div>
                </div>
                <div class="hero-icon icon-sjt"><i class="fas fa-clipboard-check"></i></div>
            </div>

            @if($activeVersions['sjt'])
                <div class="bg-white/60 backdrop-blur-sm p-3 rounded-xl border border-sky-100">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="badge-active"><i class="fas fa-check-circle"></i> SEDANG AKTIF</span>
                        <span class="text-sm font-bold text-slate-700">{{ $activeVersions['sjt']->name }}</span>
                    </div>
                    <div class="text-xs text-slate-500 pl-1">
                        Versi {{ $activeVersions['sjt']->version }} &bull; {{ $activeVersions['sjt']->sjt_questions_count }}/50 Soal
                    </div>
                </div>
            @else
                <div class="text-sm text-slate-400 italic">Belum ada versi aktif.</div>
            @endif
        </div>

        <div class="list-wrapper">
            <div class="list-header">
                <span class="text-xs font-bold text-slate-400 uppercase">Daftar Versi</span>
                <a href="{{ route('admin.questions.sjt.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-800">
                    KELOLA SOAL <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="table-scroll">
                <table class="custom-table" id="sjtTable">
                    <thead>
                        <tr>
                            <th>Ver</th>
                            <th>Nama Versi</th>
                            <th class="text-center">Soal</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sjtVersions as $version)
                        <tr class="{{ $version->is_active ? 'row-active' : '' }}">
                            <td><span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded text-slate-600">v{{ $version->version }}</span></td>
                            <td>
                                <div class="font-semibold">{{ $version->name }}</div>
                                <div class="text-xs text-slate-400">{{ $version->created_at->format('d/m/Y') }}</div>
                                <span style="display:none;">{{ $version->is_active ? 'aktif' : 'tidak aktif' }} SJT</span>
                            </td>
                            <td class="text-center">
                                @if($version->sjt_questions_count == 50)
                                    <span class="text-green-600 font-bold text-xs"><i class="fas fa-check"></i> 50</span>
                                @else
                                    <span class="text-orange-500 font-bold text-xs">{{ $version->sjt_questions_count }}/50</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="action-buttons">
                                    @if(Auth::user()->role === 'admin' && !$version->is_active)
                                        <button class="btn-activate" onclick="checkAndActivate('{{ $version->id }}', '{{ $version->name }}', 'SJT', {{ $version->sjt_questions_count }}, 50)">
                                            Gunakan
                                        </button>
                                    @endif

                                    <a href="{{ route('admin.questions.show', $version->id) }}" class="btn-icon btn-view" title="Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.questions.edit', $version->id) }}" class="btn-icon btn-edit" title="Edit">
                                            <i class="fas fa-pen text-xs"></i>
                                        </a>
                                        @if(!$version->is_active && !$version->hasResponses())
                                            <button class="btn-icon btn-delete" title="Hapus" onclick="deleteVersion('{{ $version->id }}', '{{ $version->name }}')">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-slate-400">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let searchTimeout;
    const searchInput = $('#versionSearch');
    const searchGroup = $('.search-group');
    const exportBtn = $('#btnExportPdf');
    const baseExportUrl = exportBtn.attr('href').split('?')[0];

    searchInput.on('keyup', function() {
        const query = $(this).val().toLowerCase();

        // Show Loading
        $('.loading-spinner').show();
        $('.search-icon').hide();

        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(() => {
            // Filter ST-30
            $('#st30Table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(query) > -1)
            });
            // Filter SJT
            $('#sjtTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(query) > -1)
            });

            // Update PDF Link
            if(query.trim() !== "") {
                exportBtn.attr('href', baseExportUrl + '?search=' + encodeURIComponent(query));
            } else {
                exportBtn.attr('href', baseExportUrl);
            }

            // Hide Loading
            $('.loading-spinner').hide();
            $('.search-icon').show();
        }, 300);
    });

    function checkAndActivate(id, name, type, currentCount, requiredCount) {
        if (currentCount < requiredCount) {
            Swal.fire({
                title: 'Tidak Dapat Diaktifkan',
                html: `Versi <b>${name}</b> belum memenuhi syarat (${currentCount}/${requiredCount} soal).`,
                icon: 'warning',
                confirmButtonColor: '#334155'
            });
        } else {
            Swal.fire({
                title: 'Aktifkan Versi?',
                html: `Versi <b>${name}</b> akan diaktifkan.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Ya, Gunakan'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.questions.activate", ":id") }}'.replace(':id', id);
                    form.innerHTML = '@csrf';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    }

    function deleteVersion(id, name) {
        Swal.fire({
            title: 'Hapus Versi?',
            html: `Yakin ingin menghapus <b>${name}</b>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.questions.destroy", ":id") }}'.replace(':id', id);
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush

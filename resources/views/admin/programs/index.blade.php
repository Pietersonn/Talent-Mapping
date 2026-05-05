@extends('admin.layouts.app')

@section('title', 'Manajemen Program')

@push('styles')
<style>
    /* --- STYLE TOMBOL --- */
    .btn-add { background: #22c55e; color: white; padding: 10px 20px; border-radius: 12px; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border: none; box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3); transition: all 0.2s; }
    .btn-add:hover { background: #16a34a; transform: translateY(-1px); color: white; }

    /* Tombol Print sebagai Link */
    .btn-print {
        width: 44px; height: 44px;
        background: white; border: 1px solid #e2e8f0;
        border-radius: 12px; display: flex; align-items: center; justify-content: center;
        color: #64748b; cursor: pointer; transition: all 0.2s; text-decoration: none;
    }
    .btn-print:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; transform: translateY(-1px); }

    /* --- SEARCH BAR --- */
    .search-group { position: relative; width: 300px; }
    .search-input { width: 100%; padding: 10px 12px 10px 40px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.875rem; background: white; transition: all 0.2s; }
    .search-input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1); }
    .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .loading-spinner { position: absolute; right: 12px; top: 33%; transform: translateY(-50%); color: #22c55e; display: none; }

    /* --- TABLE STYLES --- */
    .table-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { text-align: left; padding: 1.25rem; background: #f8fafc; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 1.25rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.9rem; color: #334155; background: white; }
    .custom-table tr:hover td { background-color: #f8fafc; }

    /* --- COMPONENTS --- */
    .program-info { display: flex; flex-direction: column; }
    .program-name { font-weight: 700; color: #0f172a; font-size: 0.95rem; }
    .program-code { font-family: monospace; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; width: fit-content; margin-top: 4px; }

    .status-dot { height: 8px; width: 8px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    .dot-active { background-color: #22c55e; }
    .dot-inactive { background-color: #ef4444; }

    .action-buttons { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .btn-view { background: #ecfdf5; color: #059669; }
    .btn-edit { background: #eff6ff; color: #2563eb; }
    .btn-delete { background: #fef2f2; color: #dc2626; }
    .btn-icon:hover { opacity: 0.8; transform: scale(1.05); }

    @media print {
        body { visibility: hidden; }
    }
</style>
@endpush

@section('header')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-calendar-alt" style="color: #22c55e; background: #dcfce7; padding: 10px; border-radius: 12px; font-size: 1.1rem;"></i>
                Manajemen Program
            </h1>
        </div>

        <div style="display: flex; gap: 12px; align-items: center;">
            <div class="search-group">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="realtimeSearch" class="search-input" placeholder="Cari Nama Program, Kode, Mitra..." autocomplete="off">
                <i class="fas fa-circle-notch fa-spin loading-spinner"></i>
            </div>

            <a href="{{ route('admin.Programs.export.pdf', request()->query()) ?? '#' }}" class="btn-print" id="btnExportPdf" title="Cetak PDF" target="_blank">
                <i class="fas fa-print"></i>
            </a>

            @if(Auth::user()->peran === 'admin')
                <a href="{{ route('admin.Programs.create') }}" class="btn-add">
                    <i class="fas fa-plus"></i> Tambah Program
                </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="table-card">
        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="30%">Nama Program</th>
                        <th width="30%">Perusahaan</th>
                        <th width="15%">Mitra</th>
                        <th width="10%">Peserta</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="programTableBody">
                    @forelse($Programs as $Program)
                        <tr>
                            <td>
                                <div class="program-info">
                                    <div class="program-name">
                                        @if($Program->aktif)
                                            <span class="status-dot dot-active" title="Aktif"></span>
                                        @else
                                            <span class="status-dot dot-inactive" title="Tidak Aktif"></span>
                                        @endif
                                        {{ $Program->nama }}
                                    </div>
                                    <div class="program-code">{{ $Program->kode_program }}</div>
                                </div>
                            </td>
                            <td style="color: #64748b; font-weight: 500;">{{ $Program->perusahaan ?? '-' }}</td>
                            <td style="color: #64748b;">{{ $Program->mitra->nama ?? 'Belum ada Mitra' }}</td>
                            <td>
                                <span style="font-weight: 700; color: #22c55e;">{{ $Program->participants_count }}</span>
                                <span style="color: #cbd5e1;">/</span>
                                <span style="color: #94a3b8;">{{ $Program->maks_peserta ?? '∞' }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.Programs.show', $Program->id) }}" class="btn-icon btn-view" title="Detail"><i class="fas fa-eye text-xs"></i></a>
                                    <a href="{{ route('admin.Programs.edit', $Program->id) }}" class="btn-icon btn-edit" title="Edit"><i class="fas fa-pen text-xs"></i></a>
                                    <button onclick="deleteProgram('{{ $Program->nama }}', '{{ route('admin.Programs.destroy', $Program->id) }}')" class="btn-icon btn-delete" title="Hapus"><i class="fas fa-trash text-xs"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center; padding: 3rem; color: #94a3b8;">Tidak ada data program ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        {{ $Programs->appends(request()->query())->links() }}
    </div>
@endsection

@push('scripts')
<script>
    let debounceTimer;

    // --- PENCARIAN REALTIME (AJAX) ---
    $('#realtimeSearch').on('input', function() {
        const query = $(this).val();
        $('.loading-spinner').show();
        $('.search-icon').hide();

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            $.ajax({
                url: "{{ route('admin.Programs.index') }}",
                type: "GET",
                data: { search: query },
                success: function(response) {
                    renderTable(response);
                    $('.loading-spinner').hide();
                    $('.search-icon').show();

                    // Update Export PDF jika route tersedia
                    updateExportUrl(query);
                },
                error: function() {
                    $('.loading-spinner').hide();
                    $('.search-icon').show();
                }
            });
        }, 500);
    });

    function updateExportUrl(searchQuery) {
        // Abaikan jika route export pdf tidak ada/masih disesuaikan
        let btnExport = document.getElementById('btnExportPdf');
        if(!btnExport || btnExport.getAttribute('href') === '#') return;

        let baseUrl = "{{ route('admin.Programs.export.pdf') ?? '#' }}";
        let params = new URLSearchParams(window.location.search);

        if (searchQuery) {
            params.set('search', searchQuery);
        } else {
            params.delete('search');
        }

        btnExport.setAttribute('href', baseUrl + "?" + params.toString());
    }

    function renderTable(response) {
        const tbody = $('#programTableBody');
        const programs = response.Programs.data;
        const isAdmin = response.is_admin;

        tbody.empty();

        if (programs.length === 0) {
            tbody.html('<tr><td colspan="5" style="text-align:center; padding: 3rem; color: #94a3b8;">Tidak ada data program ditemukan.</td></tr>');
            return;
        }

        let html = '';
        programs.forEach(program => {
            let dotClass = program.is_active ? 'dot-active' : 'dot-inactive';
            let editBtn = '';
            let deleteBtn = '';

            if (isAdmin) {
                editBtn = `<a href="${program.edit_url}" class="btn-icon btn-edit" title="Edit"><i class="fas fa-pen text-xs"></i></a>`;
                if (program.participants_count == 0) {
                    deleteBtn = `<button onclick="deleteProgram('${program.name}', '${program.delete_url}')" class="btn-icon btn-delete" title="Hapus"><i class="fas fa-trash text-xs"></i></button>`;
                }
            }

            let maxPart = program.max_participants ? program.max_participants : '∞';

            html += `<tr>
                <td>
                    <div class="program-info">
                        <div class="program-name">
                            <span class="status-dot ${dotClass}"></span>
                            ${program.name}
                        </div>
                        <div class="program-code">${program.Program_code}</div>
                    </div>
                </td>
                <td style="color: #64748b; font-weight: 500;">${program.company}</td>
                <td style="color: #64748b;">${program.mitra_name}</td>
                <td>
                    <span style="font-weight: 700; color: #22c55e;">${program.participants_count}</span>
                    <span style="color: #cbd5e1;">/</span>
                    <span style="color: #94a3b8;">${maxPart}</span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="${program.show_url}" class="btn-icon btn-view" title="Detail"><i class="fas fa-eye text-xs"></i></a>
                        ${editBtn}
                        ${deleteBtn}
                    </div>
                </td>
            </tr>`;
        });
        tbody.html(html);
    }

    function deleteProgram(name, url) {
        Swal.fire({
            title: 'Hapus Program?',
            html: `Yakin ingin menghapus program <b>${name}</b>?`,
            icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#f1f5f9',
            confirmButtonText: 'Ya, Hapus', cancelButtonText: '<span style="color:black">Batal</span>',
            customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl px-4 py-2', cancelButton: 'rounded-xl px-4 py-2' }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form'); form.method = 'POST'; form.action = url;
                form.innerHTML = '@csrf @method("DELETE")'; document.body.appendChild(form); form.submit();
            }
        });
    }
</script>
@endpush

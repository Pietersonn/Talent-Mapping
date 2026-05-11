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
    .table-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 1.5rem; }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { text-align: left; padding: 1.25rem; background: #f8fafc; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
    .custom-table td { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.9rem; color: #334155; background: white; }
    .custom-table tr:hover td { background-color: #f8fafc; }

    .status-dot { height: 8px; width: 8px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    .dot-active { background-color: #22c55e; }
    .dot-inactive { background-color: #cbd5e1; } /* Warna abu-abu untuk tidak aktif */

    .action-buttons { display: flex; gap: 6px; justify-content: flex-end; }
    .btn-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: none; transition: 0.2s; border: 1px solid transparent; }
    .btn-view { background: #ecfdf5; color: #059669; border-color: #d1fae5; }
    .btn-edit { background: #f0fdf4; color: #15803d; border-color: #dcfce7; }
    .btn-delete { background: #f8fafc; color: #64748b; border-color: #e2e8f0; } /* Warna abu-abu netral */
    .btn-icon:hover { opacity: 0.8; transform: scale(1.05); }

    /* --- PAGINATION (GREEN) --- */
    .pagination-wrapper { padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .btn-paginate { background: white; border: 1px solid #e2e8f0; color: #22c55e; padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 0.85rem; text-decoration: none; transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-paginate:hover:not(.disabled) { background: #f0fdf4; border-color: #22c55e; color: #15803d; transform: translateY(-1px); }
    .btn-paginate.disabled { color: #94a3b8; background: #f8fafc; cursor: not-allowed; opacity: 0.7; }
</style>
@endpush

@section('content')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-calendar-check" style="color: #22c55e; background: #dcfce7; padding: 10px; border-radius: 12px; font-size: 1.1rem;"></i>
                Manajemen Program
            </h1>
            <p style="font-size: 0.9rem; color: #64748b; margin-left: 54px; margin-top: -5px;">Daftar program assessment dan progress peserta.</p>
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="search-group">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="programSearch" class="search-input" placeholder="Cari program atau instansi..." autocomplete="off">
                <i class="fas fa-circle-notch fa-spin loading-spinner"></i>
            </div>

            <a href="{{ route('admin.programs.export.pdf') }}" id="btnExportPdf" target="_blank" class="btn-print" title="Export PDF">
                <i class="fas fa-print"></i>
            </a>

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.programs.create') }}" class="btn-tm btn-add"><i class="fas fa-plus"></i> Buat Program</a>
            @endif
        </div>
    </div>

    <div class="table-card">
        <div style="overflow-x: auto;">
            <table class="custom-table" id="programTable">
                <thead>
                    <tr>
                        <th width="35%">Nama Program / Instansi</th>
                        <th width="20%">Mitra (PIC)</th>
                        <th width="20%"><i class="far fa-calendar text-green-500 mr-1"></i> Jadwal Pelaksanaan</th>
                        <th width="10%"><i class="fas fa-users text-green-500 mr-1"></i> Kuota</th>
                        <th width="15%" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="programTableBody">
                    {{-- Diisi via AJAX --}}
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper" id="paginationWrapper">
            {{-- Diisi via AJAX --}}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let debounceTimer;
    const baseExportUrl = "{{ route('admin.programs.export.pdf') }}";

    $(document).ready(function() {
        fetchPrograms();
    });

    $('#programSearch').on('input', function() {
        const query = $(this).val();
        $('.loading-spinner').show();
        $('.search-icon').hide();

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetchPrograms(1, query);

            if (query.trim() !== "") {
                $('#btnExportPdf').attr('href', baseExportUrl + "?search=" + encodeURIComponent(query));
            } else {
                $('#btnExportPdf').attr('href', baseExportUrl);
            }
        }, 500);
    });

    // Handle klik pagination
    $(document).on('click', '.btn-paginate:not(.disabled)', function(e) {
        e.preventDefault();
        const url = new URL($(this).attr('href'));
        const page = url.searchParams.get('page');
        const search = $('#programSearch').val();
        fetchPrograms(page, search);
    });

    function fetchPrograms(page = 1, search = '') {
        $.ajax({
            url: "{{ route('admin.programs.index') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function(response) {
                renderTable(response);
                renderPagination(response.programs);
                $('.loading-spinner').hide();
                $('.search-icon').show();
            },
            error: function() {
                $('.loading-spinner').hide();
                $('.search-icon').show();
                $('#programTableBody').html('<tr><td colspan="5" style="text-align:center; padding: 2rem; color: #ef4444;">Gagal memuat data.</td></tr>');
            }
        });
    }

    function renderTable(response) {
        const tbody = $('#programTableBody');
        const programs = response.programs.data;
        const isAdmin = response.is_admin;

        tbody.empty();

        if (programs.length === 0) {
            tbody.html('<tr><td colspan="5" style="text-align:center; padding: 3rem; color: #94a3b8;">Tidak ada data program ditemukan.</td></tr>');
            return;
        }

        let html = '';
        programs.forEach(program => {
            let editBtn = isAdmin ? `<a href="${program.edit_url}" class="btn-icon btn-edit" title="Edit"><i class="fas fa-pen text-xs"></i></a>` : '';
            let deleteBtn = isAdmin ? `<button onclick="deleteProgram('${program.nama_program}', '${program.delete_url}')" class="btn-icon btn-delete" title="Hapus"><i class="fas fa-trash text-xs"></i></button>` : '';

            let maxPart = program.maks_peserta ? program.maks_peserta : '∞';
            let dotClass = program.aktif ? 'dot-active' : 'dot-inactive';
            let instansiStr = program.instansi ? program.instansi : '<span style="color:#cbd5e1; font-style:italic;">Tanpa Instansi</span>';

            html += `<tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                        <span class="status-dot ${dotClass}" title="${program.aktif ? 'Aktif' : 'Tidak Aktif'}"></span>
                        ${program.nama_program}
                    </div>
                    <div style="font-size: 0.8rem; color: #64748b; margin-left: 16px;">
                        <i class="far fa-building mr-1"></i> ${instansiStr}
                    </div>
                </td>
                <td>
                    <div style="font-weight: 600; color: #334155; font-size: 0.85rem;">${program.mitra_name}</div>
                    <div style="font-size: 0.75rem; color: #94a3b8;">${program.mitra_email}</div>
                </td>
                <td>
                    <div style="font-size: 0.85rem; color: #475569; background: #f8fafc; padding: 4px 8px; border-radius: 6px; display: inline-block; border: 1px solid #e2e8f0;">
                        ${program.tanggal_mulai_formatted} - ${program.tanggal_selesai_formatted}
                    </div>
                </td>
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

    function renderPagination(paginator) {
        const wrapper = $('#paginationWrapper');
        if (paginator.last_page <= 1) {
            wrapper.html('');
            return;
        }

        let prevClass = paginator.current_page === 1 ? 'btn-paginate disabled' : 'btn-paginate';
        let prevUrl = paginator.prev_page_url ? paginator.prev_page_url : '#';

        let nextClass = paginator.current_page === paginator.last_page ? 'btn-paginate disabled' : 'btn-paginate';
        let nextUrl = paginator.next_page_url ? paginator.next_page_url : '#';

        let html = `
            <div style="font-size: 0.85rem; color: #64748b;">Halaman <span style="font-weight: 700; color: #22c55e;">${paginator.current_page}</span> dari ${paginator.last_page}</div>
            <div style="display: flex; gap: 10px;">
                <a href="${prevUrl}" class="${prevClass}"><i class="fas fa-chevron-left mr-1"></i> Sebelumnya</a>
                <a href="${nextUrl}" class="${nextClass}">Selanjutnya <i class="fas fa-chevron-right ml-1"></i></a>
            </div>
        `;
        wrapper.html(html);
    }

    function deleteProgram(name, url) {
        Swal.fire({
            title: 'Hapus Program?',
            html: `Yakin ingin menghapus program <b>${name}</b>?`,
            icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#22c55e', cancelButtonColor: '#f1f5f9',
            confirmButtonText: '<span style="color:white">Ya, Hapus</span>', cancelButtonText: '<span style="color:black">Batal</span>',
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

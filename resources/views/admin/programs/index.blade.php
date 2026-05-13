@extends('admin.layouts.app')

@section('title', 'Manajemen Program')

@push('styles')
<style>
    /* --- STYLE TOMBOL --- */
    .btn-add { background: #22c55e; color: white; padding: 10px 20px; border-radius: 12px; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border: none; box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3); transition: 0.2s; }
    .btn-add:hover { background: #16a34a; transform: translateY(-1px); color: white; }
    .btn-print { width: 44px; height: 44px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: 0.2s; text-decoration: none; }
    .btn-print:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; }

    /* --- SEARCH BAR --- */
    .search-group { position: relative; width: 300px; }
    .search-input { width: 100%; padding: 10px 12px 10px 40px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.875rem; background: white; transition: 0.2s; }
    .search-input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1); }
    .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .loading-spinner { position: absolute; right: 12px; top: 33%; transform: translateY(-50%); color: #22c55e; display: none; }

    /* --- TABLE STYLES --- */
    .table-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { text-align: left; padding: 1.25rem; background: #f8fafc; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
    .custom-table td { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.9rem; color: #334155; }
    .custom-table tr:hover td { background-color: #f8fafc; }

    /* Helper Styles */
    .status-dot { height: 8px; width: 8px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    .dot-active { background-color: #22c55e; }
    .dot-inactive { background-color: #cbd5e1; }

    .badge-code { background: #f0fdf4; color: #166534; padding: 4px 8px; border-radius: 6px; font-family: monospace; font-weight: 700; border: 1px solid #bbf7d0; display: inline-block; }

    .badge-date { font-size: 0.85rem; color: #475569; background: #f8fafc; padding: 4px 8px; border-radius: 6px; display: inline-block; border: 1px solid #e2e8f0; }

    /* Mengunci Action Buttons (Horizontal & Nowrap) */
    .action-buttons { display: flex; flex-direction: row; flex-wrap: nowrap; gap: 6px; justify-content: flex-end; align-items: center; }
    .btn-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid transparent; transition: 0.2s; cursor: pointer; flex-shrink: 0; text-decoration: none; }
    .btn-view { background: #ecfdf5; color: #059669; border-color: #d1fae5; }
    .btn-edit { background: #f0fdf4; color: #15803d; border-color: #dcfce7; }
    .btn-delete { background: #fef2f2; color: #ef4444; border-color: #fecaca; }
    .btn-icon:hover { opacity: 0.8; transform: scale(1.05); }

    .pagination-wrapper { padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; background: white; }
</style>
@endpush

@section('content')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-calendar-check" style="color: #22c55e; background: #dcfce7; padding: 10px; border-radius: 12px;"></i>
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
            <a href="{{ route('admin.programs.export.pdf') }}" id="btnExportPdf" target="_blank" class="btn-print" title="Export PDF"><i class="fas fa-print"></i></a>
            @if(Auth::user()->peran === 'admin')
                <a href="{{ route('admin.programs.create') }}" class="btn-add"><i class="fas fa-plus"></i> Buat Program</a>
            @endif
        </div>
    </div>

    <div class="table-card">
        <div style="overflow-x: auto;">
            <table class="custom-table" id="programTable">
                <thead>
                    <tr>
                        <th width="30%">Nama Program & Instansi</th>
                        <th width="12%">Kode Akses</th>
                        <th width="18%">Mitra (PIC)</th>
                        <th width="15%">Jadwal Pelaksanaan</th>
                        <th width="12%" class="text-center">Peserta</th>
                        <th width="13%" class="text-right" style="min-width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="programTableBody">
                    {{-- Dirender oleh AJAX --}}
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper" id="paginationWrapper"></div>
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
                $('#programTableBody').html('<tr><td colspan="6" style="text-align:center; padding: 2rem; color: #ef4444;">Gagal memuat data.</td></tr>');
            }
        });
    }

    function renderTable(response) {
        const tbody = $('#programTableBody');
        const programs = response.programs.data;
        const isAdmin = response.is_admin;

        tbody.empty();

        if (programs.length === 0) {
            tbody.html('<tr><td colspan="6" style="text-align:center; padding: 3rem; color: #94a3b8;">Tidak ada data program ditemukan.</td></tr>');
            return;
        }

        let html = '';
        programs.forEach(p => {
            let editBtn = isAdmin ? `<a href="${p.edit_url}" class="btn-icon btn-edit" title="Edit"><i class="fas fa-pen text-xs"></i></a>` : '';
            let deleteBtn = isAdmin ? `<button type="button" onclick="confirmDelete('${p.id}', '${p.nama}')" class="btn-icon btn-delete" title="Hapus"><i class="fas fa-trash text-xs"></i></button>` : '';

            let maxPart = p.maks_peserta ? p.maks_peserta : '∞';
            let dotClass = p.aktif ? 'dot-active' : 'dot-inactive';
            let instansiStr = p.perusahaan ? p.perusahaan : '<span style="color:#cbd5e1; font-style:italic;">Tanpa Instansi</span>';

            html += `<tr>
                <td>
                    <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                        <span class="status-dot ${dotClass}" title="${p.aktif ? 'Aktif' : 'Tidak Aktif'}"></span>
                        ${p.nama}
                    </div>
                    <div style="font-size: 0.8rem; color: #64748b; margin-left: 14px;">
                        <i class="far fa-building mr-1"></i> ${instansiStr}
                    </div>
                </td>
                <td><span class="badge-code">${p.kode_program}</span></td>
                <td>
                    <div style="font-weight: 600; color: #334155; font-size: 0.85rem;">${p.mitra_name}</div>
                    <div style="font-size: 0.75rem; color: #94a3b8;">${p.mitra_email}</div>
                </td>
                <td>
                    <div class="badge-date">
                        ${p.tanggal_mulai_formatted} - ${p.tanggal_selesai_formatted}
                    </div>
                </td>
                <td style="text-align: center;">
                    <span style="font-weight: 700; color: #22c55e;">${p.participants_count}</span>
                    <span style="color: #cbd5e1; margin: 0 2px;">/</span>
                    <span style="color: #94a3b8; font-size: 0.85rem;">${maxPart}</span>
                </td>
                <td style="text-align: right;">
                    <div class="action-buttons">
                        <a href="${p.show_url}" class="btn-icon btn-view" title="Detail"><i class="fas fa-eye text-xs"></i></a>
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
                <a href="${prevUrl}" class="${prevClass}" style="padding: 8px 16px; border: 1px solid #e2e8f0; border-radius: 10px; text-decoration:none; color: #22c55e; font-weight: 600;"><i class="fas fa-chevron-left mr-1"></i> Sebelumnya</a>
                <a href="${nextUrl}" class="${nextClass}" style="padding: 8px 16px; border: 1px solid #e2e8f0; border-radius: 10px; text-decoration:none; color: #22c55e; font-weight: 600;">Selanjutnya <i class="fas fa-chevron-right ml-1"></i></a>
            </div>
        `;
        wrapper.html(html);
    }

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Program?',
            html: `Yakin ingin menghapus program <b>${name}</b>?`,
            icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#f1f5f9',
            confirmButtonText: 'Ya, Hapus', cancelButtonText: '<span style="color:black">Batal</span>',
            customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl px-4 py-2', cancelButton: 'rounded-xl px-4 py-2' }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('admin/programs') }}/${id}`;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush

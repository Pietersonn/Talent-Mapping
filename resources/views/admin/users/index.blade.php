@extends('admin.layouts.app')

@section('title', 'Manajemen User')

@push('styles')
<style>
    /* --- SEARCH & BUTTONS STYLE --- */
    .search-group { position: relative; width: 320px; }
    .search-input { width: 100%; height: 46px; padding: 10px 45px 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; background: #ffffff; transition: all 0.3s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); color: #334155; }
    .search-input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15); }
    .loading-spinner { position: absolute; right: 14px; top: 33%; transform: translateY(-50%); display: none; color: #22c55e; font-size: 1.1rem; pointer-events: none; }
    .search-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1rem; pointer-events: none; transition: opacity 0.2s; }

    .btn-print { width: 46px; height: 46px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all 0.2s; text-decoration: none; }
    .btn-print:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; transform: translateY(-1px); }

    .btn-add { height: 46px; padding: 0 24px; background: #22c55e; color: white; border-radius: 12px; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; border: none; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3); transition: all 0.2s; }
    .btn-add:hover { background: #16a34a; transform: translateY(-1px); }

    /* --- TABLE STYLE --- */
    .table-card { background: white; border: 1px solid #f1f5f9; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { text-align: left; padding: 1.25rem; background: #f8fafc; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 1.25rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.9rem; color: #334155; background: white; }
    .custom-table tr:hover td { background-color: #f8fafc; }

    .status-dot { height: 8px; width: 8px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    .dot-active { background-color: #22c55e; }
    .dot-inactive { background-color: #ef4444; }

    .badge-role { padding: 6px 12px; border-radius: 99px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
    .role-admin { background: #f3e8ff; color: #7e22ce; }
    .role-user { background: #f1f5f9; color: #475569; }
    .role-pic { background: #ffedd5; color: #c2410c; }
    .role-staff { background: #e0f2fe; color: #0369a1; }

    .action-buttons { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .btn-view { background: #ecfdf5; color: #059669; }
    .btn-edit { background: #eff6ff; color: #2563eb; }
    .btn-delete { background: #fef2f2; color: #dc2626; }
    .btn-icon:hover { opacity: 0.8; transform: scale(1.05); }

    @media print { body { display: none; } }
</style>
@endpush

@section('header')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                <i class="fas fa-users" style="color: #22c55e; background: #dcfce7; padding: 8px; border-radius: 8px; margin-right: 8px;"></i>
                Manajemen Pengguna
            </h1>
        </div>

        <div style="display: flex; gap: 12px; align-items: center;">
            <div class="search-group">
                <input type="text" id="realtimeSearch" class="search-input" placeholder="Cari data..." autocomplete="off">
                <i class="fas fa-search search-icon"></i>
                <i class="fas fa-circle-notch fa-spin loading-spinner"></i>
            </div>

            <a href="{{ route('admin.users.export.pdf', request()->query()) }}"
               class="btn-print"
               id="btnExportPdf"
               target="_blank"
               title="Cetak PDF Laporan">
                <i class="fas fa-print"></i>
            </a>

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.users.create') }}" class="btn-add">
                    <i class="fas fa-plus"></i>
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
                        <th width="30%">Nama</th>
                        <th width="25%">Email</th>
                        <th width="15%">Peran</th>
                        <th width="15%">Kontak</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #0f172a;">
                                    @if($user->is_active)
                                        <span class="status-dot dot-active" title="Aktif"></span>
                                    @else
                                        <span class="status-dot dot-inactive" title="Tidak Aktif"></span>
                                    @endif
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td style="color: #64748b;">{{ $user->email }}</td>
                            <td><span class="badge-role role-{{ strtolower($user->role) }}">{{ $user->role === 'user' ? 'Pengguna' : ucfirst($user->role) }}</span></td>
                            <td style="font-family: monospace; font-weight: 600; color: #334155;">{{ $user->phone_number ?? '-' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn-icon btn-view"><i class="fas fa-eye text-xs"></i></a>
                                    @if(Auth::user()->role === 'admin' || Auth::id() === $user->id)
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-icon btn-edit"><i class="fas fa-pen text-xs"></i></a>
                                    @endif
                                    @if(Auth::user()->role === 'admin' && Auth::id() !== $user->id)
                                        <button onclick="deleteUser('{{ $user->name }}', '{{ route('admin.users.destroy', $user->id) }}')" class="btn-icon btn-delete"><i class="fas fa-trash text-xs"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center; padding: 3rem; color: #94a3b8;">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        {{ $users->appends(request()->query())->links() }}
    </div>
@endsection

@push('scripts')
<script>
    // --- SEARCH REALTIME ---
    let debounceTimer;
    const searchInput = $('#realtimeSearch');
    const searchGroup = $('.search-group');
    const exportBtn = $('#btnExportPdf');
    // Base URL tanpa query params
    const baseExportUrl = "{{ route('admin.users.export.pdf') }}";

    searchInput.on('input', function() {
        const query = $(this).val();

        // Show Spinner, Hide Search Icon
        $('.loading-spinner').show();
        $('.search-icon').hide();

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            $.ajax({
                url: "{{ route('admin.users.index') }}",
                type: "GET",
                data: { search: query },
                success: function(response) {
                    renderTable(response);

                    // Hide Spinner, Show Search Icon
                    $('.loading-spinner').hide();
                    $('.search-icon').show();

                    // Update Link PDF
                    if (query.trim() !== "") {
                        exportBtn.attr('href', baseExportUrl + "?search=" + encodeURIComponent(query));
                    } else {
                        exportBtn.attr('href', baseExportUrl);
                    }
                },
                error: function() {
                    $('.loading-spinner').hide();
                    $('.search-icon').show();
                }
            });
        }, 500);
    });

    function renderTable(response) {
        const tbody = $('#userTableBody');
        const users = response.users.data;
        const currentUserId = response.current_user_id;
        const isAdmin = response.is_admin;

        tbody.empty();

        if (users.length === 0) {
            tbody.html('<tr><td colspan="5" style="text-align:center; padding: 3rem; color: #94a3b8;">Tidak ada data user ditemukan.</td></tr>');
            return;
        }

        let html = '';
        users.forEach(user => {
            const canEdit = isAdmin || currentUserId === user.id;
            const canDelete = isAdmin && currentUserId !== user.id;

            let editBtn = canEdit ? `<a href="${user.edit_url}" class="btn-icon btn-edit"><i class="fas fa-pen text-xs"></i></a>` : '';
            let deleteBtn = canDelete ? `<button onclick="deleteUser('${user.name}', '${user.delete_url}')" class="btn-icon btn-delete"><i class="fas fa-trash text-xs"></i></button>` : '';
            let phone = user.phone_number ? user.phone_number : '-';
            let dotClass = user.is_active ? 'dot-active' : 'dot-inactive';

            html += `<tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #0f172a;">
                        <span class="status-dot ${dotClass}"></span>
                        ${user.name}
                    </div>
                </td>
                <td style="color: #64748b;">${user.email}</td>
                <td><span class="badge-role role-${user.role.toLowerCase()}">${user.role}</span></td>
                <td style="font-family: monospace; font-weight: 600; color: #334155;">${phone}</td>
                <td><div class="action-buttons">
                    <a href="${user.show_url}" class="btn-icon btn-view"><i class="fas fa-eye text-xs"></i></a>
                    ${editBtn}${deleteBtn}
                </div></td>
            </tr>`;
        });
        tbody.html(html);
    }

    function deleteUser(name, url) {
        Swal.fire({
            title: 'Hapus User?', html: `Yakin ingin menghapus <b>${name}</b>?`, icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#f1f5f9',
            confirmButtonText: 'Ya', cancelButtonText: '<span style="color:black">Batal</span>',
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

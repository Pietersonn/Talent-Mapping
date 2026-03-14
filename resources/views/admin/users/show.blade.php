@extends('admin.layouts.app')

@section('title', 'Profil Pengguna')

@push('styles')
<style>
    :root { --primary: #22c55e; --secondary: #64748b; --bg-card: #ffffff; --border: #e2e8f0; --radius: 12px; }
    .page-inner { padding-bottom: 2rem; }

    /* Header Profile */
    .profile-header { background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius); padding: 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .profile-left { display: flex; align-items: center; gap: 1.5rem; }
    .profile-avatar { width: 64px; height: 64px; background: #f1f5f9; color: #475569; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; border: 1px solid #e2e8f0; }
    .profile-name { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0; }
    .profile-email { color: var(--secondary); font-size: 0.9rem; }

    /* Grid Layout */
    .dashboard-grid { display: grid; grid-template-columns: 300px 1fr; gap: 1.5rem; }
    @media (max-width: 900px) { .dashboard-grid { grid-template-columns: 1fr; } }

    /* Cards */
    .bento-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.5rem; display: flex; flex-direction: column; height: 100%; }
    .card-title { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px; }
    .card-title i { color: var(--primary); }

    /* Info List */
    .info-item { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px dashed #f1f5f9; font-size: 0.875rem; }
    .info-label { color: var(--secondary); }
    .info-value { font-weight: 600; color: #0f172a; }

    /* Stats Grid */
    .stats-container { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
    .stat-card { background: #f8fafc; padding: 1rem; border-radius: 10px; border: 1px solid #e2e8f0; text-align: center; }
    .stat-val { font-size: 1.5rem; font-weight: 800; color: #0f172a; }
    .stat-label { font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; }

    /* Table in Card */
    .clean-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .clean-table th { text-align: left; padding: 0.75rem; color: #64748b; font-weight: 600; background: #f8fafc; }
    .clean-table td { padding: 0.75rem; border-bottom: 1px dashed #f1f5f9; color: #334155; }
    .badge-status { padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 700; }
    .status-completed { background: #dcfce7; color: #166534; }
    .status-pending { background: #fef9c3; color: #854d0e; }

    /* Action Buttons */
    .btn-action { padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
    .btn-edit { background: #eff6ff; color: #2563eb; }
    .btn-toggle { background: #fefce8; color: #ca8a04; }
    .btn-reset { background: #e0f2fe; color: #0369a1; }
    .btn-delete { background: #fef2f2; color: #dc2626; }
    .btn-action:hover { opacity: 0.9; transform: translateY(-1px); }
</style>
@endpush

@section('content')
<div class="page-inner">

    <div class="profile-header">
        <div class="profile-left">
            <div class="profile-avatar">{{ substr($user->name, 0, 1) }}</div>
            <div>
                <h1 class="profile-name">{{ $user->name }}</h1>
                <div class="profile-email">
                    <i class="fas fa-envelope text-gray-400 mr-1"></i> {{ $user->email }}
                    <span style="margin: 0 8px; color: #e2e8f0;">|</span>
                    <span style="font-weight: 600; color: var(--primary);">{{ ucfirst($user->role) }}</span>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 8px;">
            @if(Auth::user()->role === 'admin' || Auth::id() === $user->id)
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-pen"></i> Edit
                </a>
            @endif
            @if(Auth::user()->role === 'admin' && Auth::id() !== $user->id)
                 <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" style="display:inline;">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-action btn-toggle" title="Ubah Status Aktif">
                        <i class="fas fa-power-off"></i> {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>

                <button onclick="deleteUser()" class="btn-action btn-delete">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            @endif
        </div>
    </div>

    <div class="dashboard-grid">

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="bento-card">
                <div class="card-title"><i class="fas fa-info-circle"></i> Detail Akun</div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        @if($user->is_active)
                            <span style="color: #166534; background: #dcfce7; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem;">AKTIF</span>
                        @else
                            <span style="color: #991b1b; background: #fee2e2; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem;">NONAKTIF</span>
                        @endif
                    </span>
                </div>
                <div class="info-item"><span class="info-label">No. Telepon</span><span class="info-value">{{ $user->phone_number ?? '-' }}</span></div>
                <div class="info-item"><span class="info-label">Bergabung</span><span class="info-value">{{ $user->created_at->translatedFormat('d M Y') }}</span></div>
                <div class="info-item"><span class="info-label">Umur Akun</span><span class="info-value">{{ $stats['account_age'] }}</span></div>
            </div>
        </div>

        <div>
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-val">{{ $stats['total_test_sessions'] }}</div>
                    <div class="stat-label">Total Tes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-val" style="color: #22c55e;">{{ $stats['completed_tests'] }}</div>
                    <div class="stat-label">Selesai</div>
                </div>
                <div class="stat-card">
                    <div class="stat-val" style="color: #eab308;">{{ $stats['events_as_pic'] }}</div>
                    <div class="stat-label">Event (PIC)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-val">{{ $user->resendRequests->count() }}</div>
                    <div class="stat-label">Permintaan</div>
                </div>
            </div>

            <div class="bento-card">
                <div class="card-title"><i class="fas fa-history"></i> Aktivitas Tes Terbaru</div>
                <div style="overflow-x: auto;">
                    <table class="clean-table">
                        <thead>
                            <tr>
                                <th>Event / Sesi</th>
                                <th>Status</th>
                                <th style="text-align: right;">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->testSessions->take(5) as $session)
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;">{{ $session->event->name ?? 'Event Tidak Dikenal' }}</div>
                                        <div style="font-size: 0.75rem; color: #94a3b8; font-family: monospace;">{{ $session->session_token }}</div>
                                    </td>
                                    <td>
                                        <span class="badge-status {{ $session->is_completed ? 'status-completed' : 'status-pending' }}">
                                            {{ $session->is_completed ? 'SELESAI' : 'PROSES' }}
                                        </span>
                                    </td>
                                    <td style="text-align: right; color: #64748b;">
                                        {{ $session->created_at->format('d M, H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" style="text-align:center; padding: 1rem; color: #94a3b8;">Tidak ada aktivitas ditemukan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function deleteUser() {
        Swal.fire({
            title: 'Hapus Pengguna Ini?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = "{{ route('admin.users.destroy', $user->id) }}";
                form.method = 'POST';
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush

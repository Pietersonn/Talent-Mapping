@extends('admin.layouts.app')

@section('title', 'Detail Tipologi')

@push('styles')
<style>
    /* --- VARIABLES (Mengikuti Style SJT) --- */
    :root {
        --text-main: #0f172a;
        --text-sub: #64748b;
        --bg-surface: #ffffff;
        --bg-subtle: #f8fafc;
        --border-color: #e2e8f0;
        --radius-lg: 16px;
        --radius-md: 12px;
        --tm-green: #22c55e;
        --tm-green-soft: #dcfce7;
        --tm-green-dark: #15803d;
    }

    /* --- LAYOUT GRID --- */
    .bento-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }

    /* --- CARDS --- */
    .bento-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        height: 100%;
        display: flex; flex-direction: column;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .bento-title {
        font-size: 0.85rem; font-weight: 700; color: var(--text-sub);
        text-transform: uppercase; letter-spacing: 0.05em;
        margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;
        border-bottom: 1px dashed var(--border-color); padding-bottom: 0.5rem;
    }

    /* --- HERO SECTION (Nama & Kode) --- */
    .typo-hero {
        display: flex; align-items: center; gap: 1.25rem;
        padding: 1.5rem; background: var(--bg-subtle);
        border-radius: var(--radius-md); border-left: 5px solid var(--tm-green);
        margin-bottom: 2rem;
    }
    .typo-code-display {
        font-family: monospace; font-size: 2rem; font-weight: 800;
        color: var(--text-main); letter-spacing: -1px;
    }
    .typo-name-display {
        font-size: 1.25rem; font-weight: 700; color: var(--text-main);
        line-height: 1.2;
    }

    /* --- DESCRIPTION BOXES --- */
    .desc-box {
        margin-bottom: 1.5rem;
    }
    .desc-label {
        font-size: 0.9rem; font-weight: 700; margin-bottom: 0.5rem;
        display: flex; align-items: center; gap: 8px;
    }
    .desc-label.strength { color: #15803d; }
    .desc-label.weakness { color: #b91c1c; }

    .desc-content {
        font-size: 0.95rem; line-height: 1.6; color: #334155;
        background: #fff; border: 1px solid var(--border-color);
        padding: 1rem; border-radius: 12px;
    }
    .desc-content.bg-green { background: #f0fdf4; border-color: #bbf7d0; }
    .desc-content.bg-red { background: #fef2f2; border-color: #fecaca; }

    /* --- SIDEBAR META --- */
    .meta-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0; border-bottom: 1px solid var(--bg-subtle); font-size: 0.85rem;
    }
    .meta-row:last-child { border-bottom: none; }
    .mr-label { color: var(--text-sub); font-weight: 600; }
    .mr-val { color: var(--text-main); font-weight: 600; }

    /* --- BUTTONS --- */
    .btn-cancel {
        background: white; color: #64748b; border: 1px solid #e2e8f0;
        padding: 10px 24px; border-radius: 12px; font-weight: 600;
        text-decoration: none; transition: all 0.2s;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; }

    .action-btn-group { display: flex; gap: 8px; margin-top: auto; }
    .btn-act { flex: 1; padding: 10px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; text-align: center; border: none; cursor: pointer; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 6px; transition: 0.2s; }

    .act-edit { background: #eff6ff; color: #2563eb; }
    .act-edit:hover { background: #dbeafe; }

    .act-del { background: #fef2f2; color: #ef4444; }
    .act-del:hover { background: #fee2e2; }

    /* --- TABLE (Related Questions) --- */
    .related-section { margin-top: 1.5rem; }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { text-align: left; padding: 1rem; background: #f8fafc; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; color: #334155; background: white; }
    .custom-table tr:hover td { background-color: #f8fafc; }

    @media (max-width: 768px) { .bento-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('header')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-shapes" style="color: #22c55e; background: #dcfce7; padding: 8px; border-radius: 10px;"></i>
                Detail Tipologi
            </h1>
        </div>
        <div>
            <a href="{{ route('admin.questions.typologies.index') }}" class="btn-cancel">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="bento-grid">

    <div class="bento-card">
        <div class="bento-title"><i class="fas fa-id-card text-green-500"></i> Identitas Tipologi</div>

        <div class="typo-hero">
            <div class="typo-code-display">{{ $typology->typology_code }}</div>
            <div style="width: 1px; height: 40px; background: #cbd5e1;"></div>
            <div class="typo-name-display">{{ $typology->typology_name }}</div>
        </div>

        <div style="margin-top: 1rem;">
            <div class="desc-box">
                <div class="desc-label strength">
                    <i class="fas fa-bolt"></i> Kekuatan (Strength)
                </div>
                <div class="desc-content bg-green">
                    {!! nl2br(e($typology->strength_description)) ?? '<span class="text-muted italic">Tidak ada deskripsi.</span>' !!}
                </div>
            </div>

            <div class="desc-box">
                <div class="desc-label weakness">
                    <i class="fas fa-exclamation-circle"></i> Kelemahan (Weakness)
                </div>
                <div class="desc-content bg-red">
                    {!! nl2br(e($typology->weakness_description)) ?? '<span class="text-muted italic">Tidak ada deskripsi.</span>' !!}
                </div>
            </div>
        </div>
    </div>

    <div class="bento-card">
        <div class="bento-title"><i class="fas fa-info-circle text-gray-500"></i> Meta Data</div>

        <div style="margin-bottom: 2rem;">
            <div class="meta-row">
                <span class="mr-label">ID Database</span>
                <span class="mr-val font-mono text-xs bg-gray-100 px-2 rounded">{{ $typology->id }}</span>
            </div>
            <div class="meta-row">
                <span class="mr-label">Dibuat Pada</span>
                <span class="mr-val text-xs">{{ $typology->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="meta-row">
                <span class="mr-label">Update Terakhir</span>
                <span class="mr-val text-xs">{{ $typology->updated_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="meta-row" style="margin-top: 10px; border-top: 1px dashed #e2e8f0; padding-top: 10px;">
                <span class="mr-label">Penggunaan (Soal)</span>
                @php
                    // Cek safety jika relasi belum didefinisikan di Model
                    $count = method_exists($typology, 'st30Questions') ? $typology->st30Questions()->count() : 0;
                @endphp
                <span class="mr-val text-success">{{ $count }} Soal</span>
            </div>
        </div>

        @if (Auth::user()->role === 'admin')
            <div class="action-btn-group">
                <a href="{{ route('admin.questions.typologies.edit', $typology->id) }}" class="btn-act act-edit">
                    <i class="fas fa-pen"></i> Edit
                </a>
                <button onclick="confirmDelete('{{ $typology->typology_name }}', '{{ route('admin.questions.typologies.destroy', $typology->id) }}')"
                        class="btn-act act-del">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
        @endif
    </div>

</div>

@if(method_exists($typology, 'st30Questions') && $typology->st30Questions()->count() > 0)
<div class="related-section">
    <div class="bento-card" style="padding: 0; overflow: hidden;">
        <div style="padding: 1.25rem; border-bottom: 1px solid var(--border-color); background: #fcfcfc;">
            <h5 style="margin:0; font-size: 1rem; font-weight: 700; color: var(--text-main);">
                <i class="fas fa-list-ul mr-2 text-primary"></i> Daftar Soal Terkait (ST-30)
            </h5>
        </div>
        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="80">No.</th>
                        <th>Pernyataan Soal</th>
                        <th width="100">Status</th>
                        <th width="80" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($typology->st30Questions as $q)
                    <tr>
                        <td style="font-family: monospace; font-weight: 600; color: #64748b;">
                            #{{ str_pad($q->number, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td>{{ Str::limit($q->statement, 100) }}</td>
                        <td>
                            @if($q->is_active ?? true)
                                <span style="background:#dcfce7; color:#15803d; padding:2px 8px; border-radius:6px; font-size:0.75rem; font-weight:700;">aktif</span>
                            @else
                                <span style="background:#f1f5f9; color:#64748b; padding:2px 8px; border-radius:6px; font-size:0.75rem; font-weight:700;">tidak aktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.questions.st30.show', $q->id) }}" style="color: #2563eb;">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    function confirmDelete(name, url) {
        Swal.fire({
            title: 'Hapus Tipologi?',
            html: `Yakin ingin menghapus tipologi <b>"${name}"</b>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#f1f5f9',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: '<span style="color:#0f172a">Batal</span>',
            customClass: { popup: 'rounded-xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST'; form.action = url;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush

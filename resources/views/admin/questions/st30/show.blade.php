@extends('admin.layouts.app')

@section('title', 'Detail Soal ST-30')

@push('styles')
<style>
    /* --- VARIABLES --- */
    :root {
        --text-main: #0f172a;
        --text-sub: #64748b;
        --bg-surface: #ffffff;
        --bg-subtle: #f8fafc;
        --border-color: #e2e8f0;
        --radius-lg: 16px;
        --radius-md: 12px;
        --tm-green: #22c55e; /* Talent Mapping Green */
        --tm-green-soft: #dcfce7;
        --tm-green-dark: #15803d;
    }

    /* --- BENTO GRID LAYOUT --- */
    .bento-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    .bento-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        height: 100%;
        display: flex; flex-direction: column;
        transition: transform 0.2s;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .bento-title {
        font-size: 0.85rem; font-weight: 700; color: var(--text-sub);
        text-transform: uppercase; letter-spacing: 0.05em;
        margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;
        border-bottom: 1px dashed var(--border-color);
        padding-bottom: 0.5rem;
    }

    /* Statement Box (Revised: Smaller Font, TM Green) */
    .statement-hero {
        font-size: 1rem; /* Ukuran font pas (16px) */
        line-height: 1.6;
        color: #334155;
        font-weight: 500;
        padding: 1.25rem;
        background: var(--bg-subtle);
        border-radius: var(--radius-md);
        border-left: 4px solid var(--tm-green);
        position: relative;
    }
    .btn-copy-abs {
        position: absolute; top: 10px; right: 10px; color: #94a3b8; cursor: pointer; transition: 0.2s;
    }
    .btn-copy-abs:hover { color: var(--tm-green); }

    /* Typology Section (Revised: Green Theme) */
    .typo-visual {
        display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;
        padding-bottom: 1rem; border-bottom: 1px dashed var(--border-color);
    }
    .typo-code {
        width: 48px; height: 48px; /* Lebih kecil */
        background: var(--tm-green); color: white;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; font-weight: 800; /* Font kode lebih kecil */
        box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.2);
    }
    .typo-name {
        font-weight: 700; font-size: 1rem; /* Judul lebih kecil */
        color: var(--tm-green-dark); /* Hijau Tua */
        margin: 0;
    }
    .typo-tag { font-size: 0.75rem; color: var(--text-sub); font-weight: 500; }

    .traits-split {
        display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
    }
    .trait-item {
        padding: 12px; border-radius: var(--radius-md); border: 1px solid transparent;
    }
    .t-green { background: #f0fdf4; border-color: #d1fae5; }
    .t-green h6 { color: #15803d; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
    .t-green p { color: #14532d; font-size: 0.85rem; margin: 0; line-height: 1.4; } /* Font isi lebih kecil */

    .t-orange { background: #fffbeb; border-color: #fef3c7; }
    .t-orange h6 { color: #b45309; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
    .t-orange p { color: #78350f; font-size: 0.85rem; margin: 0; line-height: 1.4; }

    /* Meta Sidebar */
    .meta-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0; border-bottom: 1px solid var(--bg-subtle); font-size: 0.85rem;
    }
    .meta-row:last-child { border-bottom: none; }
    .mr-label { color: var(--text-sub); font-weight: 600; }
    .mr-val { color: var(--text-main); font-weight: 600; }

    .status-active { color: #15803d; background: #dcfce7; padding: 2px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; }
    .status-inactive { color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; }

    /* Buttons (Same as Create/Edit) */
    .btn-cancel {
        background: white; color: #64748b; border: 1px solid #e2e8f0;
        padding: 10px 24px; border-radius: 12px; font-weight: 600;
        text-decoration: none; transition: all 0.2s;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; }

    .btn-nav-icon {
        width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
        border-radius: 8px; background: var(--bg-subtle); color: var(--text-sub);
        transition: 0.2s; border: 1px solid #e2e8f0; text-decoration: none;
    }
    .btn-nav-icon:hover:not(.disabled) { border-color: var(--tm-green); color: var(--tm-green); background: #f0fdf4; }
    .btn-nav-icon.disabled { opacity: 0.5; cursor: default; }

    .action-btn-group { display: flex; gap: 8px; margin-top: auto; }
    .btn-act { flex: 1; padding: 8px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-align: center; border: none; cursor: pointer; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 6px; transition: 0.2s; }
    .act-edit { background: #eff6ff; color: #2563eb; }
    .act-edit:hover { background: #dbeafe; }
    .act-del { background: #fef2f2; color: #ef4444; }
    .act-del:hover { background: #fee2e2; }

    /* Responsive */
    @media (max-width: 768px) {
        .bento-grid { grid-template-columns: 1fr; }
        .traits-split { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('header')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-file-alt" style="color: #22c55e; background: #dcfce7; padding: 8px; border-radius: 10px;"></i>
                Detail Pertanyaan ST-30
            </h1>
            <div style="font-size: 0.9rem; color: #64748b; margin-left: 44px;">
                Lihat rincian pernyataan, tipologi, dan statistik penggunaan.
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="display: flex; gap: 4px;">
                @if (isset($prevQuestion))
                    <a href="{{ route('admin.questions.st30.show', $prevQuestion) }}" class="btn-nav-icon" title="Sebelumnya"><i class="fas fa-chevron-left text-xs"></i></a>
                @else
                    <span class="btn-nav-icon disabled"><i class="fas fa-chevron-left text-xs"></i></span>
                @endif

                @if (isset($nextQuestion))
                    <a href="{{ route('admin.questions.st30.show', $nextQuestion) }}" class="btn-nav-icon" title="Selanjutnya"><i class="fas fa-chevron-right text-xs"></i></a>
                @else
                    <span class="btn-nav-icon disabled"><i class="fas fa-chevron-right text-xs"></i></span>
                @endif
            </div>

            <a href="{{ route('admin.questions.st30.index', ['version' => $st30Question->version_id]) }}" class="btn-cancel">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="bento-grid">

    <div class="bento-card">
        <div class="bento-title"><i class="fas fa-file-alt text-green-500"></i> Pernyataan Soal</div>

        <div class="statement-hero">
            <i class="far fa-copy btn-copy-abs" onclick="copyToClipboard('{{ addslashes($st30Question->statement) }}')" title="Salin"></i>
            "{{ $st30Question->statement }}"
        </div>

        <div style="margin-top: 2rem;">
            <div class="bento-title"><i class="fas fa-fingerprint text-blue-500"></i> Analisis Tipologi</div>

            @if ($st30Question->typologyDescription)
                <div class="typo-visual">
                    <div class="typo-code">{{ $st30Question->typology_code }}</div>
                    <div>
                        <h4 class="typo-name">{{ $st30Question->typologyDescription->typology_name }}</h4>
                        <span class="typo-tag">Personality Trait</span>
                    </div>
                </div>

                <div class="traits-split">
                    <div class="trait-item t-green">
                        <h6><i class="fas fa-bolt"></i> Kekuatan</h6>
                        <p>{{ $st30Question->typologyDescription->strength_description }}</p>
                    </div>
                    <div class="trait-item t-orange">
                        <h6><i class="fas fa-exclamation-triangle"></i> Kelemahan</h6>
                        <p>{{ $st30Question->typologyDescription->weakness_description }}</p>
                    </div>
                </div>
            @else
                <div class="alert alert-light text-center border text-sm text-muted">
                    Data tipologi belum tersedia.
                </div>
            @endif
        </div>
    </div>

    <div class="bento-card">
        <div class="bento-title"><i class="fas fa-info-circle text-gray-500"></i> Meta Data</div>

        <div style="margin-bottom: 2rem;">
            <div class="meta-row">
                <span class="mr-label">ID Database</span>
                <span class="mr-val font-mono text-xs bg-gray-100 px-2 rounded">{{ $st30Question->id }}</span>
            </div>
            <div class="meta-row">
                <span class="mr-label">Nomor Urut</span>
                <span class="mr-val text-primary font-bold">#{{ $st30Question->number }}</span>
            </div>
            <div class="meta-row">
                <span class="mr-label">Status Versi</span>
                <span class="mr-val">
                    @if($st30Question->questionVersion->is_active)
                        <span class="status-active">AKTIF</span>
                    @else
                        <span class="status-inactive">NON-AKTIF</span>
                    @endif
                </span>
            </div>
            <div class="meta-row">
                <span class="mr-label">Penggunaan</span>
                <span class="mr-val text-success">{{ $st30Question->usage_count }}x</span>
            </div>
            <div class="meta-row">
                <span class="mr-label">Dibuat</span>
                <span class="mr-val text-xs">{{ $st30Question->created_at->format('d M Y') }}</span>
            </div>
            <div class="meta-row">
                <span class="mr-label">Diupdate</span>
                <span class="mr-val text-xs">{{ $st30Question->updated_at->format('d M Y') }}</span>
            </div>
        </div>

        @if (Auth::user()->role === 'admin')
            <div class="action-btn-group">
                <a href="{{ route('admin.questions.st30.edit', $st30Question) }}" class="btn-act act-edit">
                    <i class="fas fa-pen"></i> Edit
                </a>
                <button onclick="confirmDelete('{{ $st30Question->number }}', '{{ route('admin.questions.st30.destroy', $st30Question) }}')"
                        class="btn-act act-del">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
            <div class="text-center mt-3">
                <small class="text-muted" style="font-size: 0.7rem;">Shortcut: <strong>Ctrl+E</strong></small>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.querySelector('.btn-copy-abs');
            btn.classList.remove('far', 'fa-copy');
            btn.classList.add('fas', 'fa-check', 'text-success');
            setTimeout(() => {
                btn.classList.remove('fas', 'fa-check', 'text-success');
                btn.classList.add('far', 'fa-copy');
            }, 1500);
        });
    }

    function confirmDelete(num, url) {
        Swal.fire({
            title: 'Hapus Soal?',
            html: `Hapus permanen soal <b>#${num}</b>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', cancelButtonColor: '#f1f5f9',
            confirmButtonText: 'Ya, Hapus', cancelButtonText: '<span style="color:#0f172a">Batal</span>',
            customClass: { popup: 'rounded-xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST'; form.action = url;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form); form.submit();
            }
        });
    }

    $(document).keydown(function(e) {
        if ((e.ctrlKey || e.metaKey) && e.which === 69) { // Ctrl+E
            e.preventDefault();
            @if(Auth::user()->role === 'admin') window.location.href = '{{ route('admin.questions.st30.edit', $st30Question) }}'; @endif
        }
    });
</script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Detail Tipologi')

@push('styles')
<style>
    .bento-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
    .bento-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .bento-title { font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; }
    .hero-box { background: #f8fafc; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 20px; border-left: 6px solid #22c55e; margin-bottom: 25px; }
    .code-display { font-size: 2.5rem; font-weight: 900; color: #334155; font-family: monospace; }
    .name-display { font-size: 1.25rem; font-weight: 700; color: #0f172a; }

    .desc-box { margin-bottom: 1.5rem; }
    .desc-label { font-size: 0.9rem; font-weight: 700; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 8px; }
    .desc-content { padding: 12px 15px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 0.95rem; line-height: 1.6; color: #334155; }

    .bg-strength { background: #f0fdf4; border-color: #bbf7d0; color: #14532d; }
    .bg-weakness { background: #fef2f2; border-color: #fecaca; color: #7f1d1d; }

    .btn-act { width: 100%; padding: 10px; border-radius: 10px; font-weight: 600; text-align: center; text-decoration: none; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s; border: none; cursor: pointer; }
    .act-edit { background: #eff6ff; color: #2563eb; }
    .act-edit:hover { background: #dbeafe; }
    .act-del { background: #fef2f2; color: #ef4444; }
    .act-del:hover { background: #fee2e2; }
</style>
@endpush

@section('content')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-fingerprint" style="color: #22c55e; background: #dcfce7; padding: 8px; border-radius: 10px;"></i>
            Detail Tipologi
        </h1>
        <a href="{{ route('admin.questions.typologies.index') }}" style="background: white; color: #64748b; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 10px; font-weight: 600; text-decoration: none;"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="bento-grid">
        <div class="bento-card">
            <div class="bento-title"><i class="fas fa-info-circle text-green-500"></i> Informasi Utama</div>
            <div class="hero-box">
                <div class="code-display">{{ $typology->kode_tipologi }}</div>
                <div style="width: 2px; height: 50px; background: #cbd5e1;"></div>
                <div class="name-display">{{ $typology->nama_tipologi }}</div>
            </div>

            <div class="desc-box">
                <div class="desc-label" style="color: #16a34a;"><i class="fas fa-bolt"></i> Kekuatan (Strength)</div>
                <div class="desc-content bg-strength">{!! nl2br(e($typology->deskripsi_kekuatan)) !!}</div>
            </div>

            <div class="desc-box">
                <div class="desc-label" style="color: #dc2626;"><i class="fas fa-exclamation-triangle"></i> Kelemahan (Weakness)</div>
                <div class="desc-content bg-weakness">{!! nl2br(e($typology->deskripsi_kelemahan)) !!}</div>
            </div>
        </div>

        <div class="bento-card" style="height: fit-content;">
            <div class="bento-title"><i class="fas fa-cog text-gray-500"></i> Aksi & Meta Data</div>

            <div class="mb-4 border-b border-dashed pb-4">
                <div style="font-size: 0.8rem; color: #64748b;">
                    <div>Dibuat: {{ $typology->created_at->format('d M Y, H:i') }}</div>
                    <div>Diupdate: {{ $typology->updated_at->format('d M Y, H:i') }}</div>
                </div>
            </div>

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.questions.typologies.edit', $typology->id) }}" class="btn-act act-edit"><i class="fas fa-pen"></i> Edit Data</a>
                <button type="button" onclick="confirmDelete()" class="btn-act act-del"><i class="fas fa-trash"></i> Hapus Data</button>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Hapus Tipologi?',
            html: `Yakin ingin menghapus <b>{{ $typology->nama_tipologi }}</b>?`,
            icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#f1f5f9',
            confirmButtonText: 'Ya, Hapus', cancelButtonText: '<span style="color:black">Batal</span>',
            customClass: { popup: 'rounded-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('admin.questions.typologies.destroy', $typology->id) }}";
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Edit Tipologi')

@push('styles')
<style>
    .form-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .form-section-title { font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
    .form-label.required::after { content: "*"; color: #ef4444; margin-left: 4px; }
    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; }
    .form-control:focus { background-color: white; border-color: #d97706; outline: none; box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.1); }
    .form-text { font-size: 0.75rem; color: #94a3b8; margin-top: 4px; }
    .form-actions { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; }
    .btn-save { background: #d97706; color: white; border: none; padding: 10px 24px; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.2s; }
    .btn-save:hover { background: #b45309; transform: translateY(-1px); }
    .btn-cancel { background: white; color: #64748b; border: 1px solid #e2e8f0; padding: 10px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-cancel:hover { background: #f8fafc; color: #0f172a; }
</style>
@endpush

@section('header')
<div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-edit" style="color: #d97706; background: #fef3c7; padding: 8px; border-radius: 10px;"></i>
        Edit Tipologi: {{ $typology->kode_tipologi }}
    </h1>
    <a href="{{ route('admin.questions.typologies.index') }}" class="btn-cancel"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
@endsection

@section('content')
<div class="form-card">
    <form action="{{ route('admin.questions.typologies.update', $typology->id) }}" method="POST" id="typologyEditForm">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">

            <div>
                <div class="form-section-title"><i class="fas fa-file-alt text-amber-500"></i> Konten Tipologi</div>

                <div class="form-group">
                    <label class="form-label required">Nama Tipologi</label>
                    <input type="text" name="nama_tipologi" class="form-control @error('nama_tipologi') border-red-500 @enderror" value="{{ old('nama_tipologi', $typology->nama_tipologi) }}" required>
                    @error('nama_tipologi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required">Kekuatan (Strength Description)</label>
                    <textarea name="deskripsi_kekuatan" class="form-control @error('deskripsi_kekuatan') border-red-500 @enderror" rows="5" required>{{ old('deskripsi_kekuatan', $typology->deskripsi_kekuatan) }}</textarea>
                    @error('deskripsi_kekuatan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required">Kelemahan (Weakness Description)</label>
                    <textarea name="deskripsi_kelemahan" class="form-control @error('deskripsi_kelemahan') border-red-500 @enderror" rows="5" required>{{ old('deskripsi_kelemahan', $typology->deskripsi_kelemahan) }}</textarea>
                    @error('deskripsi_kelemahan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <div class="form-section-title"><i class="fas fa-cog text-amber-500"></i> Identifikasi</div>

                <div class="form-group">
                    <label class="form-label required">Kode Tipologi</label>
                    <input type="text" name="kode_tipologi" id="kode_tipologi" class="form-control font-mono font-bold text-center @error('kode_tipologi') border-red-500 @enderror" value="{{ old('kode_tipologi', $typology->kode_tipologi) }}" maxlength="30" style="text-transform: uppercase;" required>
                    @error('kode_tipologi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mt-4 pt-4 border-t border-dashed">
                    <div class="text-xs text-gray-500 mb-1">Dibuat: {{ $typology->created_at->format('d M Y H:i') }}</div>
                    <div class="text-xs text-gray-500">Terakhir Update: {{ $typology->updated_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="form-actions mt-6 pt-6 border-t flex justify-end gap-3">
            <a href="{{ route('admin.questions.typologies.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save" id="submitBtn"><i class="fas fa-save"></i> Perbarui Tipologi</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#kode_tipologi').on('input', function() {
            $(this).val($(this).val().toUpperCase().replace(/[^A-Z0-9]/g, ''));
        });

        $('#typologyEditForm').on('submit', function() {
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...');
        });
    });
</script>
@endpush

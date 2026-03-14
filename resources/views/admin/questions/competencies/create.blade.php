@extends('admin.layouts.app')

@section('title', 'Tambah Kompetensi Baru')

@push('styles')
<style>
    .form-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .form-section-title { font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
    .form-label.required::after { content: "*"; color: #ef4444; margin-left: 4px; }
    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; }
    .form-control:focus { background-color: white; border-color: #22c55e; outline: none; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1); }
    .btn-save { background: #22c55e; color: white; border: none; padding: 10px 24px; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.2s; }
    .btn-save:hover { background: #16a34a; transform: translateY(-1px); }
    .btn-cancel { background: white; color: #64748b; border: 1px solid #e2e8f0; padding: 10px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    textarea.form-control { min-height: 100px; }
</style>
@endpush

@section('content')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-plus-circle" style="color: #22c55e; background: #dcfce7; padding: 8px; border-radius: 10px;"></i>
            Tambah Kompetensi
        </h1>
        <a href="{{ route('admin.questions.competencies.index') }}" class="btn-cancel"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.questions.competencies.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">

                <div>
                    <div class="form-section-title"><i class="fas fa-file-alt text-green-500"></i> Konten Kompetensi</div>

                    <div class="form-group">
                        <label class="form-label required">Nama Kompetensi</label>
                        <input type="text" name="competency_name" class="form-control" value="{{ old('competency_name') }}" required placeholder="Misal: Integritas, Kerjasama...">
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Kekuatan (Strength Description)</label>
                        <textarea name="strength_description" class="form-control" required placeholder="Deskripsikan kekuatan kompetensi ini...">{{ old('strength_description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Kelemahan (Weakness Description)</label>
                        <textarea name="weakness_description" class="form-control" required placeholder="Deskripsikan area kelemahan/risiko...">{{ old('weakness_description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Aktivitas Pengembangan (Improvement Activity)</label>
                        <textarea name="improvement_activity" class="form-control" required placeholder="Saran aktivitas untuk mengembangkan kompetensi ini...">{{ old('improvement_activity') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rekomendasi Training</label>
                        <textarea name="training_recommendations" class="form-control" placeholder="Daftar training yang disarankan (opsional)...">{{ old('training_recommendations') }}</textarea>
                    </div>
                </div>

                <div>
                    <div class="form-section-title"><i class="fas fa-cog text-green-500"></i> Identifikasi</div>
                    <div class="form-group">
                        <label class="form-label required">Kode Kompetensi</label>
                        <input type="text" name="competency_code" class="form-control font-mono font-bold text-center" value="{{ old('competency_code') }}" maxlength="30" style="text-transform: uppercase;" required placeholder="INT">
                        <small class="form-text">Kode unik maksimal 30 karakter.</small>
                    </div>

                    <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 mt-4">
                        <h6 class="text-blue-700 font-bold text-xs uppercase mb-2"><i class="fas fa-info-circle mr-1"></i> Informasi</h6>
                        <p class="text-blue-600 text-xs leading-relaxed mb-0">Pastikan Nama dan Kode kompetensi sesuai dengan framework SJT yang digunakan agar hasil laporan akurat.</p>
                    </div>
                </div>
            </div>

            <div class="form-actions mt-6 pt-6 border-t flex justify-end gap-3">
                <a href="{{ route('admin.questions.competencies.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-save"><i class="fas fa-save"></i> Simpan Kompetensi</button>
            </div>
        </form>
    </div>
@endsection

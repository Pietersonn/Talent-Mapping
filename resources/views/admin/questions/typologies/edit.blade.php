@extends('admin.layouts.app')

@section('title', 'Edit Tipologi')

@push('styles')
<style>
    /* --- STYLE UTAMA (Sama dengan Create) --- */
    .form-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .form-section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px dashed #e2e8f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-group { margin-bottom: 1.5rem; }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.5rem;
    }
    .form-label.required::after { content: "*"; color: #ef4444; margin-left: 4px; }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.9rem;
        color: #0f172a;
        background-color: #f8fafc;
        transition: all 0.2s;
    }
    .form-control:focus {
        background-color: white;
        border-color: #22c55e;
        outline: none;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
    }

    .form-text { font-size: 0.75rem; color: #94a3b8; margin-top: 4px; }

    /* Tombol Actions */
    .form-actions {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-save {
        background: #22c55e;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-save:hover { background: #16a34a; transform: translateY(-1px); }

    .btn-cancel {
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; }

    /* Textarea Height */
    textarea.form-control { min-height: 120px; line-height: 1.6; }
</style>
@endpush

@section('header')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-edit" style="color: #22c55e; background: #dcfce7; padding: 8px; border-radius: 10px;"></i>
                Edit Tipologi: <span style="font-family: monospace; color:#64748b;">{{ $typology->typology_code }}</span>
            </h1>
        </div>
        <a href="{{ route('admin.questions.typologies.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="form-card">
    <form action="{{ route('admin.questions.typologies.update', $typology->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">

            <div>
                <div class="form-section-title">
                    <i class="fas fa-file-alt text-green-500"></i> Informasi Tipologi
                </div>

                <div class="form-group">
                    <label class="form-label required" for="typology_name">Nama Tipologi</label>
                    <input type="text" name="typology_name" id="typology_name"
                           class="form-control @error('typology_name') border-red-500 @enderror"
                           value="{{ old('typology_name', $typology->typology_name) }}"
                           placeholder="Contoh: Ambassador, Commander..." required>
                    @error('typology_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required" for="description">Deskripsi</label>
                    <textarea name="description" id="description"
                              class="form-control @error('description') border-red-500 @enderror"
                              placeholder="Jelaskan karakteristik utama dari tipologi ini..." required>{{ old('description', $typology->description) }}</textarea>
                    @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="strengths">
                        <i class="fas fa-bolt text-green-500 mr-1"></i> Kekuatan (Strengths)
                    </label>
                    <textarea name="strengths" id="strengths"
                              class="form-control"
                              placeholder="Daftar kekuatan utama...">{{ old('strengths', $typology->strengths) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="weaknesses">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> Area Pengembangan (Weaknesses)
                    </label>
                    <textarea name="weaknesses" id="weaknesses"
                              class="form-control"
                              placeholder="Area yang perlu dikembangkan...">{{ old('weaknesses', $typology->weaknesses) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="career_suggestions">
                        <i class="fas fa-briefcase text-blue-500 mr-1"></i> Saran Karir
                    </label>
                    <textarea name="career_suggestions" id="career_suggestions" rows="3"
                              class="form-control"
                              placeholder="Saran jalur karir yang cocok...">{{ old('career_suggestions', $typology->career_suggestions) }}</textarea>
                </div>
            </div>

            <div>
                <div class="form-section-title">
                    <i class="fas fa-cog text-green-500"></i> Identifikasi
                </div>

                <div class="form-group">
                    <label class="form-label required" for="typology_code">Kode Tipologi</label>
                    <input type="text" name="typology_code" id="typology_code"
                           class="form-control font-mono font-bold text-center @error('typology_code') border-red-500 @enderror"
                           value="{{ old('typology_code', $typology->typology_code) }}"
                           placeholder="AAA" maxlength="10"
                           style="text-transform: uppercase; letter-spacing: 2px;" required>
                    <div class="form-text">Maksimal 10 karakter (A-Z).</div>
                    @error('typology_code') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="characteristics">Karakteristik Kunci</label>
                    <textarea name="characteristics" id="characteristics" rows="4"
                              class="form-control text-sm"
                              placeholder="Poin-poin perilaku...">{{ old('characteristics', $typology->characteristics) }}</textarea>
                </div>

                <div class="form-group p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="custom-control custom-switch d-flex align-items-center gap-2">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $typology->is_active) ? 'checked' : '' }}
                               style="width: 18px; height: 18px;">
                        <label class="custom-control-label font-bold text-sm text-gray-700 m-0" for="is_active">
                            Status Aktif
                        </label>
                    </div>
                    <div class="form-text mt-2">
                        Non-aktifkan jika tipologi ini tidak lagi digunakan dalam analisis.
                    </div>
                </div>

                <div class="mt-4 pt-4 border-top border-dashed">
                    <div class="text-xs text-gray-500 mb-1">Dibuat: {{ $typology->created_at->format('d M Y H:i') }}</div>
                    <div class="text-xs text-gray-500">Terakhir Update: {{ $typology->updated_at->format('d M Y H:i') }}</div>
                </div>

            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('admin.questions.typologies.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Perbarui Tipologi
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Force Uppercase for Code
        $('#typology_code').on('input', function() {
            $(this).val($(this).val().toUpperCase().replace(/[^A-Z0-9]/g, ''));
        });
    });
</script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Buat Tipologi Baru')

@push('styles')
<style>
    /* --- STYLE UTAMA (Mengadaptasi dari SJT Create) --- */
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

    /* Custom Textarea Height */
    textarea.form-control { min-height: 120px; line-height: 1.6; }
</style>
@endpush

@section('header')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-plus-circle" style="color: #22c55e; background: #dcfce7; padding: 8px; border-radius: 10px;"></i>
                Buat Tipologi Baru
            </h1>
        </div>
        <a href="{{ route('admin.questions.typologies.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="form-card">
    <form action="{{ route('admin.questions.typologies.store') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">

            <div>
                <div class="form-section-title">
                    <i class="fas fa-file-alt text-green-500"></i> Informasi Tipologi
                </div>

                <div class="form-group">
                    <label class="form-label required" for="typology_name">Nama Tipologi</label>
                    <input type="text" name="typology_name" id="typology_name"
                           class="form-control @error('typology_name') border-red-500 @enderror"
                           value="{{ old('typology_name') }}"
                           placeholder="Contoh: Ambassador, Commander..." required>
                    @error('typology_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required" for="description">Deskripsi</label>
                    <textarea name="description" id="description"
                              class="form-control @error('description') border-red-500 @enderror"
                              placeholder="Jelaskan karakteristik utama dari tipologi ini..." required>{{ old('description') }}</textarea>
                    @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="strengths">
                        <i class="fas fa-bolt text-green-500 mr-1"></i> Kekuatan (Strengths)
                    </label>
                    <textarea name="strengths" id="strengths"
                              class="form-control"
                              placeholder="Daftar kekuatan utama...">{{ old('strengths') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="weaknesses">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> Area Pengembangan (Weaknesses)
                    </label>
                    <textarea name="weaknesses" id="weaknesses"
                              class="form-control"
                              placeholder="Area yang perlu dikembangkan...">{{ old('weaknesses') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="career_suggestions">
                        <i class="fas fa-briefcase text-blue-500 mr-1"></i> Saran Karir
                    </label>
                    <textarea name="career_suggestions" id="career_suggestions" rows="3"
                              class="form-control"
                              placeholder="Saran jalur karir yang cocok...">{{ old('career_suggestions') }}</textarea>
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
                           value="{{ old('typology_code') }}"
                           placeholder="AAA" maxlength="10"
                           style="text-transform: uppercase; letter-spacing: 2px;" required>
                    <div class="form-text">Maksimal 10 karakter (A-Z).</div>
                    @error('typology_code') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="characteristics">Karakteristik Kunci</label>
                    <textarea name="characteristics" id="characteristics" rows="4"
                              class="form-control text-sm"
                              placeholder="Poin-poin perilaku...">{{ old('characteristics') }}</textarea>
                </div>

                <div class="p-3 bg-blue-50 rounded-xl mt-4 border border-blue-100">
                    <div class="flex items-center gap-2 mb-2 text-blue-700 font-bold text-xs uppercase tracking-wide">
                        <i class="fas fa-info-circle"></i> Info Sistem
                    </div>
                    <p class="text-xs text-blue-600 leading-relaxed">
                        Kode tipologi harus unik dan tidak boleh ada spasi. Disarankan menggunakan singkatan 3 huruf (misal: AMB).
                    </p>
                </div>

            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('admin.questions.typologies.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Simpan Tipologi
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-generate code from Name (First 3 letters)
        $('#typology_name').on('input', function() {
            let name = $(this).val();
            let currentCode = $('#typology_code').val();

            // Hanya auto-fill jika kode masih kosong
            if (name.length >= 3 && currentCode === '') {
                let code = name.substring(0, 3).toUpperCase();
                $('#typology_code').val(code);
            }
        });

        // Force Uppercase for Code
        $('#typology_code').on('input', function() {
            $(this).val($(this).val().toUpperCase().replace(/[^A-Z0-9]/g, ''));
        });
    });
</script>
@endpush

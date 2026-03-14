@extends('admin.layouts.app')

@section('title', 'Buat Versi Soal Baru')

@push('styles')
<style>
    /* --- FORM STYLES --- */
    .form-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .form-section-title { font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; }

    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
    .form-label.required::after { content: "*"; color: #ef4444; margin-left: 4px; }

    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; }
    .form-control:focus { background-color: white; border-color: #3b82f6; outline: none; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    .form-text { font-size: 0.75rem; color: #94a3b8; margin-top: 4px; }

    /* --- INFO ALERT --- */
    .info-box { background: #eff6ff; border: 1px solid #dbeafe; border-radius: 12px; padding: 1.25rem; }
    .info-box h5 { color: #1e40af; font-size: 0.95rem; font-weight: 700; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 8px; }
    .info-box ul { padding-left: 1.25rem; margin: 0; font-size: 0.85rem; color: #334155; }
    .info-box li { margin-bottom: 4px; }

    /* --- BUTTONS --- */
    .form-actions { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; }
    .btn-save { background: #3b82f6; color: white; border: none; padding: 10px 24px; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s; }
    .btn-save:hover { background: #2563eb; transform: translateY(-1px); }
    .btn-cancel { background: white; color: #64748b; border: 1px solid #e2e8f0; padding: 10px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; }
</style>
@endpush

@section('header')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-plus-circle" style="color: #3b82f6; background: #dbeafe; padding: 8px; border-radius: 10px;"></i>
                Buat Versi Soal Baru
            </h1>
        </div>
        <a href="{{ route('admin.questions.index') }}" class="btn-cancel"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
@endsection

@section('content')
<div class="form-card">
    <form action="{{ route('admin.questions.store') }}" method="POST" id="createForm">
        @csrf

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 3rem;">

            <div>
                <div class="form-section-title"><i class="fas fa-pen text-blue-500"></i> Detail Versi</div>

                <div class="form-group">
                    <label for="type" class="form-label required">Tipe Soal</label>
                    <select name="type" id="type" class="form-control @error('type') border-red-500 @enderror" required>
                        <option value="">-- Pilih Tipe Soal --</option>
                        <option value="st30" {{ old('type') === 'st30' ? 'selected' : '' }}>ST-30 (Strength Typology)</option>
                        <option value="sjt" {{ old('type') === 'sjt' ? 'selected' : '' }}>SJT (Situational Judgment Test)</option>
                    </select>
                    @error('type') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="name" class="form-label required">Nama Versi</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') border-red-500 @enderror"
                           placeholder="Contoh: ST-30 Batch 2024 V1" value="{{ old('name') }}" maxlength="50" required>
                    <div class="form-text" id="nameHelp">Berikan nama yang unik dan deskriptif.</div>
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Deskripsi / Catatan</label>
                    <textarea name="description" id="description" class="form-control" rows="4"
                              placeholder="Jelaskan perubahan atau tujuan versi ini..." maxlength="500">{{ old('description') }}</textarea>
                    <div class="form-text text-right" id="charCount">0/500 karakter</div>
                </div>
            </div>

            <div>
                <div class="form-section-title"><i class="fas fa-info-circle text-blue-500"></i> Informasi & Ketentuan</div>

                <div class="info-box mb-4">
                    <h5><i class="fas fa-lightbulb"></i> Catatan Penting:</h5>
                    <ul>
                        <li>Versi baru akan dibuat dengan status <strong>Tidak Aktif</strong> (Inactive).</li>
                        <li>Anda harus menambahkan soal terlebih dahulu sebelum bisa mengaktifkan versi ini.</li>
                        <li>Hanya <strong>satu versi</strong> per tipe soal yang bisa aktif dalam satu waktu.</li>
                    </ul>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <div class="mb-3">
                        <div class="font-bold text-sm text-slate-700 mb-1">ST-30 (Strength Typology)</div>
                        <p class="text-xs text-slate-500">Membutuhkan tepat <strong>30 pertanyaan</strong>. Digunakan untuk memetakan potensi kekuatan personality peserta.</p>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-slate-700 mb-1">SJT (Situational Judgment)</div>
                        <p class="text-xs text-slate-500">Membutuhkan tepat <strong>50 pertanyaan</strong> studi kasus dengan 5 opsi jawaban berbobot.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="reset" class="btn-cancel text-red-500 border-red-200 hover:bg-red-50 hover:border-red-300">Reset</button>
            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Buat Versi</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-generate name suggestion
        $('#type').on('change', function() {
            const type = $(this).val();
            const nameInput = $('#name');
            if (type && !nameInput.val()) {
                const year = new Date().getFullYear();
                const prefix = type === 'st30' ? 'ST-30' : 'SJT';
                nameInput.val(`${prefix} ${year} Ver. 1.0`);
            }
        });

        // Character counter
        $('#description').on('input', function() {
            const len = $(this).val().length;
            $('#charCount').text(`${len}/500 karakter`);
        });

        // SweetAlert Confirmation
        $('#createForm').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Buat Versi Baru?',
                text: "Pastikan data yang Anda masukkan sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: '<span style="color:#0f172a">Batal</span>',
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl', cancelButton: 'rounded-xl' }
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });
    });
</script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Edit Versi Soal')

@push('styles')
<style>
    /* Menggunakan style yang sama dengan create.blade.php */
    .form-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .form-section-title { font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
    .form-label.required::after { content: "*"; color: #ef4444; margin-left: 4px; }
    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; }
    .form-control:focus { background-color: white; border-color: #d97706; outline: none; box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.1); } /* Amber focus for edit */
    .form-control[readonly] { background-color: #f1f5f9; color: #64748b; cursor: not-allowed; }

    .form-actions { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; }
    .btn-save { background: #d97706; color: white; border: none; padding: 10px 24px; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s; }
    .btn-save:hover { background: #b45309; transform: translateY(-1px); }
    .btn-cancel { background: white; color: #64748b; border: 1px solid #e2e8f0; padding: 10px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; }
</style>
@endpush

@section('header')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-edit" style="color: #d97706; background: #fef3c7; padding: 8px; border-radius: 10px;"></i>
                Edit Versi Soal
            </h1>
        </div>
        <a href="{{ route('admin.questions.index') }}" class="btn-cancel"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
@endsection

@section('content')
<div class="form-card">
    {{-- Asumsi route update menggunakan parameter $questionVersion --}}
    <form action="{{ route('admin.questions.update', $questionVersion->id) }}" method="POST" id="editForm">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 3rem;">

            <div>
                <div class="form-section-title"><i class="fas fa-pen text-amber-500"></i> Informasi Versi</div>

                <div class="form-group">
                    <label class="form-label">Tipe Soal</label>
                    <input type="text" class="form-control" value="{{ strtoupper($questionVersion->type) }}" readonly>
                    <div class="form-text">Tipe soal tidak dapat diubah setelah dibuat.</div>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label required">Nama Versi</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') border-red-500 @enderror"
                           value="{{ old('name', $questionVersion->name) }}" maxlength="50" required>
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Deskripsi / Catatan</label>
                    <textarea name="description" id="description" class="form-control" rows="4"
                              maxlength="500">{{ old('description', $questionVersion->description) }}</textarea>
                    <div class="form-text text-right" id="charCount">0/500 karakter</div>
                </div>
            </div>

            <div>
                <div class="form-section-title"><i class="fas fa-chart-pie text-amber-500"></i> Status Data</div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-slate-600">Status Versi</span>
                        @if($questionVersion->is_active)
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">AKTIF</span>
                        @else
                            <span class="px-2 py-1 bg-slate-200 text-slate-600 text-xs font-bold rounded-full">TIDAK AKTIF</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-slate-600">Jumlah Soal</span>
                        <span class="font-mono font-bold text-slate-800">{{ $questionVersion->questions_count ?? 0 }} Soal</span>
                    </div>
                </div>

                <div class="alert alert-warning text-sm bg-amber-50 border-amber-100 text-amber-800 p-4 rounded-xl">
                    <i class="fas fa-exclamation-triangle mr-1"></i> <strong>Perhatian:</strong>
                    Mengubah nama versi tidak akan mempengaruhi data hasil tes yang sudah ada, namun disarankan untuk tetap menggunakan penamaan yang konsisten agar mudah dilacak.
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.questions.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Init Char Count
        let currentLen = $('#description').val().length;
        $('#charCount').text(`${currentLen}/500 karakter`);

        $('#description').on('input', function() {
            const len = $(this).val().length;
            $('#charCount').text(`${len}/500 karakter`);
        });

        // SweetAlert Confirmation
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Pastikan data yang Anda ubah sudah benar.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
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

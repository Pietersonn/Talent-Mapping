@extends('admin.layouts.app')

@section('title', 'Buat Pertanyaan ST-30')

@push('styles')
<style>
    /* Sama persis dengan Edit */
    .form-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .form-section-title { font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
    .form-label.required::after { content: "*"; color: #ef4444; margin-left: 4px; }
    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; }
    .form-control:focus { background-color: white; border-color: #22c55e; outline: none; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1); }
    .form-text { font-size: 0.75rem; color: #94a3b8; margin-top: 4px; }
    .form-actions { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; }
    .btn-save { background: #22c55e; color: white; border: none; padding: 10px 24px; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s; }
    .btn-save:hover { background: #16a34a; transform: translateY(-1px); }
    .btn-cancel { background: white; color: #64748b; border: 1px solid #e2e8f0; padding: 10px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; }
</style>
@endpush

@section('header')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-plus-circle" style="color: #22c55e; background: #dcfce7; padding: 8px; border-radius: 10px;"></i>
                Buat Pertanyaan Baru
            </h1>
            <div style="font-size: 0.9rem; color: #64748b; margin-left: 44px;">Tambahkan pertanyaan ST-30 baru ke bank soal.</div>
        </div>
        <a href="{{ route('admin.questions.st30.index', ['version' => $selectedVersion->id]) }}" class="btn-cancel">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="form-card">
    <form action="{{ route('admin.questions.st30.store') }}" method="POST" id="st30Form">
        @csrf
        <input type="hidden" name="version_id" value="{{ $selectedVersion->id }}">

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">

            <div>
                <div class="form-section-title"><i class="fas fa-file-alt text-green-500"></i> Konten Pertanyaan</div>

                <div class="form-group">
                    <label class="form-label required">Pernyataan (Statement)</label>
                    <textarea name="statement" id="statement" class="form-control @error('statement') border-red-500 @enderror" rows="5" required maxlength="500" placeholder="Contoh: Saya lebih suka bekerja secara terstruktur...">{{ old('statement') }}</textarea>
                    @error('statement') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    <div class="form-text text-right"><span id="statement_char_count">0</span>/500 karakter</div>
                </div>

                <div class="form-group">
                    <label class="form-label required">Tipologi Kepribadian</label>
                    <select name="typology_code" id="typology_code" class="form-control @error('typology_code') border-red-500 @enderror" required>
                        <option value="">-- Pilih Tipologi --</option>
                        @foreach($typologies as $typology)
                            <option value="{{ $typology->typology_code }}" {{ old('typology_code') == $typology->typology_code ? 'selected' : '' }}>
                                {{ $typology->typology_code }} - {{ $typology->typology_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('typology_code') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div id="typology_description" class="p-4 bg-blue-50 border border-blue-100 rounded-xl mt-4" style="display: none;">
                    <div class="flex items-center gap-2 mb-2 text-blue-700 font-bold text-xs uppercase tracking-wide">
                        <i class="fas fa-info-circle"></i> Deskripsi Tipologi
                    </div>
                    <p class="text-sm text-blue-800 leading-relaxed" id="typology_desc_text"></p>
                </div>
            </div>

            <div>
                <div class="form-section-title"><i class="fas fa-cog text-green-500"></i> Pengaturan Soal</div>

                <div class="form-group">
                    <label class="form-label">Versi Soal</label>
                    <input type="text" class="form-control" value="{{ $selectedVersion->display_name }}" readonly disabled style="color: #64748b;">
                </div>

                <div class="form-group">
                    <label class="form-label required">Nomor Urut</label>
                    <input type="number" name="number" class="form-control @error('number') border-red-500 @enderror"
                           value="{{ old('number', $nextNumber) }}" min="1" max="30" required>
                    @error('number') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="p-3 bg-yellow-50 rounded-xl mt-4 border border-yellow-100">
                    <div class="flex items-center gap-2 mb-2 text-yellow-700 font-bold text-xs uppercase tracking-wide">
                        <i class="fas fa-lightbulb"></i> Tips
                    </div>
                    <ul class="list-disc list-inside text-sm text-yellow-800 space-y-1">
                        <li>Gunakan pernyataan yang jelas dan tidak ambigu.</li>
                        <li>Fokus pada perilaku alami (bakat).</li>
                        <li>Pastikan tipologi sesuai dengan master data.</li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('admin.questions.st30.index', ['version' => $selectedVersion->id]) }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save" id="submitBtn">
                <i class="fas fa-check-circle"></i> Simpan Pertanyaan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const typologyDescriptions = {
        @foreach($typologies as $typology)
            '{{ $typology->typology_code }}': '{{ addslashes($typology->strength_description) }}',
        @endforeach
    };

    $('#typology_code').on('change', function() {
        var selectedCode = $(this).val();
        if (selectedCode && typologyDescriptions[selectedCode]) {
            $('#typology_desc_text').text(typologyDescriptions[selectedCode]);
            $('#typology_description').fadeIn();
        } else {
            $('#typology_description').hide();
        }
    });

    $('#statement').on('input', function() {
        $('#statement_char_count').text($(this).val().length);
    });

    $('#st30Form').on('submit', function(e) {
        var statement = $('#statement').val().trim();
        var typology = $('#typology_code').val();

        if (!statement || !typology) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Data Belum Lengkap',
                text: 'Mohon lengkapi pernyataan dan pilih tipologi terlebih dahulu.',
                confirmButtonColor: '#22c55e'
            });
            return false;
        }
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...');
    });
});
</script>
@endpush

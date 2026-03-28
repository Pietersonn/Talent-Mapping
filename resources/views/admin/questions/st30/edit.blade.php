@extends('admin.layouts.app')

@section('title', 'Edit Pertanyaan ST-30')

@push('styles')
<style>
    /* Mengadopsi style dari Event Management */
    .form-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .form-section-title { font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; }

    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
    .form-label.required::after { content: "*"; color: #ef4444; margin-left: 4px; }

    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; }
    .form-control:focus { background-color: white; border-color: #22c55e; outline: none; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1); }
    .form-text { font-size: 0.75rem; color: #94a3b8; margin-top: 4px; }

    /* Buttons */
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
                <i class="fas fa-edit" style="color: #22c55e; background: #dcfce7; padding: 8px; border-radius: 10px;"></i>
                Edit Pertanyaan ST-30
            </h1>
            <div style="font-size: 0.9rem; color: #64748b; margin-left: 44px;">Perbarui detail pernyataan dan tipologi soal ini.</div>
        </div>
        <a href="{{ route('admin.questions.st30.index', ['version' => $st30Question->id_versi]) }}" class="btn-cancel">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="form-card">
    <form action="{{ route('admin.questions.st30.update', $st30Question) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">

            <div>
                <div class="form-section-title"><i class="fas fa-file-alt text-green-500"></i> Konten Pertanyaan</div>

                <div class="form-group">
                    <label class="form-label required">Pernyataan (Statement)</label>
                    <textarea name="pernyataan" id="pernyataan" class="form-control @error('pernyataan') border-red-500 @enderror" rows="5" required maxlength="500" placeholder="Masukkan pernyataan perilaku...">{{ old('pernyataan', $st30Question->pernyataan) }}</textarea>
                    @error('pernyataan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    <div class="form-text text-right"><span id="pernyataan_char_count">{{ strlen($st30Question->pernyataan) }}</span>/500 karakter</div>
                </div>

                <div class="form-group">
                    <label class="form-label required">Tipologi Kepribadian</label>
                    <select name="kode_tipologi" id="kode_tipologi" class="form-control @error('kode_tipologi') border-red-500 @enderror" required>
                        @foreach($typologies as $typology)
                            <option value="{{ $typology->kode_tipologi }}"
                                {{ old('kode_tipologi', $st30Question->kode_tipologi) == $typology->kode_tipologi ? 'selected' : '' }}>
                                {{ $typology->kode_tipologi }} - {{ $typology->nama_tipologi }}
                            </option>
                        @endforeach
                    </select>
                    @error('kode_tipologi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div id="typology_description" class="p-4 bg-blue-50 border border-blue-100 rounded-xl mt-4">
                    <div class="flex items-center gap-2 mb-2 text-blue-700 font-bold text-xs uppercase tracking-wide">
                        <i class="fas fa-info-circle"></i> Deskripsi Tipologi
                    </div>
                    <p class="text-sm text-blue-800 leading-relaxed" id="typology_desc_text">
                        {{ $st30Question->typologyDescription->deskripsi_kekuatan ?? 'Tidak ada deskripsi.' }}
                    </p>
                </div>
            </div>

            <div>
                <div class="form-section-title"><i class="fas fa-cog text-green-500"></i> Pengaturan Soal</div>

                <div class="form-group">
                    <label class="form-label">Versi Soal</label>
                    <input type="text" class="form-control" value="{{ $st30Question->questionVersion->versi }} - {{ $st30Question->questionVersion->nama }}" readonly disabled style="color: #64748b;">
                </div>

                <div class="form-group">
                    <label class="form-label required">Nomor Urut</label>
                    <input type="number" name="nomor" class="form-control @error('nomor') border-red-500 @enderror"
                           value="{{ old('nomor', $st30Question->nomor) }}" min="1" max="30" required>
                    @error('nomor') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="p-3 bg-gray-50 rounded-xl mt-4 border border-gray-100">
                    <div class="flex items-center gap-2 mb-2 text-gray-600 font-bold text-xs uppercase tracking-wide">
                        <i class="fas fa-chart-pie"></i> Statistik
                    </div>
                    <div class="text-sm text-gray-600">
                        <div class="flex justify-between mb-1"><span>Total Penggunaan:</span> <span class="font-bold">{{ $st30Question->usage_count ?? 0 }}x</span></div>
                        <div class="flex justify-between"><span>Dibuat:</span> <span>{{ $st30Question->created_at->format('d M Y') }}</span></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('admin.questions.st30.index', ['version' => $st30Question->id_versi]) }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Data Deskripsi
    const typologyDescriptions = {
        @foreach($typologies as $typology)
            '{{ $typology->kode_tipologi }}': '{{ addslashes($typology->deskripsi_kekuatan) }}',
        @endforeach
    };

    // Update deskripsi saat dropdown berubah
    $('#kode_tipologi').on('change', function() {
        var selectedCode = $(this).val();
        if (selectedCode && typologyDescriptions[selectedCode]) {
            $('#typology_desc_text').text(typologyDescriptions[selectedCode]);
        }
    });

    // Hitung karakter
    $('#pernyataan').on('input', function() {
        $('#pernyataan_char_count').text($(this).val().length);
    });
});
</script>
@endpush

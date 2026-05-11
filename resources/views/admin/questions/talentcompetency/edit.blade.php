@extends('admin.layouts.app')

@section('title', 'Edit Soal TK')

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

    .option-row { display: flex; gap: 1rem; margin-bottom: 1rem; align-items: flex-start; padding: 1rem; background: #fff; border: 1px solid #f1f5f9; border-radius: 12px; transition: 0.2s; }
    .option-row:hover { border-color: #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .opt-letter-box { width: 36px; height: 36px; flex-shrink: 0; background: #fffbeb; color: #d97706; font-weight: 800; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; margin-top: 2px; }
    .opt-input { flex-grow: 1; }
    .opt-score { width: 120px; flex-shrink: 0; }

    .form-actions { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; }
    .btn-save { background: #d97706; color: white; border: none; padding: 10px 24px; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s; }
    .btn-save:hover { background: #b45309; transform: translateY(-1px); }
    .btn-cancel { background: white; color: #64748b; border: 1px solid #e2e8f0; padding: 10px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-cancel:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; }
</style>
@endpush

@section('header')
<div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-edit" style="color: #d97706; background: #fef3c7; padding: 8px; border-radius: 10px;"></i>
            Edit Soal TK #{{ $talentCompetencyQuestion->nomor }}
        </h1>
    </div>
    <a href="{{ route('admin.questions.tk.index', ['version' => $talentCompetencyQuestion->id_versi]) }}" class="btn-cancel">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection

@section('content')
<div class="form-card">
    <form action="{{ route('admin.questions.tk.update', $talentCompetencyQuestion->id) }}" method="POST" id="tkEditForm">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">

            {{-- KOLOM KIRI: KONTEN SOAL & OPSI --}}
            <div>
                <div class="form-section-title"><i class="fas fa-file-alt text-amber-500"></i> Skenario Kasus / Pertanyaan</div>

                <div class="form-group">
                    <label class="form-label required">Teks Pertanyaan</label>
                    <textarea name="teks_pertanyaan" id="teks_pertanyaan" class="form-control @error('teks_pertanyaan') border-red-500 @enderror" rows="5" required placeholder="Tuliskan skenario situasi di sini...">{{ old('teks_pertanyaan', $talentCompetencyQuestion->teks_pertanyaan) }}</textarea>
                    @error('teks_pertanyaan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    <div class="form-text text-right"><span id="char_count">{{ strlen($talentCompetencyQuestion->teks_pertanyaan) }}</span>/1000 karakter</div>
                </div>

                <div class="form-section-title mt-8"><i class="fas fa-list-ol text-amber-500"></i> Opsi Jawaban & Poin</div>

                @foreach($talentCompetencyQuestion->options->sortBy('huruf_pilihan') as $index => $opt)
                    <div class="option-row">
                        <div class="opt-letter-box">{{ strtoupper($opt->huruf_pilihan) }}</div>
                        <input type="hidden" name="options[{{ $index }}][id]" value="{{ $opt->id }}">

                        <div class="opt-input">
                            <textarea name="options[{{ $index }}][teks_pilihan]" class="form-control" rows="2" placeholder="Tindakan opsi {{ strtoupper($opt->huruf_pilihan) }}..." required>{{ old("options.{$index}.teks_pilihan", $opt->teks_pilihan) }}</textarea>
                            @error("options.{$index}.teks_pilihan") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="opt-score">
                            <select name="options[{{ $index }}][skor]" class="form-control text-center font-bold text-amber-600 @error("options.{$index}.skor") border-red-500 @enderror" required style="cursor: pointer;">
                                <option value="" disabled>Skor</option>
                                @for($s = 0; $s <= 5; $s++)
                                    <option value="{{ $s }}" {{ old("options.{$index}.skor", $opt->skor) == $s ? 'selected' : '' }}>
                                        {{ $s }} Poin
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- KOLOM KANAN: PENGATURAN --}}
            <div>
                <div class="form-section-title"><i class="fas fa-cog text-amber-500"></i> Pengaturan</div>

                <div class="form-group">
                    <label class="form-label">Versi Soal</label>
                    <input type="text" class="form-control" value="{{ $talentCompetencyQuestion->questionVersion->nama ?? '-' }}" readonly disabled style="color: #64748b;">
                </div>

                <div class="form-group">
                    <label class="form-label required">Nomor Urut</label>
                    <input type="number" name="nomor" class="form-control @error('nomor') border-red-500 @enderror" value="{{ old('nomor', $talentCompetencyQuestion->nomor) }}" min="1" max="50" required>
                    @error('nomor') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status Tampil</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="aktif" value="1" class="sr-only peer" {{ old('aktif', $talentCompetencyQuestion->aktif) ? 'checked' : '' }}>
                            <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                            <span class="ms-3 text-sm font-medium text-slate-700">Aktifkan Soal</span>
                        </label>
                    </div>
                </div>

                <div class="form-section-title" style="margin-top: 2rem;"><i class="fas fa-bullseye text-blue-500"></i> Kompetensi Target</div>

                <div class="form-group">
                    <select name="kode_kompetensi" id="kode_kompetensi" class="form-control bg-slate-50 @error('kode_kompetensi') border-red-500 @enderror" required>
                        @foreach($competencies as $komp)
                            <option value="{{ $komp->kode_kompetensi }}" {{ old('kode_kompetensi', $talentCompetencyQuestion->kode_kompetensi) == $komp->kode_kompetensi ? 'selected' : '' }}>
                                {{ $komp->kode_kompetensi }} - {{ $komp->nama_kompetensi }}
                            </option>
                        @endforeach
                    </select>
                    @error('kode_kompetensi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="p-3 bg-blue-50 rounded-xl mt-4 border border-blue-100">
                    <div class="flex items-center gap-2 mb-2 text-blue-700 font-bold text-xs uppercase tracking-wide">
                        <i class="fas fa-info-circle"></i> Info Skoring
                    </div>
                    <p class="text-xs text-blue-600 leading-relaxed">
                        Pastikan setiap opsi memiliki skor penilaian (0-5) yang sesuai dengan rubrik efektivitas kompetensi.
                    </p>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('admin.questions.tk.index', ['version' => $talentCompetencyQuestion->id_versi]) }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save" id="submitBtn">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#teks_pertanyaan').on('input', function() {
        $('#char_count').text($(this).val().length);
    });

    $('#tkEditForm').on('submit', function() {
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...');
    });
});
</script>
@endpush

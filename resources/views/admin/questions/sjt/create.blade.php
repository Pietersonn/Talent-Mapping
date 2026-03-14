@extends('admin.layouts.app')

@section('title', 'Buat Soal SJT')

@push('styles')
<style>
    /* Style Konsisten dengan Edit */
    .form-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .form-section-title { font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
    .form-label.required::after { content: "*"; color: #ef4444; margin-left: 4px; }
    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; }
    .form-control:focus { background-color: white; border-color: #22c55e; outline: none; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1); }
    .form-text { font-size: 0.75rem; color: #94a3b8; margin-top: 4px; }
    .option-row { display: flex; gap: 1rem; align-items: flex-start; padding: 1rem; background: #fff; border: 1px solid #f1f5f9; border-radius: 12px; margin-bottom: 1rem; transition: 0.2s; }
    .option-row:hover { border-color: #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .option-badge { width: 36px; height: 36px; border-radius: 8px; background: #dcfce7; color: #166534; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem; flex-shrink: 0; margin-top: 2px; }
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
                Buat Soal SJT Baru
            </h1>
        </div>
        <a href="{{ route('admin.questions.sjt.index', ['version' => $selectedVersion->id]) }}" class="btn-cancel">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="form-card">
    <form action="{{ route('admin.questions.sjt.store') }}" method="POST">
        @csrf
        <input type="hidden" name="version_id" value="{{ $selectedVersion->id }}">

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">

            <div>
                <div class="form-section-title">
                    <i class="fas fa-file-alt text-green-500"></i> Konten Pertanyaan
                </div>

                <div class="form-group">
                    <label class="form-label required">Deskripsi Situasi</label>
                    <textarea name="question_text" id="question_text" class="form-control @error('question_text') border-red-500 @enderror" rows="5" placeholder="Contoh: Anda sedang memimpin rapat dan terjadi konflik antar anggota..." required>{{ old('question_text') }}</textarea>
                    @error('question_text') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    <div class="form-text text-right"><span id="char_count">0</span>/1000 karakter</div>
                </div>

                <div class="form-section-title" style="margin-top: 2rem;">
                    <i class="fas fa-list-ol text-green-500"></i> Opsi Jawaban & Poin
                </div>

                @php $letters = ['A', 'B', 'C', 'D', 'E']; @endphp
                @for($i = 0; $i < 5; $i++)
                    <div class="option-row">
                        <div class="option-badge">{{ $letters[$i] }}</div>
                        <div style="flex: 1;">
                            <input type="text" name="options[{{ $i }}][option_text]"
                                   class="form-control @error('options.'.$i.'.option_text') border-red-500 @enderror"
                                   placeholder="Tindakan opsi {{ $letters[$i] }}..." value="{{ old('options.'.$i.'.option_text') }}" required>
                        </div>
                        <div style="width: 120px;">
                            <select name="options[{{ $i }}][score]" class="form-control text-center @error('options.'.$i.'.score') border-red-500 @enderror" required style="cursor: pointer;">
                                <option value="" selected disabled>Skor</option>
                                <option value="0">0 Poin</option>
                                <option value="1">1 Poin</option>
                                <option value="2">2 Poin</option>
                                <option value="3">3 Poin</option>
                                <option value="4">4 Poin</option>
                            </select>
                        </div>
                    </div>
                @endfor
            </div>

            <div>
                <div class="form-section-title">
                    <i class="fas fa-cog text-green-500"></i> Pengaturan
                </div>

                <div class="form-group">
                    <label class="form-label">Versi Soal</label>
                    <input type="text" class="form-control" value="{{ $selectedVersion->display_name }}" readonly disabled style="color: #64748b;">
                </div>

                <div class="form-group">
                    <label class="form-label required">Nomor Urut</label>
                    <input type="number" name="number" class="form-control @error('number') border-red-500 @enderror"
                           value="{{ old('number', $nextNumber) }}" min="1" max="50" required>
                    @error('number') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required">Kompetensi Target</label>
                    <select name="competency" class="form-control @error('competency') border-red-500 @enderror" required>
                        <option value="">-- Pilih Kompetensi --</option>
                        @foreach($competencies as $competency)
                            <option value="{{ $competency->competency_code }}" {{ old('competency') == $competency->competency_code ? 'selected' : '' }}>
                                {{ $competency->competency_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('competency') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="p-3 bg-blue-50 rounded-xl mt-4 border border-blue-100">
                    <div class="flex items-center gap-2 mb-2 text-blue-700 font-bold text-xs uppercase tracking-wide">
                        <i class="fas fa-info-circle"></i> Info
                    </div>
                    <p class="text-xs text-blue-600 leading-relaxed">
                        Pastikan seluruh 5 opsi terisi lengkap beserta skor penilaiannya.
                    </p>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('admin.questions.sjt.index', ['version' => $selectedVersion->id]) }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save">
                <i class="fas fa-check-circle"></i> Simpan Soal
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $('#question_text').on('input', function() {
        $('#char_count').text($(this).val().length);
    });
</script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Buat Soal TK')

@push('styles')
<style>
    .form-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .form-section-title { font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
    .form-label.required::after { content: "*"; color: #ef4444; margin-left: 4px; }
    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; }
    .form-control:focus { background-color: white; border-color: #22c55e; outline: none; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1); }
    .form-text { font-size: 0.75rem; color: #94a3b8; margin-top: 4px; }

    .option-row { display: flex; gap: 1rem; margin-bottom: 1rem; align-items: flex-start; }
    .opt-letter-box { width: 40px; height: 40px; flex-shrink: 0; background: #eff6ff; color: #1d4ed8; font-weight: 800; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; border: 1px solid #bfdbfe; }
    .opt-input { flex-grow: 1; }
    .opt-score { width: 100px; flex-shrink: 0; }

    .form-actions { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; }
    .btn-save { background: #22c55e; color: white; border: none; padding: 10px 24px; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s; }
    .btn-save:hover { background: #16a34a; transform: translateY(-1px); }
    .btn-cancel { background: white; color: #64748b; border: 1px solid #e2e8f0; padding: 10px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-cancel:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; }
</style>
@endpush

@section('header')
<div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-plus-circle" style="color: #22c55e; background: #dcfce7; padding: 8px; border-radius: 10px;"></i>
            Buat Soal TK Baru
        </h1>
        <div style="font-size: 0.9rem; color: #64748b; margin-left: 44px;">
            Tambahkan soal kasus dan 5 opsi jawaban untuk Versi: <span class="font-bold text-slate-700">{{ $selectedVersion->nama }}</span>
        </div>
    </div>
    <a href="{{ route('admin.questions.tk.index', ['version' => $selectedVersion->id]) }}" class="btn-cancel">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection

@section('content')
<div class="form-card">
    <form action="{{ route('admin.questions.tk.store') }}" method="POST" id="tkForm">
        @csrf
        <input type="hidden" name="id_versi" value="{{ $selectedVersion->id }}">

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">

            {{-- KOLOM KIRI: KONTEN SOAL & OPSI --}}
            <div>
                <div class="form-section-title"><i class="fas fa-file-alt text-green-500"></i> Skenario Kasus / Pertanyaan</div>

                <div class="form-group">
                    <label class="form-label required">Teks Pertanyaan</label>
                    <textarea name="teks_pertanyaan" id="teks_pertanyaan" class="form-control @error('teks_pertanyaan') border-red-500 @enderror" rows="4" required placeholder="Tuliskan skenario kasus atau pertanyaan di sini...">{{ old('teks_pertanyaan') }}</textarea>
                    @error('teks_pertanyaan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    <div class="form-text text-right"><span id="char_count">0</span>/1000 karakter</div>
                </div>

                <div class="form-section-title mt-8"><i class="fas fa-list-ul text-blue-500"></i> Opsi Jawaban & Bobot</div>

                @php $letters = ['A', 'B', 'C', 'D', 'E']; @endphp

                @foreach($letters as $index => $letter)
                    <div class="option-row">
                        <div class="opt-letter-box">{{ $letter }}</div>
                        <input type="hidden" name="options[{{ $index }}][huruf]" value="{{ $letter }}">

                        <div class="opt-input">
                            <textarea name="options[{{ $index }}][teks]" class="form-control" rows="2" placeholder="Tindakan opsi {{ $letter }}..." required>{{ old("options.{$index}.teks") }}</textarea>
                            @error("options.{$index}.teks") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="opt-score">
                            <select name="options[{{ $index }}][skor]" class="form-control text-center font-bold text-green-600" required style="cursor: pointer;">
                                <option value="" disabled selected>Skor</option>
                                @for($s = 0; $s <= 5; $s++)
                                    <option value="{{ $s }}" {{ old("options.{$index}.skor") == $s ? 'selected' : '' }}>
                                        {{ $s }} Poin
                                    </option>
                                @endfor
                            </select>
                            <div class="text-[10px] text-center text-slate-400 mt-1">Skor (0-5)</div>
                            @error("options.{$index}.skor") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- KOLOM KANAN: PENGATURAN --}}
            <div>
                <div class="form-section-title"><i class="fas fa-cog text-green-500"></i> Pengaturan Soal</div>

                <div class="form-group">
                    <label class="form-label required">Nomor Urut</label>
                    <input type="number" name="nomor" class="form-control @error('nomor') border-red-500 @enderror" value="{{ old('nomor', $nextNumber) }}" min="1" max="50" required>
                    @error('nomor') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status Tampil</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="aktif" value="1" class="sr-only peer" {{ old('aktif', true) ? 'checked' : '' }}>
                            <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            <span class="ms-3 text-sm font-medium text-slate-700">Aktifkan Soal</span>
                        </label>
                    </div>
                </div>

                <div class="form-section-title" style="margin-top: 2rem;"><i class="fas fa-bullseye text-amber-500"></i> Target Pemetaan</div>

                <div class="form-group">
                    <label class="form-label required">Kompetensi</label>
                    <select name="kode_kompetensi" id="kode_kompetensi" class="form-control bg-slate-50 @error('kode_kompetensi') border-red-500 @enderror" required>
                        <option value="">-- Pilih Kompetensi --</option>
                        @foreach($competencies as $komp)
                            <option value="{{ $komp->kode_kompetensi }}" {{ old('kode_kompetensi') == $komp->kode_kompetensi ? 'selected' : '' }}>
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
                    <ul class="text-xs text-blue-800 space-y-2 pl-3 list-disc">
                        <li>Gunakan skala skor 0 sampai 5.</li>
                        <li>Skor tertinggi <b>(5)</b>: Tindakan paling efektif.</li>
                        <li>Skor terendah <b>(0/1)</b>: Tindakan tidak disarankan.</li>
                        <li>Pastikan seluruh 5 opsi terisi lengkap.</li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('admin.questions.tk.index', ['version' => $selectedVersion->id]) }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save" id="submitBtn">
                <i class="fas fa-check-circle"></i> Simpan Soal
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

    $('#tkForm').on('submit', function(e) {
        var statement = $('#teks_pertanyaan').val().trim();
        var competency = $('#kode_kompetensi').val();

        if (!statement || !competency) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Data Belum Lengkap',
                text: 'Mohon lengkapi skenario kasus dan pilih kompetensi terlebih dahulu.',
                confirmButtonColor: '#22c55e'
            });
            return false;
        }
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...');
    });
});
</script>
@endpush

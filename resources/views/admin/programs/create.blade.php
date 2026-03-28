@extends('admin.layouts.app')

@section('title', 'Buat Event Baru')

@push('styles')
<style>
    /* --- FORM STYLES --- */
    .form-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .form-section-title { font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; }

    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
    .form-label.required::after { content: "*"; color: #ef4444; margin-left: 4px; }

    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; }
    .form-control:focus { background-color: white; border-color: #22c55e; outline: none; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1); }
    .form-text { font-size: 0.75rem; color: #94a3b8; margin-top: 4px; }

    /* --- TOGGLE SWITCH MODERN --- */
    .toggle-wrapper { display: flex; align-items: center; gap: 12px; padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; }
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    input:checked + .slider { background-color: #22c55e; }
    input:checked + .slider:before { transform: translateX(20px); }

    /* --- BUTTONS --- */
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
                Buat Event Baru
            </h1>
        </div>
        <a href="{{ route('admin.events.index') }}" class="btn-cancel"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
@endsection

@section('content')
<div class="form-card">
    <form action="{{ route('admin.events.store') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3rem;">

            <div>
                <div class="form-section-title"><i class="fas fa-info-circle text-green-500"></i> Informasi Event</div>

                <div class="form-group">
                    <label class="form-label required">Nama Event</label>
                    <input type="text" name="name" class="form-control @error('name') border-red-500 @enderror" value="{{ old('name') }}" placeholder="Contoh: Recruitment Batch 1 2024" required>
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required">Kode Event (Unik)</label>
                    <input type="text" name="event_code" id="event_code" class="form-control @error('event_code') border-red-500 @enderror" value="{{ old('event_code') }}" placeholder="Contoh: REC-B1-24" required>
                    <div class="form-text">Akan otomatis diubah menjadi huruf besar.</div>
                    @error('event_code') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Instansi / Perusahaan</label>
                    <input type="text" name="company" class="form-control" value="{{ old('company') }}" placeholder="Contoh: PT. Maju Jaya Sejahtera">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan detail event secara singkat...">{{ old('description') }}</textarea>
                </div>
            </div>

            <div>
                <div class="form-section-title"><i class="fas fa-calendar-alt text-green-500"></i> Jadwal & Pengaturan</div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label required">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') border-red-500 @enderror" value="{{ old('start_date') }}" required>
                        @error('start_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') border-red-500 @enderror" value="{{ old('end_date') }}" required>
                        @error('end_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Person In Charge (PIC)</label>
                    <select name="pic_id" class="form-control" style="background-image: none;">
                        <option value="">-- Pilih PIC (Opsional) --</option>
                        @foreach($pics as $pic)
                            <option value="{{ $pic->id }}" {{ old('pic_id') == $pic->id ? 'selected' : '' }}>{{ $pic->name }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Hanya menampilkan user dengan role 'PIC' yang aktif.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Batas Maksimal Peserta</label>
                    <input type="number" name="max_participants" class="form-control" value="{{ old('max_participants') }}" placeholder="Contoh: 100 (Kosongkan jika tidak terbatas)" min="1">
                </div>

                <div class="form-group" style="margin-top: 2rem;">
                    <label class="form-label">Status Publikasi</label>
                    <div class="toggle-wrapper">
                        <label class="switch">
                            {{-- Input checkbox tersembunyi --}}
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem; color: #0f172a;">Aktifkan Event Segera?</div>
                            <div style="font-size: 0.75rem; color: #64748b;">Jika non-aktif, event tidak akan terlihat oleh peserta.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.events.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save"><i class="fas fa-save"></i> Simpan Event</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Auto Uppercase untuk Kode Event
    $('#event_code').on('input', function() {
        $(this).val($(this).val().toUpperCase().replace(/\s+/g, '')); // Uppercase & hapus spasi
    });

    // Validasi Tanggal: Tanggal Selesai minimal harus sama dengan Tanggal Mulai
    $('#start_date').on('change', function() {
        $('#end_date').attr('min', $(this).val());
    });
</script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Edit Program')

@push('styles')
<style>
    .form-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .form-section-title { font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
    .form-label.required::after { content: "*"; color: #22c55e; margin-left: 4px; }
    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; background-color: #f8fafc; transition: 0.2s; }
    .form-control:focus { background-color: white; border-color: #22c55e; outline: none; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1); }
    .toggle-wrapper { display: flex; align-items: center; gap: 12px; padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; }
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: #22c55e; }
    input:checked + .slider:before { transform: translateX(20px); }
    .btn-save { background: #22c55e; color: white; border: none; padding: 10px 24px; border-radius: 12px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3); transition: 0.2s; }
    .btn-save:hover { background: #16a34a; transform: translateY(-1px); }
</style>
@endpush

@section('content')
<div class="form-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-edit text-green-500 bg-green-100 p-2 rounded-xl"></i>
            Edit Program
        </h1>
        <a href="{{ route('admin.programs.index') }}" style="background: white; border: 1px solid #e2e8f0; padding: 10px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; color: #64748b;">Kembali</a>
    </div>

    <form action="{{ route('admin.programs.update', $program->id) }}" method="POST">
        @csrf @method('PUT')
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
            <div>
                <div class="form-section-title"><i class="fas fa-edit text-green-500"></i> Edit Detail Program</div>

                {{-- Gunakan name="nama" --}}
                <div class="form-group">
                    <label class="form-label required">Nama Program</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $program->nama) }}" required>
                    @error('nama') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required">Kode Akses Masuk</label>
                    <input type="text" name="kode_program" id="kode_program" class="form-control font-mono font-bold" value="{{ old('kode_program', $program->kode_program) }}" required>
                    <small style="color:#94a3b8;">Kode ini digunakan peserta untuk mendaftar masuk ke program.</small>
                    @error('kode_program') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Gunakan name="perusahaan" --}}
                <div class="form-group">
                    <label class="form-label">Instansi / Perusahaan</label>
                    <input type="text" name="perusahaan" class="form-control" value="{{ old('perusahaan', $program->perusahaan) }}">
                </div>

                <div class="form-group">
                    <label class="form-label required">Mitra (PIC)</label>
                    <select name="id_mitra" class="form-control" required>
                        @foreach($mitras as $m) <option value="{{ $m->id }}" {{ $program->id_mitra == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option> @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi & Tujuan</label>
                    <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $program->deskripsi) }}</textarea>
                </div>
            </div>
            <div>
                <div class="form-section-title"><i class="fas fa-calendar text-green-500"></i> Pengaturan & Jadwal</div>
                <div class="form-group">
                    <label class="form-label required">Tgl Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ \Carbon\Carbon::parse($program->tanggal_mulai)->format('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label required">Tgl Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ \Carbon\Carbon::parse($program->tanggal_selesai)->format('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Maks. Peserta (Kuota)</label>
                    <input type="number" name="maks_peserta" class="form-control" value="{{ old('maks_peserta', $program->maks_peserta) }}">
                </div>
                <div class="form-group mt-6">
                    <label class="form-label">Status Publikasi</label>
                    <div class="toggle-wrapper">
                        <label class="switch"><input type="checkbox" name="aktif" value="1" {{ old('aktif', $program->aktif) ? 'checked' : '' }}><span class="slider"></span></label>
                        <div>
                            <span style="font-weight: 600; display:block;">Aktifkan Publikasi</span>
                            <span style="font-size: 0.75rem; color:#64748b;">Ubah switch untuk menonaktifkan.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 2rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
            <a href="{{ route('admin.programs.index') }}" style="padding: 10px 24px; color: #64748b; font-weight: 600; text-decoration: none;">Batal</a>
            <button type="submit" class="btn-save">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $('#kode_program').on('input', function() {
        $(this).val($(this).val().toUpperCase().replace(/\s+/g, ''));
    });
    $('#tanggal_mulai').on('change', function() {
        $('#tanggal_selesai').attr('min', $(this).val());
    });
</script>
@endpush

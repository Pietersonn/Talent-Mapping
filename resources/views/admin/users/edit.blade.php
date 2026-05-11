@extends('admin.layouts.app')

@section('title', 'Edit Pengguna')

@push('styles')
<style>
    .border-red-500 { border-color: #ef4444 !important; }
    .text-red-500 { color: #ef4444 !important; }

    /* --- STYLE TOMBOL --- */
    .btn-add {
        background: #22c55e;
        color: white;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3);
    }
    .btn-add:hover { background: #16a34a; transform: translateY(-1px); }

    .btn-cancel {
        background: white; color: #64748b; border: 1px solid #e2e8f0;
        padding: 10px 24px; border-radius: 12px; font-weight: 600; font-size: 0.9rem;
        text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;
    }
    .btn-cancel:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; }

    /* --- FORM CARD & INPUTS --- */
    .form-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #0f172a; background-color: #f8fafc; transition: all 0.2s; }
    .form-control:focus { background-color: white; border-color: #22c55e; outline: none; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1); }

    /* Password Eye */
    .password-wrapper { position: relative; }
    .btn-toggle-password { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; transition: color 0.2s; }
    .btn-toggle-password:hover { color: #22c55e; }

    /* Switch */
    .toggle-wrapper { display: flex; align-items: center; gap: 12px; padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; }
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: #22c55e; }
    input:checked + .slider:before { transform: translateX(20px); }

    /* Divider */
    .form-section-title { font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; }
    .form-actions { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; }
</style>
@endpush

@section('header')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title" style="font-size: 1.5rem; font-weight: 800; color: #0f172a;"><i class="fas fa-user-edit text-green-500 mr-2"></i> Edit Pengguna</h1>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="form-card fade-in-up">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;">

            <div>
                <div class="form-section-title"><i class="far fa-id-card mr-2 text-green-500"></i> Identitas Pengguna</div>

                <div class="form-group">
                    <label class="form-label required">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control @error('nama') border-red-500 @enderror" value="{{ old('nama', $user->nama) }}" required>
                    @error('nama') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required">Alamat Email</label>
                    <input type="email" name="email" class="form-control @error('email') border-red-500 @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="nomor_telepon" class="form-control @error('nomor_telepon') border-red-500 @enderror" value="{{ old('nomor_telepon', $user->nomor_telepon) }}">
                    @error('nomor_telepon') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <div class="form-section-title"><i class="fas fa-user-shield mr-2 text-green-500"></i> Akses & Keamanan</div>

                <div class="form-group">
                    <label class="form-label required">Peran (Role)</label>
                    <select name="peran" class="form-control @error('peran') border-red-500 @enderror" required>
                        <option value="peserta" {{ old('peran', $user->peran) == 'peserta' ? 'selected' : '' }}>Peserta</option>
                        <option value="mitra" {{ old('peran', $user->peran) == 'mitra' ? 'selected' : '' }}>Mitra (PIC)</option>
                        <option value="admin" {{ old('peran', $user->peran) == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('peran') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Kata Sandi Baru <span style="color:#94a3b8; font-weight:normal; font-size:0.75rem;">(Opsional)</span></label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="form-control @error('password') border-red-500 @enderror">
                            <button type="button" class="btn-toggle-password" onclick="togglePassword('password', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ulangi Sandi</label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirm" class="form-control @error('password') border-red-500 @enderror">
                            <button type="button" class="btn-toggle-password" onclick="togglePassword('password_confirm', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div style="grid-column: span 2; margin-top: -15px;">
                        @error('password') <span class="text-xs text-red-500 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status Akun</label>
                    <div class="toggle-wrapper">
                        <label class="switch">
                            <input type="checkbox" name="aktif" value="1" {{ old('aktif', $user->aktif) ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem; color: #1e293b;">Aktifkan Akun</div>
                            <div style="font-size: 0.75rem; color: #64748b;">User dapat login ke dalam sistem</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-add">
                <i class="fas fa-save"></i> Perbarui Data
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush

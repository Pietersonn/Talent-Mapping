@extends('admin.layouts.app')

@section('title', 'Tambah Pengguna Baru')

@push('styles')
<style>
    /* --- STYLE TOMBOL (FIXED) --- */
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
    .btn-add:hover {
        background: #16a34a;
        transform: translateY(-1px);
        box-shadow: 0 6px 8px -1px rgba(34, 197, 94, 0.4);
    }

    .btn-cancel {
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-cancel:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    /* --- FORM CARD & INPUTS --- */
    .form-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
    .form-label.required::after { content: "*"; color: #ef4444; margin-left: 4px; }

    .form-control {
        width: 100%; padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0; border-radius: 10px;
        font-size: 0.9rem; color: #0f172a;
        background-color: #f8fafc; transition: all 0.2s;
    }
    .form-control:focus {
        background-color: white; border-color: #22c55e;
        outline: none; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
    }

    /* Password Eye */
    .password-wrapper { position: relative; }
    .btn-toggle-password {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: #94a3b8; cursor: pointer;
        transition: color 0.2s;
    }
    .btn-toggle-password:hover { color: #22c55e; }

    /* Switch Custom */
    .toggle-wrapper {
        display: flex; align-items: center; gap: 12px;
        padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;
    }
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: #22c55e; }
    input:checked + .slider:before { transform: translateX(20px); }

    /* Section Divider */
    .form-section-title {
        font-size: 0.85rem; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.05em;
        border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem;
    }

    /* Action Buttons Area */
    .form-actions {
        margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;
        display: flex; justify-content: flex-end; gap: 10px;
    }
</style>
@endpush

@section('header')
    <div class="header-wrapper">
        <div>
            <h1 class="page-title"><i class="fas fa-user-plus"></i> Tambah Pengguna Baru</h1>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="form-card fade-in-up">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;">

            <div>
                <div class="form-section-title"><i class="far fa-id-card mr-2"></i> Identitas Pengguna</div>

                <div class="form-group">
                    <label class="form-label required">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required>
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required">Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nama@perusahaan.com" required>
                    @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}" placeholder="0812xxxx">
                </div>
            </div>

            <div>
                <div class="form-section-title"><i class="fas fa-user-shield mr-2"></i> Akses & Keamanan</div>

                <div class="form-group">
                    <label class="form-label required">Peran (Role)</label>
                    <select name="role" class="form-control" style="background-image: none;" required>
                        <option value="">-- Pilih Peran --</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Pengguna (Peserta)</option>
                        <option value="pic" {{ old('role') == 'pic' ? 'selected' : '' }}>PIC (Event Manager)</option>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label required">Kata Sandi</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="form-control" placeholder="******" required>
                            <button type="button" class="btn-toggle-password" onclick="togglePassword('password', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Ulangi Sandi</label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirm" class="form-control" placeholder="******" required>
                            <button type="button" class="btn-toggle-password" onclick="togglePassword('password_confirm', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status Akun</label>
                    <div class="toggle-wrapper">
                        <label class="switch">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
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
                <i class="fas fa-save"></i> Simpan Data
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

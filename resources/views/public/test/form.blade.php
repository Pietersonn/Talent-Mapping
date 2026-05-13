@extends('public.layouts.app')

@section('title', 'Daftar Test - TalentMapping')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/public/css/pages/test.css') }}">
@endsection

@section('content')
    <div class="test-registration-container">
        <div class="test-hero">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-overlay">
                        <h1>Langkah Pertama Menuju Pemahaman Diri Sendiri</h1>
                        <p>Isi data diri Anda untuk memulai tes dan temukan potensi serta kekuatan yang akan membantu Anda
                            merencanakan karier yang lebih tepat.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress global (Register) --}}
        @php($progress = 0)
        @include('public.test.partials.progress-stepper', ['progress' => $progress])

        <div class="form-section">
            <div class="container">
                <div class="form-container">
                    <h2>Lengkapi Data Dibawah ini :</h2>

                    <form method="POST" action="{{ route('test.form.store') }}" class="test-form js-loading-form">
                        @csrf

                        <div class="form-grid">
                            <div class="form-column">
                                <div class="form-group">
                                    <label for="full_name">Nama Lengkap</label>
                                    {{-- name="full_name" Sesuai dengan Controller --}}
                                    <input type="text" id="full_name" name="full_name"
                                        value="{{ old('full_name', auth()->user()->nama ?? '') }}" required
                                        placeholder="Wajib di isi" class="form-input">
                                    @error('full_name')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email"
                                        value="{{ old('email', auth()->user()->email ?? '') }}" required
                                        placeholder="Wajib di isi" class="form-input">
                                    @error('email')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="workplace">Latar Belakang</label>
                                    {{-- name="workplace" Sesuai dengan Controller --}}
                                    <input type="text" id="workplace" name="workplace" value="{{ old('workplace') }}"
                                        required placeholder="Sekolah/Unit Kerja/Universitas" class="form-input">
                                    @error('workplace')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="position">Position (Jabatan)</label>
                                    <input id="position" type="text" name="position"
                                        class="form-input @error('position') is-invalid @enderror"
                                        placeholder="Your position" value="{{ old('position') }}">
                                    @error('position')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="event_id">Event/Program/Dll</label>
                                    {{-- name="event_id" Sesuai dengan Controller --}}
                                    <select id="event_id" name="event_id" class="form-select">
                                        <option value="">Tidak ada</option>
                                        @foreach ($activePrograms as $program)
                                            <option value="{{ $program->id }}"
                                                {{ old('event_id') == $program->id ? 'selected' : '' }}>
                                                {{ $program->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('event_id')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                    <div class="input-help">
                                        *Isi apabila mengikuti event untuk mengisi talent mapping
                                    </div>
                                </div>

                                <div class="form-group" id="eventCodeGroup" style="display: none;">
                                    <label for="event_code">Access Code</label>
                                    <input type="text" id="event_code" name="event_code" value="{{ old('event_code') }}"
                                        placeholder="Masukkan kode event" class="form-input">
                                    @error('event_code')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                    <div class="code-validation" id="codeValidation"></div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-submit" id="submitBtn" disabled>Kirim &
                                        Lanjutkan</button>
                                </div>
                            </div>

                            <div class="illustration-column">
                                <div class="illustration">
                                    <div class="floating-elements">
                                        <div class="dot dot-1"></div>
                                        <div class="dot dot-2"></div>
                                        <div class="dot dot-3"></div>
                                        <div class="dot dot-4"></div>
                                        <div class="dot dot-5"></div>
                                        <div class="line line-1"></div>
                                        <div class="line line-2"></div>
                                        <div class="line line-3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const eventSelect = document.getElementById('event_id');
            const eventCodeGroup = document.getElementById('eventCodeGroup');
            const eventCodeInput = document.getElementById('event_code');
            const submitBtn = document.getElementById('submitBtn');
            const codeValidation = document.getElementById('codeValidation');

            // Store event codes for validation
            const eventCodes = {
                @foreach ($activePrograms as $program)
                    '{{ $program->id }}': '{{ $program->kode_program }}',
                @endforeach
            };

            // Handle event selection
            eventSelect.addEventListener('change', function() {
                if (this.value) {
                    eventCodeGroup.style.display = 'block';
                    eventCodeInput.required = true;
                    submitBtn.disabled = true;
                    codeValidation.innerHTML = '';
                } else {
                    eventCodeGroup.style.display = 'none';
                    eventCodeInput.required = false;
                    eventCodeInput.value = '';
                    codeValidation.innerHTML = '';
                    validateForm();
                }
            });

            // Event code validation
            eventCodeInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
                validateEventCode();
            });

            function validateEventCode() {
                const selectedEventId = eventSelect.value;
                const inputCode = eventCodeInput.value.trim();

                if (!selectedEventId || !inputCode) {
                    codeValidation.innerHTML = '';
                    submitBtn.disabled = true;
                    return;
                }

                const correctCode = eventCodes[selectedEventId];

                if (inputCode === correctCode) {
                    codeValidation.innerHTML =
                        '<span class="validation-success"><i class="icon-check"></i> Kode event benar</span>';
                    validateForm();
                } else {
                    codeValidation.innerHTML =
                        '<span class="validation-error"><i class="icon-times"></i> Kode event tidak sesuai</span>';
                    submitBtn.disabled = true;
                }
            }

            function validateForm() {
                const requiredFields = document.querySelectorAll('input[required], select[required]');
                let allValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        allValid = false;
                    }
                });

                if (eventSelect.value && (!eventCodeInput.value || !codeValidation.querySelector('.validation-success'))) {
                    allValid = false;
                }

                submitBtn.disabled = !allValid;
            }

            const allInputs = document.querySelectorAll('input[required], select[required]');
            allInputs.forEach(input => {
                input.addEventListener('input', validateForm);
                input.addEventListener('change', validateForm);
            });

            validateForm();

            const form = document.querySelector('.test-form');
            form.addEventListener('submit', function(e) {
                if (eventSelect.value) {
                    const selectedEventId = eventSelect.value;
                    const inputCode = eventCodeInput.value.trim();
                    const correctCode = eventCodes[selectedEventId];

                    if (inputCode !== correctCode) {
                        e.preventDefault();
                        alert('Kode event tidak sesuai. Harap periksa kembali.');
                        return false;
                    }
                }

                submitBtn.disabled = true;
                submitBtn.textContent = 'Memproses...';
                submitBtn.classList.add('loading');
            });

            const dots = document.querySelectorAll('.dot');
            dots.forEach((dot, index) => {
                dot.style.animationDelay = `${index * 0.5}s`;
            });
        });
    </script>
@endsection

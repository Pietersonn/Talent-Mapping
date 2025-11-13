@extends('public.layouts.app', ['hideFooter' => true])

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/public/css/pages/st30-test.css') }}">
    @endpush

    <div class="st30-test-container">
        <!-- Hero Section -->
        <div class="st30-hero">
            <div class="st30-hero-content">
                <h1 class="st30-title">
                    @if ($stage == 1)
                        ST-30 Paling Cocok (Section 1)
                    @elseif($stage == 2)
                        ST-30 Paling Tidak Cocok (Section 2)
                    @elseif($stage == 3)
                        ST-30 Cocok (Section 3)
                    @else
                        ST-30 Tidak Cocok (Section 4)
                    @endif
                </h1>

                <div class="st30-instruction">
                    @if ($stage == 1)
                        Dari pernyataan berikut, <strong>pilihlah 5 -7 pernyataan yang paling cocok </strong>dengan gambaran
                        diri anda.
                    @elseif($stage == 2)
                        Dari pernyataan berikut, <strong>pilihlah 5 -7 pernyataan yang paling tidak cocok </strong>dengan
                        gambaran diri anda.
                    @elseif($stage == 3)
                        Dari pernyataan berikut, <strong>pilihlah 5 -7 pernyataan yang cocok </strong>dengan gambaran diri
                        anda.
                    @else
                        Dari pernyataan berikut, <strong>pilihlah 5 -7 pernyataan yang tidak cocok </strong>dengan gambaran
                        diri anda.
                    @endif
                </div>
            </div>
        </div>

        {{-- PROGRESS: stepper pendek & seragam --}}
        @include('public.test.partials.progress-stepper', ['progress' => $progress])

        <div class="st30-selection-note st30-selection-note--inline">
            <span class="note-star" aria-hidden="true">*</span>
            <span class="note-text">
                @if ($stage == 1)
                    Pilih 5–7 Yang Paling Sesuai Sama Kamu
                @elseif($stage == 2)
                    Pilih 5–7 Yang Paling Tidak Sesuai Sama Kamu
                @elseif($stage == 3)
                    Pilih 5–7 Yang Cukup Sesuai Sama Kamu
                @else
                    Pilih 5–7 Yang Kurang Sesuai Sama Kamu
                @endif
            </span>
        </div>

        <!-- Questions Form -->
        <div class="st30-questions-section">
            <form id="st30Form" action="{{ route('test.st30.stage.store', $stage) }}" method="POST"
                class="js-loading-form">
                @csrf
                <div class="st30-questions-list">
                    @foreach ($availableQuestions as $question)
                        <div class="st30-question-item">
                            <label class="st30-question-label">
                                <div class="st30-question-content">
                                    <input type="checkbox" name="selected_questions[]" value="{{ $question->id }}"
                                        class="st30-checkbox">
                                    <span class="st30-number">{{ $question->number }}.</span>
                                    <span class="st30-text">{{ $question->statement }}</span>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="st30-counter" role="status" aria-live="polite" data-total="7">
                    <span class="st30-count">0</span>/<span class="st30-total">7</span>
                </div>

                <!-- Action Buttons -->
                <div class="st30-actions">
                    @if ($stage > 1)
                        <a href="{{ route('test.st30.stage', $stage - 1) }}" class="st30-btn st30-btn-back js-loading-link">
                            Kembali
                        </a>
                    @else
                        <div></div>
                    @endif


                    <button type="submit" id="submitBtn" class="st30-btn st30-btn-primary" disabled>
                        @if ($stage < 4)
                            Kirim & Lanjutkan
                        @else
                            Kirim & Lanjutkan
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM loaded');

                const checkboxes = document.querySelectorAll('.st30-checkbox');
                const submitBtn = document.getElementById('submitBtn');
                const form = document.getElementById('st30Form');
                const counter = document.querySelector('.st30-counter');

                console.log('Found checkboxes:', checkboxes.length);
                console.log('Found submit button:', !!submitBtn);
                console.log('Found counter:', !!counter);

                function updateSubmitButton() {
                    const checkedCount = document.querySelectorAll('.st30-checkbox:checked').length;
                    const isValid = checkedCount >= 5 && checkedCount <= 7;

                    console.log('Checked count:', checkedCount, 'Valid:', isValid);

                    if (submitBtn) {
                        submitBtn.disabled = !isValid;
                    }

                    if (counter) {
                        counter.textContent = `${checkedCount}/7 dipilih`;
                        counter.className = `st30-counter ${isValid ? 'valid' : 'invalid'}`;
                    }
                }

                checkboxes.forEach((checkbox, index) => {
                    console.log('Adding listener to checkbox', index);

                    checkbox.addEventListener('change', function(e) {
                        console.log('Checkbox changed:', this.checked, this.value);

                        updateSubmitButton();

                        const questionItem = this.closest('.st30-question-item');
                        if (questionItem) {
                            if (this.checked) {
                                questionItem.classList.add('selected');
                            } else {
                                questionItem.classList.remove('selected');
                            }
                        }
                    });
                });

                if (form) {
                    form.addEventListener('submit', function(e) {
                        const checkedCount = document.querySelectorAll('.st30-checkbox:checked').length;

                        console.log('Form submit - checked count:', checkedCount);

                        if (checkedCount < 5 || checkedCount > 7) {
                            e.preventDefault();
                            alert('Anda harus memilih 5-7 pernyataan. Saat ini: ' + checkedCount +
                                ' pernyataan.');
                            return false;
                        }

                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.textContent = 'Menyimpan...';
                        }
                    });
                }


                updateSubmitButton();

                // Backup manual click detection
                document.addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('st30-checkbox')) {
                        console.log('Manual click detection:', e.target.checked, e.target.value);
                        setTimeout(updateSubmitButton, 10);
                    }
                });


            });
        </script>
    @endpush
@endsection

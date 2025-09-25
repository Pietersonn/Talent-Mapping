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
                        Kenali Sisi Terbaik dari Diri Anda
                    @elseif($stage == 2)
                        Lepaskan Hal yang Bukan Milik Anda
                    @elseif($stage == 3)
                        Pilih Hal yang Cukup Mewakili Diri Anda
                    @else
                        Identifikasi Area yang Perlu Pengembangan
                    @endif
                </h1>

                <div class="st30-instruction">
                    @if ($stage == 1)
                        Inilah saatnya melihat lebih dalam pada diri Anda. <strong>Pilih 5-7 pernyataan yang paling sesuai
                            menggambarkan kemampuan, cara berpikir, dan karakter Anda</strong>, semakin jelas Anda menilai,
                        semakin akurat hasil gambaran potensi yang akan tersaji.
                    @elseif($stage == 2)
                        Karena kelebihan diri untuk mencapai skill jadi. <strong>Pilih 5-7 pernyataan yang paling tidak
                            sesuai</strong> dengan kemampuan dan karakter Anda. Dengan begitu, Anda akan bisa lebih
                        mengetahui potensi sejati anda untuk berkembang menjadi yang terbaik.
                    @elseif($stage == 3)
                        Tidak semua yang sesuai harus menjadi kekuatan utama, tapi setiap potongan tetap berperan dalam
                        membentuk siapa Anda. <strong>Pilih 5-7 pernyataan yang cukup menggambarkan diri Anda</strong> dari
                        sisa pernyataan yang belum terpilih.
                    @else
                        Langkah terakhir untuk melengkapi peta kompetensi Anda. <strong>Pilih 5-7 pernyataan yang kurang
                            sesuai</strong> dari pilihan Stage 3 untuk memperjelas area pengembangan diri Anda.
                    @endif
                </div>
            </div>
        </div>

        {{-- PROGRESS: stepper pendek & seragam --}}
        @include('public.test.partials.progress-stepper', ['progress' => $progress])

        {{-- Instruksi “Pilih 5–7 …” kiri, tanpa background --}}
        {{-- Instruksi “Pilih 5–7 …” agak ke kanan, bintang merah --}}
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
                class="js-loading-form">>
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

                <!-- Action Buttons -->
                <div class="st30-actions">
                    @if ($stage > 1)
                        <button type="button" class="st30-btn st30-btn-back js-loading-link" onclick="history.back()">
                            Kembali
                        </button>
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

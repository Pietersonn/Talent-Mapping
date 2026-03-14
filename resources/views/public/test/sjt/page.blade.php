@extends('public.layouts.app', ['hideFooter' => true])

@section('content')
    {{-- PASTIKAN CSS STYLES DILOAD DI SINI ATAU DI LAYOUT --}}
    @push('styles')
        {{-- Jika Anda memuat CSS terpisah --}}
        {{-- <link rel="stylesheet" href="{{ asset('assets/public/css/pages/sjt-test.css') }}"> --}}
    @endpush

    <div class="sjt-test-container">
        <div class="sjt-hero">
            <div class="sjt-hero-content">
                <h1 class="sjt-title">Talent Competency </h1>
                <p class="sjt-instruction">
                    Mohon mengisi dengan jujur dan sesuai keadaan Anda saat ini. Tidak ada jawaban yang benar atau
                    salah, karena seluruh isian mencerminkan diri Anda secara pribadi.
                </p>
                {{-- Tambahkan class 'sjt-reminder-text' pada paragraf ini --}}
                <p class="sjt-reminder-text">
                    {{-- Bungkus 'Harap diingat:' dengan span dan class 'highlight-red' --}}
                    <strong class="highlight-red">Harap diingat:</strong>
                    Jawaban yang Anda berikan akan mempengaruhi hasil akhir, sehingga penting untuk menjawab dengan
                    reflektif dan apa adanya, agar hasil yang diperoleh benar-benar sesuai dengan karakter, kekuatan,
                    dan area pengembangan diri Anda.
                </p>
            </div>
        </div>
        {{-- PROGRESS: stepper pendek & seragam --}}
        @include('public.test.partials.progress-stepper', ['progress' => $progress])

        <div class="sjt-questions-section">
            <form id="sjtForm" action="{{ route('test.sjt.page.store', $page) }}" method="POST" class="js-loading-form">
                @csrf
                <input type="hidden" name="session_id" value="{{ $session->id ?? '' }}">
                <div class="sjt-questions-list">
                    @foreach ($questions as $question)
                        <div class="sjt-question-block">
                            <div class="sjt-question-header">
                                {{ $question->number }}. {{ $question->question_text }}
                            </div>
                            <div class="sjt-question-content">
                                <div class="sjt-options-list">
                                    @foreach ($question->options as $option)
                                        @php
                                            $isSelected =
                                                isset($existingResponses[$question->id]) &&
                                                $existingResponses[$question->id]->selected_option ===
                                                    $option->option_letter;
                                        @endphp

                                        <label class="sjt-option-item {{ $isSelected ? 'selected' : '' }}"
                                            data-question="{{ $question->id }}">
                                            <input type="radio" name="responses[{{ $question->id }}]"
                                                value="{{ $option->option_letter }}" class="sjt-radio"
                                                data-question="{{ $question->id }}" {{ $isSelected ? 'checked' : '' }}>
                                            <span
                                                class="sjt-option-letter">{{ strtoupper($option->option_letter) }}.</span>
                                            <span class="sjt-option-text">{{ $option->option_text }}</span>
                                        </label>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="sjt-answer-status incomplete" id="answerStatus">
                        Lengkapi semua jawaban untuk melanjutkan (0/{{ $questions->count() }} dijawab)
                    </div>
                </div>

                <div class="sjt-actions">
                    @if ($page > 1)
                        {{-- Tombol Kembali diset ke page - 1 --}}
                        <a href="{{ route('test.sjt.page', $page - 1) }}" class="sjt-btn sjt-btn-back js-loading-link">
                            Kembali
                        </a>
                    @else
                        <div></div>
                    @endif

                    <button type="submit" id="submitBtn" class="sjt-btn sjt-btn-primary" disabled>
                        @if ($page < 5)
                            Kirim & Lanjutkan
                        @else
                            Selesaikan Test
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ❌ Matikan auto scroll restoration browser
                if ('scrollRestoration' in history) {
                    history.scrollRestoration = 'manual';
                }

                const initSJT = () => {
                    const container = document.querySelector('.sjt-test-container');
                    if (!container) return;

                    const form = container.querySelector('#sjtForm');
                    const submitBtn = container.querySelector('#submitBtn');
                    const answerStatus = container.querySelector('#answerStatus');
                    const radios = Array.from(container.querySelectorAll('.sjt-radio'));
                    const totalQuestions = Number({{ $questions->count() }});
                    const currentPage = Number({{ $page }}); // Ambil nomor halaman saat ini
                    const isLastPage = currentPage === 5; // ASUMSI: Halaman terakhir adalah Page 5

                    const updateAnswerStatus = () => {
                        const answered = new Set();
                        radios.forEach(radio => {
                            if (radio.checked) answered.add(radio.dataset.question);
                        });
                        const answeredCount = answered.size;
                        const allAnswered = answeredCount === totalQuestions;

                        answerStatus.textContent = allAnswered ?
                            `Semua pertanyaan telah dijawab (${answeredCount}/${totalQuestions})` :
                            `Lengkapi semua jawaban (${answeredCount}/${totalQuestions} dijawab)`;

                        answerStatus.className = allAnswered ? 'sjt-answer-status complete' :
                            'sjt-answer-status incomplete';
                        submitBtn.disabled = !allAnswered;
                    };

                    radios.forEach(radio => {
                        radio.addEventListener('change', e => {
                            const questionBlock = e.target.closest('.sjt-question-block');
                            questionBlock.querySelectorAll('.sjt-option-item')
                                .forEach(i => i.classList.remove('selected'));
                            e.target.closest('.sjt-option-item').classList.add('selected');
                            updateAnswerStatus();
                        });
                    });

                    updateAnswerStatus();

                    // 🚀 Submit logic dengan SweetAlert
                    form.addEventListener('submit', async e => {
                        e.preventDefault();

                        // Menonaktifkan tombol saat submit dimulai
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Menyimpan...';

                        const fd = new FormData(form);
                        try {
                            const res = await fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: fd,
                                credentials: 'same-origin'
                            });

                            const data = await res.json().catch(() => null);
                            const next = data?.next || res.url;

                            if (isLastPage) {
                                // ✅ Tampilkan SweetAlert, lalu langsung redirect tanpa tunggu
                                Swal.fire({
                                    title: 'Tes Selesai!',
                                    text: 'Terima kasih telah menyelesaikan test.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didClose: () => {
                                        window.location.replace(next);
                                    }
                                });

                            } else {
                                // Halaman biasa, langsung pindah
                                window.location.replace(next);
                            }

                        } catch (err) {
                            console.error('Submit gagal:', err);
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                            submitBtn.disabled = false;
                            submitBtn.textContent = isLastPage ? 'Selesaikan Test' :
                                'Kirim & Lanjutkan';
                        }
                    });
                };

                initSJT();
                window.addEventListener('popstate', () => setTimeout(initSJT, 50));
            });
        </script>
    @endpush
@endsection

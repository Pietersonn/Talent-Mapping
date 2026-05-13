@extends('public.layouts.app', ['hideFooter' => true])

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/public/css/pages/tk-test.css') }}">
    @endpush

    <div class="tk-test-container">
        <div class="tk-hero">
            <div class="tk-hero-content">
                <h1 class="tk-title">Talenta Kompetensi (TK)</h1>
                <p class="tk-instruction">
                    Mohon mengisi dengan jujur dan sesuai keadaan Anda saat ini. Tidak ada jawaban yang benar atau
                    salah, karena seluruh isian mencerminkan diri Anda secara pribadi.
                </p>
                <p class="tk-reminder-text">
                    <strong class="highlight-red">Harap diingat:</strong>
                    Jawaban yang Anda berikan akan mempengaruhi hasil akhir, sehingga penting untuk menjawab dengan
                    reflektif dan apa adanya, agar hasil yang diperoleh benar-benar sesuai dengan karakter, kekuatan,
                    dan area pengembangan diri Anda.
                </p>
            </div>
        </div>

        {{-- PROGRESS: stepper --}}
        @php
            $totalPages = 5; // Asumsi total halaman adalah 5, sama seperti di logic controller
            $progress = 50 + (($page / $totalPages) * 50)
        @endphp
        @include('public.test.partials.progress-stepper', ['progress' => $progress])

        <div class="tk-questions-section">
            <form id="tkForm" action="{{ route('test.tk.page.store', $page) }}" method="POST" class="js-loading-form">
                @csrf
                <input type="hidden" name="session_id" value="{{ $session->id ?? '' }}">
                <input type="hidden" name="version_id" value="{{ $activeVersion->id }}">

                <div class="tk-questions-list">
                    @foreach ($questions as $question)
                        <div class="tk-question-block">
                            <div class="tk-question-header">
                                {{ $question->nomor }}. {{ $question->teks_pertanyaan }}
                            </div>
                            <div class="tk-question-content">
                                <div class="tk-options-list">
                                    {{-- Mengambil nilai $answered dari existingResponses untuk mengecek apa yang dipilih --}}
                                    @php
                                        $answeredOpt = isset($existingResponses[$question->id]) ? $existingResponses[$question->id]->pilihan_dipilih : null;
                                    @endphp

                                    {{-- Lakukan looping dari tabel pilihan_tk melalui relasi 'options' --}}
                                    @foreach($question->options as $option)
                                        @php
                                            $isSelected = ($answeredOpt === $option->huruf_pilihan);
                                        @endphp

                                        <label class="tk-option-item {{ $isSelected ? 'selected' : '' }}"
                                            data-question="{{ $question->id }}">
                                            <input type="radio" name="answers[{{ $question->id }}][option_id]"
                                                value="{{ $option->huruf_pilihan }}" class="tk-radio"
                                                data-question="{{ $question->id }}" {{ $isSelected ? 'checked' : '' }}>
                                            <input type="hidden" name="answers[{{ $question->id }}][question_id]" value="{{ $question->id }}">
                                            <span class="tk-option-letter">{{ strtoupper($option->huruf_pilihan) }}.</span>
                                            <span class="tk-option-text">{{ $option->teks_pilihan }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="tk-answer-status incomplete" id="answerStatus">
                        Lengkapi semua jawaban untuk melanjutkan (0/{{ $questions->count() }} dijawab)
                    </div>
                </div>

                <div class="tk-actions">
                    @if ($page > 1)
                        <a href="{{ route('test.tk.page', $page - 1) }}" class="tk-btn tk-btn-back js-loading-link">
                            Kembali
                        </a>
                    @else
                        <div></div>
                    @endif

                    <button type="submit" id="submitBtn" class="tk-btn tk-btn-primary" disabled>
                        @if ($page < $totalPages)
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
                if ('scrollRestoration' in history) {
                    history.scrollRestoration = 'manual';
                }

                const initTK = () => {
                    const container = document.querySelector('.tk-test-container');
                    if (!container) return;

                    const form = container.querySelector('#tkForm');
                    const submitBtn = container.querySelector('#submitBtn');
                    const answerStatus = container.querySelector('#answerStatus');
                    const radios = Array.from(container.querySelectorAll('.tk-radio'));
                    const totalQuestions = Number({{ $questions->count() }});
                    const currentPage = Number({{ $page }});
                    const isLastPage = currentPage === Number({{ $totalPages }});

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

                        answerStatus.className = allAnswered ? 'tk-answer-status complete' :
                            'tk-answer-status incomplete';
                        submitBtn.disabled = !allAnswered;
                    };

                    radios.forEach(radio => {
                        radio.addEventListener('change', e => {
                            const questionBlock = e.target.closest('.tk-question-block');
                            questionBlock.querySelectorAll('.tk-option-item')
                                .forEach(i => i.classList.remove('selected'));
                            e.target.closest('.tk-option-item').classList.add('selected');
                            updateAnswerStatus();
                        });
                    });

                    updateAnswerStatus();

                    form.addEventListener('submit', async e => {
                        e.preventDefault();
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Menyimpan...';

                        const answers = [];
                        const formData = new FormData(form);
                        for(let [key, value] of formData.entries()) {
                            if(key.includes('[option_id]')) {
                                let questionId = key.match(/\[(.*?)\]/)[1];
                                answers.push({
                                    question_id: questionId,
                                    option_id: value
                                });
                            }
                        }

                        try {
                            const res = await fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    answers: answers,
                                    version_id: form.querySelector('input[name="version_id"]').value
                                })
                            });

                            const data = await res.json();
                            const next = data.redirect || res.url;

                            if (isLastPage) {
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
                                window.location.replace(next);
                            }

                        } catch (err) {
                            console.error('Submit gagal:', err);
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                            submitBtn.disabled = false;
                            submitBtn.textContent = isLastPage ? 'Selesaikan Test' : 'Kirim & Lanjutkan';
                        }
                    });
                };

                initTK();
                window.addEventListener('popstate', () => setTimeout(initTK, 50));
            });
        </script>
    @endpush
@endsection

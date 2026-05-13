@extends('public.layouts.app', ['hideFooter' => true])

@section('content')
    {{-- PASTIKAN CSS STYLES DILOAD DI SINI ATAU DI LAYOUT --}}
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/public/css/pages/sjt-test.css') }}">
    @endpush

    <div class="sjt-test-container">
        <div class="sjt-hero">
            <div class="sjt-hero-content">
                <h1 class="sjt-title">Talenta Kompetensi </h1>
                <p class="sjt-instruction">
                    Mohon mengisi dengan jujur dan sesuai keadaan Anda saat ini. Tidak ada jawaban yang benar atau
                    salah, karena seluruh isian mencerminkan diri Anda secara pribadi.
                </p>
                <p class="sjt-reminder-text">
                    <strong class="highlight-red">Harap diingat:</strong>
                    Jawaban yang Anda berikan akan mempengaruhi hasil akhir, sehingga penting untuk menjawab dengan
                    reflektif dan apa adanya, agar hasil yang diperoleh benar-benar sesuai dengan karakter, kekuatan,
                    dan area pengembangan diri Anda.
                </p>
            </div>
        </div>

        {{-- PROGRESS: stepper --}}
        @php($progress = 50 + (($page / $totalPages) * 50))
        @include('public.test.partials.progress-stepper', ['progress' => $progress])

        <div class="sjt-questions-section">
            <form id="sjtForm" action="{{ route('test.tk.page.store', $page) }}" method="POST" class="js-loading-form">
                @csrf
                <input type="hidden" name="session_id" value="{{ $session->id ?? '' }}">
                <input type="hidden" name="version_id" value="{{ $tkVersion->id }}">

                <div class="sjt-questions-list">
                    @foreach ($questions as $question)
                        <div class="sjt-question-block">
                            <div class="sjt-question-header">
                                {{ $question->nomor }}. {{ $question->teks_pertanyaan }}
                            </div>
                            <div class="sjt-question-content">
                                <div class="sjt-options-list">
                                    {{-- Menggunakan kolom pilihan_a, pilihan_b, dll --}}
                                    @foreach(['a', 'b', 'c', 'd', 'e'] as $opt)
                                        @if(isset($question->{'pilihan_'.$opt}))
                                            @php
                                                $isSelected = in_array($opt, $answered) && array_search($opt, $answered) == $question->id;
                                            @endphp

                                            <label class="sjt-option-item {{ $isSelected ? 'selected' : '' }}"
                                                data-question="{{ $question->id }}">
                                                <input type="radio" name="answers[{{ $question->id }}][option_id]"
                                                    value="{{ $opt }}" class="sjt-radio"
                                                    data-question="{{ $question->id }}" {{ $isSelected ? 'checked' : '' }}>
                                                <input type="hidden" name="answers[{{ $question->id }}][question_id]" value="{{ $question->id }}">
                                                <span class="sjt-option-letter">{{ strtoupper($opt) }}.</span>
                                                <span class="sjt-option-text">{{ $question->{'pilihan_'.$opt} }}</span>
                                            </label>
                                        @endif
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
                        <a href="{{ route('test.tk.page', $page - 1) }}" class="sjt-btn sjt-btn-back js-loading-link">
                            Kembali
                        </a>
                    @else
                        <div></div>
                    @endif

                    <button type="submit" id="submitBtn" class="sjt-btn sjt-btn-primary" disabled>
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

                const initSJT = () => {
                    const container = document.querySelector('.sjt-test-container');
                    if (!container) return;

                    const form = container.querySelector('#sjtForm');
                    const submitBtn = container.querySelector('#submitBtn');
                    const answerStatus = container.querySelector('#answerStatus');
                    const radios = Array.from(container.querySelectorAll('.sjt-radio'));
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

                    form.addEventListener('submit', async e => {
                        e.preventDefault();
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Menyimpan...';

                        // Format array of objects untuk sesuai dengan request validate
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

                initSJT();
                window.addEventListener('popstate', () => setTimeout(initSJT, 50));
            });
        </script>
    @endpush
@endsection

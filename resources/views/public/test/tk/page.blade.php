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
            $totalPages = 5;
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
                                    @php
                                        $answeredOpt = isset($existingResponses[$question->id]) ? $existingResponses[$question->id]->pilihan_dipilih : null;
                                    @endphp

                                    @foreach($question->options as $option)
                                        @php
                                            $isSelected = ($answeredOpt === $option->huruf_pilihan);
                                        @endphp

                                        <label class="tk-option-item {{ $isSelected ? 'selected' : '' }}"
                                            data-question="{{ $question->id }}">
                                            {{-- UBAH: name disesuaikan dengan validasi Controller (responses[ID_SOAL]) --}}
                                            <input type="radio" name="responses[{{ $question->id }}]"
                                                value="{{ $option->huruf_pilihan }}" class="tk-radio"
                                                data-question="{{ $question->id }}" {{ $isSelected ? 'checked' : '' }}>

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

                            // --- INI KUNCI PERBAIKANNYA: TANGKAP ERROR DARI SERVER ---
                            if (!res.ok) {
                                const errorData = await res.json().catch(() => null);
                                let errorMessage = "Terjadi kesalahan saat memproses jawaban Anda.";

                                if (errorData && errorData.message) {
                                    errorMessage = errorData.message;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Melanjutkan',
                                    text: errorMessage
                                });

                                // Aktifkan tombol kembali agar user tidak stuck
                                submitBtn.disabled = false;
                                submitBtn.textContent = isLastPage ? 'Selesaikan Test' : 'Kirim & Lanjutkan';
                                return; // Hentikan eksekusi di sini, JANGAN RELOAD HALAMAN!
                            }
                            // --------------------------------------------------------

                            const data = await res.json();
                            const next = data.next || res.url;

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
                            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
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

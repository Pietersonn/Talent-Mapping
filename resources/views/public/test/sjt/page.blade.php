    @extends('public.layouts.app', ['hideFooter' => true])

    @section('content')
        <div class="sjt-test-container">
            <!-- Hero Section -->
            <div class="sjt-hero">
                <div class="sjt-hero-content">
                    <h1 class="sjt-title">Situational Judgment Test (SJT)</h1>
                    <p class="sjt-instruction">
                        Bacalah setiap situasi dengan cermat dan pilih respons yang paling tepat menurut Anda.
                        Tidak ada jawaban yang benar atau salah, jawablah sesuai dengan cara Anda menangani situasi tersebut.
                    </p>
                </div>
            </div>
            {{-- PROGRESS: stepper pendek & seragam --}}
            @include('public.test.partials.progress-stepper', ['progress' => $progress])

            <!-- Questions Section -->
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
                        <!-- Answer Status -->
                        <div class="sjt-answer-status incomplete" id="answerStatus">
                            Lengkapi semua jawaban untuk melanjutkan (0/{{ $questions->count() }} dijawab)
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="sjt-actions">
                        @if ($page > 1)
                            <button type="button" class="sjt-btn sjt-btn-back" onclick="history.back()">
                                Kembali
                            </button>
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
                    // buat fungsi init yang binding semuanya — bisa dipanggil ulang setelah DOM replace
                    const initSJT = () => {
                        // ambil elemen dari DOM yg terbaru
                        const container = document.querySelector('.sjt-test-container');
                        if (!container) return;

                        const form = container.querySelector('#sjtForm');
                        const submitBtn = container.querySelector('#submitBtn');
                        const answerStatus = container.querySelector('#answerStatus');
                        let radios = Array.from(container.querySelectorAll('.sjt-radio'));
                        const totalQuestions = Number({{ $questions->count() }});

                        // helper update status
                        const updateAnswerStatus = () => {
                            const answeredQuestions = new Set();
                            radios.forEach(radio => {
                                if (radio.checked) answeredQuestions.add(radio.dataset.question);
                            });
                            const answeredCount = answeredQuestions.size;
                            const allAnswered = answeredCount === totalQuestions;
                            if (answerStatus) {
                                if (allAnswered) {
                                    answerStatus.textContent =
                                        `Semua pertanyaan telah dijawab (${answeredCount}/${totalQuestions})`;
                                    answerStatus.className = 'sjt-answer-status complete';
                                } else {
                                    answerStatus.textContent =
                                        `Lengkapi semua jawaban untuk melanjutkan (${answeredCount}/${totalQuestions} dijawab)`;
                                    answerStatus.className = 'sjt-answer-status incomplete';
                                }
                            }
                            if (submitBtn) submitBtn.disabled = !allAnswered;
                        };

                        // attach radio handlers
                        const attachRadioHandlers = () => {
                            radios.forEach(radio => {
                                // detach old handler if ada
                                if (radio._handler) radio.removeEventListener('change', radio._handler);
                                const handler = function() {
                                    const optionItem = this.closest('.sjt-option-item');
                                    const questionBlock = this.closest('.sjt-question-block');
                                    questionBlock.querySelectorAll('.sjt-option-item').forEach(item =>
                                        item.classList.remove('selected'));
                                    if (this.checked) optionItem.classList.add('selected');
                                    updateAnswerStatus();

                                    // Optional: autosave single response (fire-and-forget)
                                    // autosaveSingle(this.dataset.question, this.value);
                                };
                                radio.addEventListener('change', handler);
                                radio._handler = handler;
                            });
                        };

                        // initial mark (server-side selected already rendered)
                        radios.forEach(r => {
                            if (r.checked) r.closest('.sjt-option-item').classList.add('selected');
                        });
                        attachRadioHandlers();
                        updateAnswerStatus();

                        // AJAX submit handler (bind once per form)
                        // Remove previous listener to avoid duplicates
                        if (form._submitHandler) form.removeEventListener('submit', form._submitHandler);

                        const submitHandler = async function(e) {
                            e.preventDefault();

                            // final client check
                            const answered = new Set(Array.from(form.querySelectorAll('.sjt-radio')).filter(r =>
                                r.checked).map(r => r.dataset.question)).size;
                            if (answered !== totalQuestions) {
                                alert(
                                    `Harap jawab semua pertanyaan. Saat ini: ${answered}/${totalQuestions} telah dijawab.`
                                );
                                return;
                            }

                            // disable button UI
                            if (submitBtn) {
                                submitBtn.disabled = true;
                                var originalText = submitBtn.textContent;
                                submitBtn.textContent = 'Menyimpan...';
                            }

                            const fd = new FormData(form);

                            try {
                                const res = await fetch(form.action, {
                                    method: 'POST',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json',
                                        // CSRF token header optional if you include @csrf hidden input (Laravel expects cookie/token)
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content || ''
                                    },
                                    body: fd,
                                    credentials: 'same-origin' // <<< PENTING: kirim cookie/session
                                });

                                // If validation error
                                if (res.status === 422) {
                                    const payload = await res.json().catch(() => null);
                                    alert(payload?.message || Object.values(payload?.errors || {}).flat().join(
                                        '\n') || 'Validasi gagal.');
                                    if (submitBtn) {
                                        submitBtn.disabled = false;
                                        submitBtn.textContent = originalText;
                                    }
                                    return;
                                }

                                // if JSON response (we expect { next: url })
                                const contentType = res.headers.get('content-type') || '';
                                if (contentType.includes('application/json')) {
                                    const data = await res.json();
                                    if (!data?.next) {
                                        window.location.reload();
                                        return;
                                    }

                                    // load next page fragment (HTML) if SJT page
                                    if (data.next.includes('/test/sjt/page')) {
                                        try {
                                            const frag = await fetch(data.next, {
                                                headers: {
                                                    'X-Requested-With': 'XMLHttpRequest'
                                                },
                                                credentials: 'same-origin'
                                            });
                                            const html = await frag.text();
                                            const parser = new DOMParser();
                                            const doc = parser.parseFromString(html, 'text/html');
                                            const newContainer = doc.querySelector('.sjt-test-container');
                                            if (newContainer) {
                                                // preserve current selections (optional, in case server-rendered checked missing)
                                                const selections = {};
                                                document.querySelectorAll('.sjt-radio:checked').forEach(r => {
                                                    selections[r.dataset.question] = r.value;
                                                });

                                                // replace content
                                                const oldContainer = document.querySelector(
                                                    '.sjt-test-container');
                                                oldContainer.replaceWith(newContainer);

                                                // scroll to top smoothly and set focus for accessibility
                                                try {
                                                    window.scrollTo({
                                                        top: 0,
                                                        behavior: 'smooth'
                                                    });
                                                } catch (e) {
                                                    // fallback
                                                    window.scrollTo(0, 0);
                                                }

                                                // small delay to ensure DOM attached, then focus first heading and reapply selections
                                                setTimeout(() => {
                                                    // reapply selections so UI stays consistent if server didn't render checked
                                                    Object.entries(selections).forEach(([qid, val]) => {
                                                        const input = document.querySelector(
                                                            `input[name="responses[${qid}]"][value="${val}"]`
                                                            );
                                                        if (input) {
                                                            input.checked = true;
                                                            input.closest('.sjt-option-item')
                                                                ?.classList.add('selected');
                                                        }
                                                    });

                                                    // focus first meaningful heading for screen readers
                                                    const firstHeading = document.querySelector(
                                                        '.sjt-title') || document.querySelector(
                                                        '.sjt-question-header');
                                                    if (firstHeading) {
                                                        firstHeading.setAttribute('tabindex', '-1');
                                                        try {
                                                            firstHeading.focus({
                                                                preventScroll: true
                                                            });
                                                        } catch (err) {
                                                            firstHeading.focus();
                                                        }
                                                    }

                                                    // update history + re-init bindings
                                                    history.pushState({}, '', data.next);
                                                    initSJT();
                                                }, 80); // small timeout to allow browser paint

                                                return;
                                            } else {
                                                window.location.href = data.next;
                                                return;
                                            }
                                        } catch (err) {
                                            console.error('Error fetching next fragment', err);
                                            window.location.href = data.next;
                                            return;
                                        }
                                    } else {
                                        // final redirect (e.g. thank-you)
                                        window.location.href = data.next;
                                        return;
                                    }
                                }

                                // If server returned redirect HTML (fetch may follow redirect)
                                if (res.redirected) {
                                    window.location.href = res.url;
                                    return;
                                }

                                // Fallback: reload
                                window.location.reload();
                            } catch (err) {
                                console.error(err);
                                alert('Terjadi kesalahan jaringan. Coba lagi.');
                                if (submitBtn) {
                                    submitBtn.disabled = false;
                                    submitBtn.textContent = originalText;
                                }
                            }
                        };

                        form.addEventListener('submit', submitHandler);
                        form._submitHandler = submitHandler;
                    }; // end initSJT

                    // initial call
                    initSJT();

                    // Optional: handle browser back/forward to re-init when user navigates history
                    window.addEventListener('popstate', function() {
                        // small delay to let DOM update
                        setTimeout(() => initSJT(), 50);
                    });
                });
            </script>
        @endpush
    @endsection

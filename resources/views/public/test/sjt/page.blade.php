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
            <!-- Answer Status -->
            <div class="sjt-answer-status incomplete" id="answerStatus">
                Lengkapi semua jawaban untuk melanjutkan (0/{{ $questions->count() }} dijawab)
            </div>

            <form id="sjtForm" action="{{ route('test.sjt.page.store', $page) }}" method="POST" class="js-loading-form">
                @csrf
                <div class="sjt-questions-list">
                    @foreach ($questions as $question)
                        <div class="sjt-question-block">
                            <div class="sjt-question-header">
                                {{ $question->number }}. {{ $question->question_text }}
                            </div>
                            <div class="sjt-question-content">
                                <div class="sjt-options-list">
                                    @foreach ($question->options as $option)
                                        <label class="sjt-option-item" data-question="{{ $question->id }}">
                                            <input type="radio" name="responses[{{ $question->id }}]"
                                                value="{{ $option->option_letter }}" class="sjt-radio"
                                                data-question="{{ $question->id }}"
                                                {{ isset($existingResponses[$question->id]) && $existingResponses[$question->id]->selected_option === $option->option_letter ? 'checked' : '' }}>
                                            <span class="sjt-option-letter">{{ strtoupper($option->option_letter) }}.</span>
                                            <span class="sjt-option-text">{{ $option->option_text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
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
                const form = document.getElementById('sjtForm');
                const submitBtn = document.getElementById('submitBtn');
                const answerStatus = document.getElementById('answerStatus');
                const radios = document.querySelectorAll('.sjt-radio');
                const totalQuestions = {{ $questions->count() }};

                console.log('SJT Page loaded, total questions:', totalQuestions);

                function updateAnswerStatus() {
                    const answeredQuestions = new Set();

                    radios.forEach(radio => {
                        if (radio.checked) {
                            answeredQuestions.add(radio.dataset.question);
                        }
                    });

                    const answeredCount = answeredQuestions.size;
                    const allAnswered = answeredCount === totalQuestions;

                    // Update status display
                    if (allAnswered) {
                        answerStatus.textContent =
                            `Semua pertanyaan telah dijawab (${answeredCount}/${totalQuestions})`;
                        answerStatus.className = 'sjt-answer-status complete';
                    } else {
                        answerStatus.textContent =
                            `Lengkapi semua jawaban untuk melanjutkan (${answeredCount}/${totalQuestions} dijawab)`;
                        answerStatus.className = 'sjt-answer-status incomplete';
                    }

                    // Enable/disable submit button
                    submitBtn.disabled = !allAnswered;

                    console.log('Answered questions:', answeredCount, 'of', totalQuestions);
                }

                // Add event listeners to radio buttons
                radios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        // Visual feedback - mark option as selected
                        const optionItem = this.closest('.sjt-option-item');
                        const questionBlock = this.closest('.sjt-question-block');

                        // Remove selected class from other options in this question
                        questionBlock.querySelectorAll('.sjt-option-item').forEach(item => {
                            item.classList.remove('selected');
                        });

                        // Add selected class to current option
                        if (this.checked) {
                            optionItem.classList.add('selected');
                        }

                        updateAnswerStatus();
                    });
                });

                // Form submission
                form.addEventListener('submit', function(e) {
                    const answeredQuestions = new Set();

                    radios.forEach(radio => {
                        if (radio.checked) {
                            answeredQuestions.add(radio.dataset.question);
                        }
                    });

                    if (answeredQuestions.size !== totalQuestions) {
                        e.preventDefault();
                        alert(
                            `Harap jawab semua pertanyaan. Saat ini: ${answeredQuestions.size}/${totalQuestions} pertanyaan telah dijawab.`
                            );
                        return false;
                    }

                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Menyimpan...';
                });

                // Initial status update - mark existing selections
                radios.forEach(radio => {
                    if (radio.checked) {
                        const optionItem = radio.closest('.sjt-option-item');
                        optionItem.classList.add('selected');
                    }
                });

                updateAnswerStatus();
            });
        </script>
    @endsection

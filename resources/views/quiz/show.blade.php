@extends('layouts.app')
@section('title', 'Quiz: ' . $lesson->title)
@section('breadcumb')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}">{{ ucwords(Auth::user()->role->name) }}</a>
    </li>
    <li class="breadcrumb-item">Quiz</li>
    <li class="breadcrumb-item active">{{ $lesson->title }}</li>
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Quiz: {{ $lesson->title }}</h5>
                </div>
                <div class="card-body">

                    @if ($alreadySubmitted)
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info">
                            <i class="bi bi-info-circle me-2"></i> Kamu sudah mengerjakan quiz ini.
                            <a href="{{ route('student.quiz.result', $lesson->uuid) }}"
                                class="fw-bold text-info text-decoration-underline ms-2">Lihat Hasil</a>
                        </div>
                    @else
                        @if ($questions->isEmpty())
                            <div class="text-center py-5">
                                <h6 class="text-muted">Belum ada soal untuk quiz ini.</h6>
                            </div>
                        @else
                            <form action="{{ route('student.quiz.submit', $lesson->uuid) }}" method="POST" id="quizForm">
                                @csrf

                                @foreach ($questions->shuffle() as $index => $item)
                                    @php
                                        $q = $item['question'];
                                        $diff = $item['difficulty'];
                                        $type = $q->type ?? 'teori';

                                        // Render code blocks nicely if there are any
                                        $questionText = preg_replace(
                                            '/```(?:php|js|python|java|c|cpp|html)?\s*([\s\S]*?)```/m',
                                            '<pre class="bg-light border rounded p-3 mt-2 mb-2 w-100" style="overflow-x: auto;"><code>$1</code></pre>',
                                            htmlspecialchars($q->question_text, ENT_QUOTES, 'UTF-8'),
                                        );
                                        // Unescape the <pre><code> tags we just inserted
                                        $questionText = str_replace(
                                            [
                                                '&lt;pre class=&quot;bg-light border rounded p-3 mt-2 mb-2 w-100&quot; style=&quot;overflow-x: auto;&quot;&gt;&lt;code&gt;',
                                                '&lt;/code&gt;&lt;/pre&gt;',
                                            ],
                                            [
                                                '<pre class="bg-light border rounded p-3 mt-2 mb-2 w-100" style="overflow-x: auto;"><code>',
                                                '</code></pre>',
                                            ],
                                            $questionText,
                                        );
                                        // Simple nl2br for the rest
                                        $questionText = nl2br($questionText);
                                    @endphp

                                    <div class="mb-5 quiz-question" id="q-{{ $index }}">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="fw-bold me-2">{{ $index + 1 }}.</div>
                                            <div class="flex-grow-1">
                                                <div class="mb-1">
                                                    <span class="badge bg-secondary mb-2">{{ ucfirst($diff) }}</span>
                                                </div>
                                                <div class="question-text text-dark">
                                                    {!! $questionText !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ms-4 ps-2 border-start border-2 border-primary border-opacity-25">
                                            @foreach ($item['answers']->shuffle() as $ans)
                                                <div class="form-check mb-2 custom-radio">
                                                    <input class="form-check-input" type="radio"
                                                        name="answers[{{ $q->id }}]" id="ans_{{ $ans->id }}"
                                                        value="{{ $ans->id }}" required>
                                                    <label
                                                        class="form-check-label w-100 cursor-pointer p-2 rounded hover-bg-light"
                                                        for="ans_{{ $ans->id }}">
                                                        {{ $ans->answer_text }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    @unless ($loop->last)
                                        <hr class="text-muted opacity-25 mb-4">
                                    @endunless
                                @endforeach

                                <div class="d-flex justify-content-between align-items-center mt-5 bg-light p-3 rounded">
                                    <span class="text-muted small"><i class="bi bi-info-circle me-1"></i> Pastikan semua
                                        terjawab sebelum submit</span>
                                    <button type="submit" class="btn btn-primary px-4" id="btnSubmit">
                                        Submit Jawaban <i class="bi bi-send ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        @endif

                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .custom-radio .form-check-input {
            margin-top: 0.6rem;
        }

        .hover-bg-light:hover {
            background-color: #f8f9fa;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .question-text pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $('#quizForm').on('submit', function() {
            $('#btnSubmit').prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses...'
            );
        });
    </script>
@endpush

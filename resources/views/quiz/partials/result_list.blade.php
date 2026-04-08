@if ($questions->isEmpty() || ($filterLevel !== 'all' && $questions->where('difficulty', $filterLevel)->isEmpty()))
    <div class="text-center py-5">
        <h6 class="text-muted">Tidak ada soal untuk kategori ini.</h6>
    </div>
@else
    <ul class="list-group list-group-flush">
        @foreach ($questions as $index => $item)
            @php
                $q = $item['question'];
                $diff = $item['difficulty'];

                // Skip jika tidak cocok dengan filter
                if ($filterLevel !== 'all' && $diff !== $filterLevel) {
                    continue;
                }

                $type = $q->type ?? 'teori';
                $studentAnswer = $item['student_answer'];
                $studentAnswerId = $studentAnswer ? $studentAnswer->answer_id : null;

                $isCorrect = false;
                if ($studentAnswerId) {
                    $selectedAns = $item['answers']->where('id', $studentAnswerId)->first();
                    $isCorrect = $selectedAns && $selectedAns->is_correct;
                }

                $badgeColor = match ($diff) {
                    'mudah' => 'success',
                    'sedang' => 'warning',
                    'sulit' => 'danger',
                    default => 'secondary',
                };

                // Parse Code di Soal
                $questionText = preg_replace(
                    '/```(?:php|js|python|java|c|cpp|html)?\s*([\s\S]*?)```/m',
                    '<pre class="bg-light border rounded p-2 mt-1 mb-2 small" style="overflow-x:auto;"><code>$1</code></pre>',
                    htmlspecialchars($q->question_text, ENT_QUOTES, 'UTF-8'),
                );
                $questionText = str_replace(
                    [
                        '&lt;pre class=&quot;bg-light border rounded p-2 mt-1 mb-2 small&quot; style=&quot;overflow-x:auto;&quot;&gt;&lt;code&gt;',
                        '&lt;/code&gt;&lt;/pre&gt;',
                    ],
                    [
                        '<pre class="bg-light border rounded p-2 mt-1 mb-2 small" style="overflow-x:auto;"><code>',
                        '</code></pre>',
                    ],
                    $questionText,
                );
                $questionText = nl2br($questionText);
            @endphp

            <li
                class="list-group-item p-4 {{ $isCorrect ? 'bg-success bg-opacity-10 border-success border-opacity-25' : 'bg-danger bg-opacity-10 border-danger border-opacity-25' }}">
                <div class="d-flex w-100 justify-content-between mb-2">
                    <div class="d-flex align-items-center">
                        @if ($isCorrect)
                            <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i>
                            <span class="text-success fw-bold">Benar</span>
                        @else
                            <i class="bi bi-x-circle-fill text-danger fs-5 me-2"></i>
                            <span class="text-danger fw-bold">Salah</span>
                        @endif
                    </div>
                    <div>
                        <span class="badge bg-{{ $badgeColor }}">{{ ucfirst($diff) }}</span>
                    </div>
                </div>

                <div class="mb-3 mt-2 text-dark">
                    <span class="fw-bold me-1">{{ $index + 1 }}.</span>
                    {!! $questionText !!}
                </div>

                <div class="row ms-3 mt-2">
                    <div class="col-md-6 col-12">
                        <p class="text-muted small mb-1">Jawaban Kamu:</p>
                        @if ($studentAnswerId)
                            @php
                                $ansText = $item['answers']->where('id', $studentAnswerId)->first()->answer_text ?? '-';
                            @endphp
                            <div
                                class="p-2 border rounded {{ $isCorrect ? 'border-success text-success bg-white' : 'border-danger text-danger bg-white' }}">
                                {!! preg_replace('/`([^`]+)`/', '<code>$1</code>', e($ansText)) !!}
                            </div>
                        @else
                            <div class="p-2 border rounded border-secondary text-secondary bg-white">
                                Tidak menjawab
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 col-12 mt-3 mt-md-0">
                        <p class="text-muted small mb-1">Kunci Jawaban:</p>
                        @php
                            $correctAnswer = $item['answers']->where('is_correct', true)->first();
                        @endphp
                        <div class="p-2 border rounded border-success text-success bg-white fw-semibold">
                            {!! $correctAnswer ? preg_replace('/`([^`]+)`/', '<code>$1</code>', e($correctAnswer->answer_text)) : 'Tidak ada' !!}
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
@endif

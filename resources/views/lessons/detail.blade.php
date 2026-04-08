@extends('layouts.app')
@section('title', $lesson->title)
@section('breadcumb')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}">{{ ucwords(Auth::user()->role->name) }}</a>
    </li>
    <li class="breadcrumb-item">Materi</li>
    <li class="breadcrumb-item active">{{ $lesson->title }}</li>
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('lessons.generate', $lesson->uuid) }}" method="post" id="generateQuestion"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                        <div class="row justify-content-end mb-3">
                            <div class="col-md-2">
                                <label>Total Soal</label>
                                <input type="number" name="total_question" min="1" class="form-control">
                            </div>
                        </div>
                        <div id="question-wrapper">
                            <div class="row align-items-end question-group mb-2">
                                <div class="col-md-6">
                                    <label>File Materi</label>
                                    <input type="file" name="material_path[]" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label>Persentase Soal (%)</label>
                                    <input type="number" name="percentage[]" min="0" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Tingkat Kesulitan</label>
                                    <select name="difficulty[]" class="form-control">
                                        <option value="mudah">Mudah</option>
                                        <option value="sedang">Sedang</option>
                                        <option value="sulit">Sulit</option>
                                    </select>
                                </div>
                                <div class="col-md-1 d-flex">
                                    <button type="button" id="addRow" class="btn btn-primary mt-auto">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a href="{{ route('lessons.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="button" id="btnGenerate" class="btn btn-primary">
                        <span id="btnText">Generate Soal</span>
                        <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Hasil Soal --}}
    <div class="row justify-content-center mt-3" id="result-section"
        {{ $existingQuestions->isEmpty() ? 'style=display:none' : '' }}>
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Daftar Soal</h6>
                    <span id="result-count" class="badge bg-primary">{{ $existingQuestions->count() }} soal</span>
                </div>
                <div class="card-body" id="result-wrapper">
                    @forelse ($existingQuestions as $item)
                        @php
                            $q = $item['question'];
                            $diff = $item['difficulty'];
                            $type = $q->type ?? 'teori';
                            $badgeColor = match ($diff) {
                                'mudah' => 'success',
                                'sedang' => 'warning',
                                'sulit' => 'danger',
                                default => 'secondary',
                            };
                            // Render backtick code block → <pre><code>
                            // Pakai preg_replace_callback agar $variable tidak dianggap backreference
                            $questionHtml = preg_replace_callback(
                                '/```(?:php|js|python|java|c|cpp|html)?\s*([\s\S]*?)```/m',
                                function ($m) {
                                    return '<pre class="bg-light border rounded p-2 mt-1 mb-1 small"><code>' .
                                        htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8') .
                                        '</code></pre>';
                                },
                                $q->question_text,
                            );
                            // Escape teks di luar blok <pre>
                            $segments = preg_split(
                                '/(<pre[\s\S]*?<\/pre>)/m',
                                $questionHtml,
                                -1,
                                PREG_SPLIT_DELIM_CAPTURE,
                            );
                            $questionHtml = implode(
                                '',
                                array_map(
                                    function ($seg, $idx) {
                                        return $idx % 2 === 0 ? nl2br(e($seg)) : $seg;
                                    },
                                    $segments,
                                    array_keys($segments),
                                ),
                            );
                        @endphp
                        <div class="mb-4">
                            <p class="fw-semibold mb-1">
                                {{ $loop->iteration }}.
                                <span class="badge bg-{{ $type === 'solving' ? 'info' : 'secondary' }} me-1">
                                    {{ $type === 'solving' ? '💻 Problem Solving' : '📖 Teori' }}
                                </span>
                                <span class="badge bg-{{ $badgeColor }}">{{ $diff }}</span>
                            </p>
                            <div class="ms-1 mb-1">{!! $questionHtml !!}</div>
                            <ul class="list-unstyled ms-3">
                                @foreach ($item['answers'] as $ans)
                                    <li class="{{ $ans->is_correct ? 'text-success fw-bold' : '' }}">
                                        {!! preg_replace('/`([^`]+)`/', '<code>$1</code>', e($ans->answer_text)) !!}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @unless ($loop->last)
                            <hr>
                        @endunless
                    @empty
                        {{-- kosong, diisi via JS setelah generate --}}
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // ── Tambah baris ─────────────────────────────────────────────
            $('#addRow').on('click', function() {
                let newRow = `
                <div class="row align-items-end question-group mb-2">
                    <div class="col-md-6">
                        <input type="file" name="material_path[]" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="percentage[]" min="0" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <select name="difficulty[]" class="form-control">
                            <option value="mudah">Mudah</option>
                            <option value="sedang">Sedang</option>
                            <option value="sulit">Sulit</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex">
                        <button type="button" class="btn btn-danger removeRow mt-auto">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>`;
                $('#question-wrapper').append(newRow);
            });

            // ── Hapus baris ───────────────────────────────────────────────
            $(document).on('click', '.removeRow', function() {
                if ($('.question-group').length > 1) {
                    $(this).closest('.question-group').remove();
                } else {
                    alert('Minimal harus ada 1 materi!');
                }
            });

            // ── Render backtick code block → <pre><code> (JS side) ────────
            function renderCode(text) {
                return text.replace(/```(?:php|js|python|java|c|cpp|html)?\s*([\s\S]*?)```/gm,
                    function(_, code) {
                        let escaped = code
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;');
                        return '<pre class="bg-light border rounded p-2 mt-1 mb-1 small"><code>' + escaped +
                            '</code></pre>';
                    });
            }

            // ── Helper escape HTML ─────────────────────────────────────────
            function escapeHtml(text) {
                if (!text) return '';
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // ── Submit via AJAX ───────────────────────────────────────────
            $('#btnGenerate').on('click', function() {
                let formData = new FormData($('#generateQuestion')[0]);

                // Loading state
                $('#btnText').text('Generating...');
                $('#btnSpinner').removeClass('d-none');
                $('#btnGenerate').prop('disabled', true);
                $('#result-section').hide();

                $.ajax({
                    url: $('#generateQuestion').attr('action'),
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        if (!res.success) {
                            alert('Gagal: ' + (res.error || 'Unknown error'));
                            return;
                        }

                        // Hitung nomor soal mulai dari setelah soal existing
                        let startIdx = $('#result-wrapper .mb-4').length;
                        let questions = res.questions;

                        questions.forEach(function(item, i) {
                            let q = item.question;
                            let answers = item.answers;
                            let difficulty = item.difficulty;
                            let type = item.type || q.type || 'teori';

                            let diffBadge = {
                                mudah: 'success',
                                sedang: 'warning',
                                sulit: 'danger',
                            } [difficulty] || 'secondary';

                            let typeBadge = type === 'solving' || type === 'problem_solving' ?
                                '<span class="badge bg-info me-1">💻 Problem Solving</span>' :
                                '<span class="badge bg-secondary me-1">📖 Teori</span>';

                            let html = '';
                            if (startIdx + i > 0) html += '<hr>';
                            html += `
                            <div class="mb-4">
                                <p class="fw-semibold mb-1">
                                    ${startIdx + i + 1}. ${typeBadge}
                                    <span class="badge bg-${diffBadge}">${difficulty}</span>
                                </p>
                                <div class="ms-1 mb-1">${renderCode(q.question_text)}</div>
                                <ul class="list-unstyled ms-3">`;

                            answers.forEach(function(ans) {
                                let correct = ans.is_correct ?
                                    'text-success fw-bold' : '';
                                html +=
                                    `<li class="${correct}">${escapeHtml(ans.answer_text).replace(/`([^`]+)`/g, '<code>$1</code>')}</li>`;
                            });

                            html += `</ul></div>`;
                            $('#result-wrapper').append(html);
                        });

                        let totalSoal = $('#result-wrapper .mb-4').length;
                        $('#result-count').text(totalSoal + ' soal');
                        $('#result-section').show();
                    },
                    error: function(xhr) {
                        let res = xhr.responseJSON;
                        if (xhr.status === 422 && res?.errors) {
                            let msgs = Object.values(res.errors).flat().join('\n');
                            alert('Validasi gagal:\n' + msgs);
                        } else {
                            let msg = res?.error || res?.message || 'Terjadi kesalahan.';
                            alert('Error: ' + msg);
                        }
                    },
                    complete: function() {
                        $('#btnText').text('Generate Soal');
                        $('#btnSpinner').addClass('d-none');
                        $('#btnGenerate').prop('disabled', false);
                    }
                });
            });

        });
    </script>
@endpush

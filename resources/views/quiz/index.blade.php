@extends('layouts.app')
@section('title', 'Daftar Quiz')
@section('breadcumb')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}">{{ ucwords(Auth::user()->role->name) }}</a>
    </li>
    <li class="breadcrumb-item active">Quiz</li>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-1">Mari Uji Kemampuanmu!</h4>
                        <p class="mb-0 opacity-75">Kerjakan kuis yang tersedia untuk mengukur pemahaman materi pembelajaran.
                        </p>
                    </div>
                    <div class="d-none d-md-block opacity-50">
                        <i class="bi bi-journal-text" style="font-size: 4rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($lessons as $lesson)
            @php
                // Cek apakah student sudah pernah mengerjakan (relasi studentScore hanya punya student ini)
                $scoreRecord = $lesson->studentScore->first();
                $isCompleted = !is_null($scoreRecord);
                $isPassed = $isCompleted && $scoreRecord->is_passed;
            @endphp

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-elevate transition-all">

                    {{-- Status Badge (Done/Not Done) --}}
                    @if ($isCompleted)
                        <div class="position-absolute top-0 end-0 mt-3 me-3">
                            <span
                                class="badge rounded-pill {{ $isPassed ? 'bg-success' : 'bg-danger' }} bg-opacity-10 text-{{ $isPassed ? 'success' : 'danger' }} px-3 py-2 border border-{{ $isPassed ? 'success' : 'danger' }} border-opacity-25">
                                <i class="bi bi-{{ $isPassed ? 'check-circle' : 'x-circle' }} me-1"></i>
                                {{ $isPassed ? 'Lulus' : 'Tidak Lulus' }}
                            </span>
                        </div>
                    @else
                        <div class="position-absolute top-0 end-0 mt-3 me-3">
                            <span
                                class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2 border border-warning border-opacity-25">
                                <i class="bi bi-clock-history me-1"></i> Belum Dikerjakan
                            </span>
                        </div>
                    @endif

                    <div class="card-body p-4 pt-5 mt-2 d-flex flex-column">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-lightbulb fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark text-truncate" title="{{ $lesson->title }}">
                                {{ collect(explode(' ', $lesson->title))->take(5)->implode(' ') }}
                                {{ str_word_count($lesson->title) > 5 ? '...' : '' }}
                            </h5>
                        </div>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">
                                    <i class="bi bi-list-ol me-1"></i> {{ $lesson->questions_count }} Soal
                                </span>
                                @if ($isCompleted)
                                    <span class="fw-bold {{ $isPassed ? 'text-success' : 'text-danger' }}">
                                        Skor: {{ $scoreRecord->score }}
                                    </span>
                                @endif
                            </div>

                            @if ($isCompleted)
                                <a href="{{ route('student.quiz.result', $lesson->uuid) }}"
                                    class="btn btn-outline-primary w-100">
                                    Lihat Hasil <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            @else
                                <a href="{{ route('student.quiz.show', $lesson->uuid) }}" class="btn btn-primary w-100">
                                    Mulai Kerjakan <i class="bi bi-play-circle ms-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body p-5 text-center">
                        <div class="mb-3 opacity-50">
                            <i class="bi bi-inbox fs-1"></i>
                        </div>
                        <h5 class="fw-bold text-muted mb-1">Belum Ada Quiz Tersedia</h5>
                        <p class="text-muted mb-0">Belum ada lesson yang memiliki soal quiz saat ini.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination jika ada --}}
    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            {{ $lessons->links() }}
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .hover-elevate {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-elevate:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .transition-all {
            transition: all 0.3s ease;
        }
    </style>
@endpush

@extends('layouts.app')
@section('title', 'Hasil Quiz: ' . $lesson->title)
@section('breadcumb')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}">{{ ucwords(Auth::user()->role->name) }}</a>
    </li>
    <li class="breadcrumb-item">Quiz</li>
    <li class="breadcrumb-item active">Hasil {{ $lesson->title }}</li>
@endsection

@section('content')
    <div class="row">
        {{-- OVERALL SCORE CARD --}}
        <div class="col-md-4 mb-4">
            <div
                class="card h-100 border-0 shadow-sm {{ $overallScore && $overallScore->is_passed ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                <div class="card-body text-center d-flex flex-column justify-content-center py-5">
                    <h5 class="text-uppercase text-muted mb-3 fw-bold">Skor Akhir</h5>

                    @if ($overallScore)
                        <h1 class="display-1 fw-bold {{ $overallScore->is_passed ? 'text-success' : 'text-danger' }} mb-0">
                            {{ $overallScore->score }}
                        </h1>
                        <div class="mt-2">
                            <span
                                class="badge rounded-pill {{ $overallScore->is_passed ? 'bg-success' : 'bg-danger' }} px-4 py-2 fs-6">
                                Grade: {{ $overallScore->grade }} - {{ $overallScore->is_passed ? 'LULUS' : 'TIDAK LULUS' }}
                            </span>
                        </div>
                    @else
                        <h3 class="text-muted">Belum ada skor</h3>
                    @endif
                </div>
            </div>
        </div>

        {{-- DIFFICULTY BREAKDOWN CARDS --}}
        <div class="col-md-8 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold">Analisis Skor Kesulitan</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row h-100 align-items-center">

                        @php
                            $levels = [
                                'mudah' => ['color' => 'success', 'icon' => 'emoji-smile'],
                                'sedang' => ['color' => 'warning', 'icon' => 'emoji-neutral'],
                                'sulit' => ['color' => 'danger', 'icon' => 'emoji-frown'],
                            ];
                        @endphp

                        @foreach ($levels as $level => $config)
                            @php
                                $scoreStat = $difficultyScores[$level] ?? null;
                                $pct = $scoreStat ? $scoreStat->score_percentage : 0;
                                $correct = $scoreStat ? $scoreStat->correct_answers : 0;
                                $total = $scoreStat ? $scoreStat->total_questions : 0;
                            @endphp

                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <div
                                    class="p-3 rounded bg-{{ $config['color'] }} bg-opacity-10 border border-{{ $config['color'] }} border-opacity-25 h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="text-{{ $config['color'] }} mb-2">
                                            <i class="bi bi-{{ $config['icon'] }} fs-3"></i>
                                        </div>
                                        <h6 class="text-uppercase text-muted fw-bold">{{ $level }}</h6>
                                    </div>
                                    <div class="mt-3">
                                        <h3 class="fw-bold text-{{ $config['color'] }} mb-0">{{ (int) $pct }}%</h3>
                                        <small class="text-muted">{{ $correct }} dari {{ $total }}
                                            benar</small>
                                    </div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-{{ $config['color'] }}" role="progressbar"
                                            style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DETAIL JAWABAN TABS --}}
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Detail Jawaban</h6>
            <div class="nav nav-pills custom-pills" id="v-pills-tab" role="tablist" aria-orientation="horizontal">
                <button class="nav-link active py-1 px-3 me-2 rounded-pill" id="tab-all" data-bs-toggle="pill"
                    data-bs-target="#content-all" type="button" role="tab" aria-selected="true">Semua</button>
                <button class="nav-link py-1 px-3 me-2 rounded-pill bg-success bg-opacity-10 text-success" id="tab-mudah"
                    data-bs-toggle="pill" data-bs-target="#content-mudah" type="button" role="tab"
                    aria-selected="false">Mudah</button>
                <button class="nav-link py-1 px-3 me-2 rounded-pill bg-warning bg-opacity-10 text-warning" id="tab-sedang"
                    data-bs-toggle="pill" data-bs-target="#content-sedang" type="button" role="tab"
                    aria-selected="false">Sedang</button>
                <button class="nav-link py-1 px-3 rounded-pill bg-danger bg-opacity-10 text-danger" id="tab-sulit"
                    data-bs-toggle="pill" data-bs-target="#content-sulit" type="button" role="tab"
                    aria-selected="false">Sulit</button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="tab-content" id="v-pills-tabContent">

                {{-- Tab All --}}
                <div class="tab-pane fade show active" id="content-all" role="tabpanel" aria-labelledby="tab-all">
                    @include('quiz.partials.result_list', ['filterLevel' => 'all'])
                </div>

                {{-- Tab Mudah --}}
                <div class="tab-pane fade" id="content-mudah" role="tabpanel" aria-labelledby="tab-mudah">
                    @include('quiz.partials.result_list', ['filterLevel' => 'mudah'])
                </div>

                {{-- Tab Sedang --}}
                <div class="tab-pane fade" id="content-sedang" role="tabpanel" aria-labelledby="tab-sedang">
                    @include('quiz.partials.result_list', ['filterLevel' => 'sedang'])
                </div>

                {{-- Tab Sulit --}}
                <div class="tab-pane fade" id="content-sulit" role="tabpanel" aria-labelledby="tab-sulit">
                    @include('quiz.partials.result_list', ['filterLevel' => 'sulit'])
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .custom-pills .nav-link.active {
            background-color: var(--bs-primary) !important;
            color: white !important;
        }

        .custom-pills .nav-link {
            font-size: 0.85rem;
            font-weight: 600;
        }
    </style>
@endpush

@extends('layouts.app')
@section('title', 'Dashboard Mahasiswa')
@section('breadcumb')
    <li class="breadcrumb-item"><a href="#">{{ ucwords(Auth::user()->role->name) }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
    {{-- WELCOME BANNER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden">
                <div class="card-body p-4 d-flex align-items-center position-relative">
                    <div class="z-index-1">
                        <h4 class="fw-bold mb-1">Selamat datang kembali, {{ Auth::user()->name }}! 👋</h4>
                        <p class="mb-0 opacity-75">Pantau terus perkembangan belajarmu di dashboard ini.</p>
                    </div>
                    <i class="bi bi-mortarboard-fill position-absolute text-white opacity-10"
                        style="font-size: 8rem; right: -1rem; top: -1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- STATISTIC CARDS --}}
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100 px-2 py-3">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 55px; height: 55px;">
                        <i class="bi bi-journal-text fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Total Materi</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $data['lesson'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100 px-2 py-3">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 55px; height: 55px;">
                        <i class="bi bi-pencil-square fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Kuis Selesai</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $data['quiz_completed'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100 px-2 py-3">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 55px; height: 55px;">
                        <i class="bi bi-star fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Rata-Rata Skor</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ round($data['avg_score'], 1) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 px-2 py-3">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 55px; height: 55px;">
                        <i class="bi bi-trophy fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Tingkat Lulus</h6>
                        @php
                            $passRate =
                                $data['quiz_completed'] > 0
                                    ? round(($data['passed'] / $data['quiz_completed']) * 100, 1)
                                    : 0;
                        @endphp
                        <h3 class="fw-bold mb-0 text-dark">{{ $passRate }}%</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- ACQUISITION BY DIFFICULTY --}}
        <div class="col-lg-5 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-bar-chart-fill text-primary me-2"></i> Penguasaan Materi</h5>
                    <p class="text-muted small mb-0 mt-1">Berdasarkan soal yang dijawab benar</p>
                </div>
                <div class="card-body px-4 pb-4">

                    @php
                        $diffColors = [
                            'mudah' => 'success',
                            'sedang' => 'warning',
                            'sulit' => 'danger',
                        ];
                    @endphp

                    @foreach (['mudah', 'sedang', 'sulit'] as $level)
                        @php
                            $pct = $data['difficulty'][$level]['percentage'];
                            $correct = $data['difficulty'][$level]['correct'];
                            $total = $data['difficulty'][$level]['total'];
                            $color = $diffColors[$level];
                        @endphp
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-end mb-1">
                                <span
                                    class="fw-bold text-uppercase p-1 px-2 rounded bg-{{ $color }} bg-opacity-10 text-{{ $color }}"
                                    style="font-size: 0.8rem;">
                                    {{ $level }}
                                </span>
                                <div class="text-end">
                                    <span class="fw-bold h5 mb-0 text-dark">{{ $pct }}%</span>
                                    <br>
                                    <span class="text-muted small">{{ $correct }}/{{ $total }} benar</span>
                                </div>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-{{ $color }} rounded-pill" role="progressbar"
                                    style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

        {{-- RECENT OUIZZES TIMELINE --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0"><i class="bi bi-clock-history text-primary me-2"></i> Riwayat Tes Terakhir
                        </h5>
                    </div>
                    <a href="{{ route('student.quiz.index') }}"
                        class="btn btn-sm btn-outline-primary rounded-pill px-3">Semua Kuis</a>
                </div>
                <div class="card-body px-4 pb-4 pt-3">

                    @if ($data['recent_quizzes']->isEmpty())
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-box-seam text-muted fs-2"></i>
                            </div>
                            <h6 class="fw-bold text-muted">Belum ada riwayat kuis</h6>
                            <p class="text-muted small mb-0">Kamu belum mengerjakan kuis satupun. Yuk mulai sekarang!</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="text-muted bg-light">
                                        <th class="ps-3 border-0 rounded-start" style="font-weight: 600;">Materi</th>
                                        <th class="border-0" style="font-weight: 600;">Waktu</th>
                                        <th class="border-0 text-center" style="font-weight: 600;">Skor</th>
                                        <th class="pe-3 border-0 rounded-end text-center" style="font-weight: 600;">Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['recent_quizzes'] as $rec)
                                        <tr>
                                            <td class="ps-3 border-bottom-0 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                                                        <i class="bi bi-journal-check"></i>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('student.quiz.result', $rec->lesson->uuid) }}"
                                                            class="text-dark fw-bold text-decoration-none hover-primary">
                                                            {{ Str::limit($rec->lesson->title, 35) }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-muted small border-bottom-0 py-3">
                                                {{ $rec->updated_at->diffForHumans() }}
                                            </td>
                                            <td class="text-center border-bottom-0 py-3">
                                                <span
                                                    class="fw-bold fs-5 {{ $rec->is_passed ? 'text-success' : 'text-danger' }}">
                                                    {{ $rec->score }}
                                                </span>
                                            </td>
                                            <td class="pe-3 text-center border-bottom-0 py-3">
                                                @if ($rec->is_passed)
                                                    <span
                                                        class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Lulus</span>
                                                @else
                                                    <span
                                                        class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">Gagal</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .hover-primary:hover {
            color: var(--bs-primary) !important;
        }

        .z-index-1 {
            z-index: 1;
        }
    </style>
@endpush

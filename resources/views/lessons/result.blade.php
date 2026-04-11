@extends('layouts.app')
@section('title', 'Hasil Kuis - ' . $lesson->title)
@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ ucwords(Auth::user()->role->name) }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('lessons.index') }}">Materi</a></li>
    <li class="breadcrumb-item active" aria-current="page">Hasil Kuis: {{ $lesson->title }}</li>
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Hasil Penilaian Kuis: {{ $lesson->title }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="resultTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Status</th>
                                <th>Nilai Akhir</th>
                                <th>Grade</th>
                                <th>Lulus</th>
                                <th>Detail Tingkat Kesulitan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                @php
                                    $scoreResult = $student->studentScore->first();
                                    $status = $scoreResult ? 'Sudah Mengerjakan' : 'Belum Mengerjakan';
                                    $badgeStatus = $scoreResult ? 'bg-success' : 'bg-danger';
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->user->name ?? 'Unknown' }}</td>
                                    <td><span class="badge {{ $badgeStatus }}">{{ $status }}</span></td>
                                    @if($scoreResult)
                                        <td><span class="fw-bold">{{ $scoreResult->score }}</span></td>
                                        <td>{{ $scoreResult->grade }}</td>
                                        <td>
                                            @if($scoreResult->is_passed)
                                                <span class="text-success"><i class="bi bi-check-circle-fill"></i> Ya</span>
                                            @else
                                                <span class="text-danger"><i class="bi bi-x-circle-fill"></i> Tidak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <ul class="list-unstyled mb-0 small">
                                                @foreach($student->studentDifficultyScores as $diffScore)
                                                    <li>
                                                        <strong>{{ ucfirst($diffScore->difficulty) }}</strong>: 
                                                        {{ $diffScore->correct_answers }}/{{ $diffScore->total_questions }} 
                                                        <span class="text-muted">({{ $diffScore->score_percentage }}%)</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    @else
                                        <td class="text-muted text-center">-</td>
                                        <td class="text-muted text-center">-</td>
                                        <td class="text-muted text-center">-</td>
                                        <td class="text-muted text-center">-</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('lessons.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#resultTable').DataTable();
    });
</script>
@endpush

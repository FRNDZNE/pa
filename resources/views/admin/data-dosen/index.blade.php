@extends('layouts.app')
@section('title', 'Data Dosen')
@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ ucwords(Auth::user()->role->name) }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Dosen</li>
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Modal trigger button -->
                    <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                        data-bs-target="#modalCreate">
                        <i class="bi bi-plus-lg"></i> Tambah Data Dosen
                    </button>

                    <!-- Modal Body -->
                    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                    <div class="modal fade" id="modalCreate" tabindex="-1" data-bs-backdrop="static"
                        data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitleId">
                                        Tambah Data Dosen
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.lecturer.store') }}" method="post" id="storeDosenForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="nipDosen" class="form-label">NIP Dosen</label>
                                            <input type="text"
                                                class="form-control @error('lecture_number') is-invalid @enderror"
                                                name="lecture_number" id="nipDosen" placeholder="Masukkan NIP Dosen">
                                        </div>
                                        <div class="mb-3">
                                            <label for="namaDosen" class="form-label">Nama Dosen</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="namaDosen" placeholder="Masukkan Nama Dosen">
                                        </div>

                                        <div class="mb-3">
                                            <label for="emailDosen" class="form-label">Email Dosen</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="emailDosen" placeholder="Masukkan Email Dosen">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="button" onclick="document.getElementById('storeDosenForm').submit()"
                                        class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-default" id="lecturerTable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">NIP</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lecturers as $lecturer)
                                    <tr>
                                        <td scope="row">{{ $loop->iteration }}</td>
                                        <td>{{ $lecturer->lecture_number }}</td>
                                        <td>{{ $lecturer->user->name }}</td>
                                        <td>
                                            {{-- Modals Edit --}}
                                            {{-- End Modals Edit --}}
                                            {{-- Modal Delete --}}
                                            {{-- End Modal Delete --}}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#lecturerTable').DataTable();
    </script>
@endpush

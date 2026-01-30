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
                    {{-- Modals Create --}}
                    <div class="d-flex justify-content-start mb-3">
                        <!-- Modal trigger button -->
                        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                            data-bs-target="#modalCreate">
                            <i class="bi bi-plus"></i> Tambah Dosen
                        </button>

                        <!-- Modal Body -->
                        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                        <div class="modal fade" id="modalCreate" tabindex="-1" data-bs-backdrop="static"
                            data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTitleId">
                                            Tambah Data Dosen
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('lecturer.store') }}" id="createLecturer" method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="lecture_number_create">NIDN Dosen</label>
                                                        <input type="text"
                                                            class="form-control @error('lecture_number', 'create') is-invalid @enderror"
                                                            name="lecture_number" id="lecture_number_create"
                                                            placeholder="Masukkan NIDN Dosen"
                                                            value="{{ old('lecture_number') }}">
                                                        @error('lecture_number', 'create')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="name_create">Nama Dosen</label>
                                                        <input type="text"
                                                            class="form-control @error('name', 'create') is-invalid @enderror"
                                                            name="name" id="name_create"
                                                            placeholder="Masukkan Nama Dosen" value="{{ old('name') }}">
                                                        @error('name', 'create')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="email">E-mail</label>
                                                        <input type="email"
                                                            class="form-control @error('email', 'create') is-invalid @enderror"
                                                            name="email" id="email" placeholder="Masukkan E-mail"
                                                            value="{{ old('email') }}">
                                                        @error('email', 'create')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="password">Password</label>
                                                        <input type="password"
                                                            class="form-control @error('password', 'create') is-invalid @enderror"
                                                            name="password" id="password" placeholder="Masukkan Password"
                                                            value="{{ old('password') }}">
                                                        @error('password', 'create')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Kembali
                                        </button>
                                        <button type="button" class="btn btn-primary"
                                            onclick="document.getElementById('createLecturer').submit();">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End Modal Create --}}
                    {{-- Table Start --}}
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dosenTable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">NIDN</th>
                                    <th scope="col">Nama Dosen</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lecturers as $l)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $l->lecturer->lecture_number }}</td>
                                        <td>{{ $l->name }}</td>
                                        <td>
                                            {{-- Modal Edit --}}
                                            <!-- Modal trigger button -->
                                            <button type="button" class="btn btn-warning btn-md" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit-{{ $l->uuid }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <!-- Modal Body -->
                                            <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                                            <div class="modal fade" id="modalEdit-{{ $l->uuid }}" tabindex="-1"
                                                data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
                                                aria-labelledby="modalTitleId" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg"
                                                    role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalTitleId">
                                                                Edit Data Dosen
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('lecturer.update', $l->uuid) }}"
                                                                id="updateLecturer-{{ $l->uuid }}" method="post">
                                                                @csrf
                                                                @method('PATCH')
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group mb-3">
                                                                            <label
                                                                                for="lecture_number_edit_{{ $l->uuid }}">NIDN
                                                                                Dosen</label>
                                                                            <input type="text"
                                                                                class="form-control @error('lecture_number', 'edit_' . $l->uuid) is-invalid @enderror"
                                                                                name="lecture_number"
                                                                                id="lecture_number_edit_{{ $l->uuid }}"
                                                                                placeholder="Masukkan NIDN Dosen"
                                                                                value="{{ $l->lecturer->lecture_number }}">
                                                                            @error('lecture_number', 'edit_' . $l->uuid)
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group mb-3">
                                                                            <label
                                                                                for="name_edit_{{ $l->uuid }}">Nama
                                                                                Dosen</label>
                                                                            <input type="text"
                                                                                class="form-control @error('name', 'edit_' . $l->uuid) is-invalid @enderror"
                                                                                name="name"
                                                                                id="name_edit_{{ $l->uuid }}"
                                                                                placeholder="Masukkan Nama Dosen"
                                                                                value="{{ $l->name }}">
                                                                            @error('name', 'edit' . $l->uuid)
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group mb-3">
                                                                            <label
                                                                                for="email_edit_{{ $l->uuid }}">E-mail</label>
                                                                            <input type="email"
                                                                                class="form-control @error('email', 'edit_' . $l->uuid) is-invalid @enderror"
                                                                                name="email"
                                                                                id="email_edit_{{ $l->uuid }}"
                                                                                placeholder="Masukkan E-mail"
                                                                                value="{{ $l->email }}">
                                                                            @error('email', 'edit_' . $l->uuid)
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group mb-3">
                                                                            <label
                                                                                for="password_edit_{{ $l->uuid }}">Password</label>
                                                                            <input type="password"
                                                                                class="form-control @error('password', 'edit' . $l->uuid) is-invalid @enderror"
                                                                                name="password"
                                                                                id="password_edit_{{ $l->uuid }}"
                                                                                placeholder="Abaikan Jika Tidak Ingin Mengganti Password"
                                                                                value="">
                                                                            @error('password', 'edit' . $l->uuid)
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">
                                                                Kembali
                                                            </button>
                                                            <button type="button" class="btn btn-warning"
                                                                onclick="document.getElementById('updateLecturer-{{ $l->uuid }}').submit()">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Optional: Place to the bottom of scripts -->


                                            {{-- End Modal Edit --}}
                                            {{-- Modal Delete --}}
                                            <!-- Modal trigger button -->
                                            <button type="button" class="btn btn-danger btn-md" data-bs-toggle="modal"
                                                data-bs-target="#modalDelete{{ $l->uuid }}">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                            <!-- Modal Body -->
                                            <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                                            <div class="modal fade" id="modalDelete{{ $l->uuid }}" tabindex="-1"
                                                data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
                                                aria-labelledby="modalTitleId" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md"
                                                    role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalTitleId">
                                                                Hapus Data Dosen
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Hapus Data Dosen {{ $l->name }} Dari Daftar Dosen ?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">
                                                                Close
                                                            </button>
                                                            <form action="{{ route('lecturer.destroy', $l->uuid) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- End Modal Delete --}}
                                        </td>
                                    </tr>
                                    @push('scripts')
                                        @if ($errors->{'edit_' . $l->uuid}?->any())
                                            <script>
                                                new bootstrap.Modal(
                                                    document.getElementById('modalEdit-{{ $l->uuid }}')
                                                ).show();
                                            </script>
                                        @endif
                                    @endpush
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Table End --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#dosenTable').DataTable();
        });

        @if ($errors->create->any())
            new bootstrap.Modal(
                document.getElementById('modalCreate')
            ).show();
        @endif
    </script>
@endpush

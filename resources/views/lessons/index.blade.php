@extends('layouts.app')
@section('title', 'Materi')
@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ ucwords(Auth::user()->role->name) }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Materi</li>
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
                            <i class="bi bi-plus"></i> Tambah Materi
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
                                            Tambah Materi
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('lessons.store') }}" id="createLessons" method="post">
                                            @csrf
                                            @if (Auth::user()->role->name == 'admin')
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="lecturer_id">Pilih Dosen Pengampu</label>
                                                            <select name="lecturer_id" id="lecturer_id"
                                                                class="form-control @error('lecturer_id', 'create') is-invalid @enderror">
                                                                <option value="0">Pilih Dosen</option>
                                                                @foreach ($lecturer as $l)
                                                                    <option value="{{ $l->id }}">{{ $l->user->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('lecturer_id', 'create')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <input type="hidden" name="lecturer_id"
                                                    value="{{ Auth::user()->lecturer->id }}">
                                            @endif
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="title_create">Nama Materi</label>
                                                        <input type="text"
                                                            class="form-control @error('title', 'create') is-invalid @enderror"
                                                            name="title" id="title_create"
                                                            placeholder="Masukkan Nama Materi" value="{{ old('title') }}">
                                                        @error('title', 'create')
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
                                            onclick="document.getElementById('createLessons').submit();">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End Modal Create --}}
                    {{-- Table Start --}}
                    <div class="table-responsive">
                        <table class="table table-bordered" id="lessonTable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Materi</th>
                                    @if (Auth::user()->role->name == 'admin')
                                        <th scope="col">Dosen Pengampu</th>
                                    @endif
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lessons as $l)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $l->title }}</td>
                                        @if (Auth::user()->role->name == 'admin')
                                            <td>{{ $l->lecturer->user->name }}</td>
                                        @endif
                                        <td>
                                            <a href="{{ route('lessons.show', $l->uuid) }}" class="btn btn-info btn-md" title="Lihat Soal"><i
                                                    class="bi bi-eye"></i></a>
                                            <a href="{{ route('lessons.result', $l->uuid) }}" class="btn btn-success btn-md" title="Lihat Hasil Kuis"><i
                                                    class="bi bi-bar-chart-fill"></i></a>
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
                                                                Edit Materi
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('lessons.update', $l->uuid) }}"
                                                                id="updateLessons-{{ $l->uuid }}" method="post">
                                                                @csrf
                                                                @method('PATCH')
                                                                @if (Auth::user()->role->name == 'admin')
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group mb-3">
                                                                                <label for="lecturer_id">Pilih Dosen
                                                                                    Pengampu</label>
                                                                                <select name="lecturer_id"
                                                                                    id="lecturer_id"
                                                                                    class="form-control @error('lecturer_id', 'edit_' . $l->uuid) is-invalid @enderror">
                                                                                    <option value="0">Pilih Dosen
                                                                                    </option>
                                                                                    @foreach ($lecturer as $le)
                                                                                        <option
                                                                                            value="{{ $le->id }}"
                                                                                            @if ($le->id == $l->lecturer_id) selected @endif>
                                                                                            {{ $le->user->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @error('lecturer_id', 'edit_' . $l->uuid)
                                                                                    <div class="invalid-feedback">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <input type="hidden" name="lecturer_id"
                                                                        value="{{ Auth::user()->lecturer->id }}">
                                                                @endif
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group mb-3">
                                                                            <label for="title_create">Nama Materi</label>
                                                                            <input type="text"
                                                                                class="form-control @error('title', 'edit_' . $l->uuid) is-invalid @enderror"
                                                                                name="title" id="title_create"
                                                                                placeholder="Masukkan Nama Materi"
                                                                                value="{{ $l->title }}">
                                                                            @error('title', 'edit_' . $l->uuid)
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
                                                                onclick="document.getElementById('updateLessons-{{ $l->uuid }}').submit()">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Optional: Place to the bottom of scripts -->


                                            {{-- End Modal Edit --}}
                                            {{-- Modal Delete --}}
                                            <!-- Modal trigger button -->
                                            <button type="button" class="btn btn-danger btn-md" data-bs-toggle="modal"
                                                data-bs-target="#modalDelete-{{ $l->uuid }}">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                            <!-- Modal Body -->
                                            <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                                            <div class="modal fade" id="modalDelete-{{ $l->uuid }}" tabindex="-1"
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
                                                            <p>Hapus Materi {{ $l->title }} Dari daftar materi ?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">
                                                                Close
                                                            </button>
                                                            <form action="{{ route('lessons.destroy', $l->uuid) }}"
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
            $('#lessonTable').DataTable();
        });

        @if ($errors->create->any())
            new bootstrap.Modal(
                document.getElementById('modalCreate')
            ).show();
        @endif
    </script>
@endpush

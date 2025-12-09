@extends('layouts.app')
@section('title', 'Data Dosen')
@section('breadcumb')
    <li class="breadcrumb-item"><a href="#">{{ ucwords(Auth::user()->role->name) }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Dosen</li>
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @livewire('admin.lecturer.index')
                </div>
            </div>
        </div>
    </div>
@endsection

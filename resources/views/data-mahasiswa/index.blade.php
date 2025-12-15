@extends('layouts.app')
@section('title', 'Data Mahasiswa')
@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ ucwords(Auth::user()->role->name) }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Mahasiswa</li>
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <H1>Halaman Data Mahasiswa</H1>
                </div>
            </div>
        </div>
    </div>
@endsection

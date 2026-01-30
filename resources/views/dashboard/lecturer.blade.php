@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('breadcumb')
    <li class="breadcrumb-item"><a href="#">{{ ucwords(Auth::user()->role->name) }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
                <span class="info-box-icon text-bg-success shadow-sm">
                    <i class="bi bi-building"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Kelas</span>
                    <span class="info-box-number">{{ $data['kelas'] }} <small>Kelas</small></span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
                <span class="info-box-icon text-bg-info shadow-sm">
                    <i class="bi bi-journal-text"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Materi</span>
                    <span class="info-box-number">{{ $data['materi'] }} <small>File</small></span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm">
                    <i class="bi bi-clipboard-check"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Bank Soal</span>
                    <span class="info-box-number">{{ $data['quiz'] }} <small>Soal</small></span>
                </div>
            </div>
        </div>
    </div>
@endsection

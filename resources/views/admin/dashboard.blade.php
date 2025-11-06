@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('dashboard', 'active')
@section('page-subtitle', 'Selamat Datang di Dashboard')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="dashboard-card-title">Total Dosen</div>
                    <div class="dashboard-card-subtitle">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="dashboard-card-title">Total Kelas</div>
                    <div class="dashboard-card-subtitle">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="dashboard-card-title">Total Mahasiswa</div>
                    <div class="dashboard-card-subtitle">0</div>
                </div>
            </div>
        </div>
    </div>
@endsection

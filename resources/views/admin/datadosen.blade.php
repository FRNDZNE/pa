@extends('layouts.app')
@section('title', 'Data Dosen')
@section('page-title', 'Data Dosen')
@section('content')
    @livewire('admin.lecturer.create-data')
    <hr>
    @livewire('admin.lecturer.get-data')
@endsection

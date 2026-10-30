@extends('layouts.app')
@section('title','Jadwal Mengajar')
@section('subtitle', $dosen->nama_lengkap . ' · ' . ($semester?->nama ?? '-'))
@section('content')
@include('jadwal._grid', ['mode'=>'dosen'])
@endsection

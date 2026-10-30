@extends('layouts.app')
@section('title','Jadwal Kuliah')
@section('subtitle', $mhs->nama . ' · ' . ($semester?->nama ?? '-'))
@section('content')
@include('jadwal._grid', ['mode'=>'mahasiswa'])
@endsection

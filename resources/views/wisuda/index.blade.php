@extends('layouts.app')
@section('title','Wisuda')
@section('subtitle', $wisuda->total() . ' periode')
@section('content')
@if(in_array(auth()->user()->role,['akademik','admin']))
<details class="bg-white rounded-xl border border-gray-200 mb-5"><summary class="px-5 py-3 cursor-pointer text-sm font-semibold text-indigo-700">➕ Buat Periode Wisuda</summary>
<form action="{{ route('wisuda.store') }}" method="POST" class="p-5 border-t border-gray-100 grid grid-cols-3 gap-4">@csrf
    <div class="col-span-2"><label class="block text-xs text-gray-500 mb-1">Nama</label><input name="nama" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div><label class="block text-xs text-gray-500 mb-1">Tanggal Wisuda</label><input type="date" name="tanggal" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div><label class="block text-xs text-gray-500 mb-1">Lokasi</label><input name="lokasi" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div><label class="block text-xs text-gray-500 mb-1">Jam Mulai</label><input type="time" name="jam_mulai" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div><label class="block text-xs text-gray-500 mb-1">Kuota</label><input type="number" name="kuota" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div><label class="block text-xs text-gray-500 mb-1">Daftar Buka</label><input type="date" name="tanggal_daftar_buka" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div><label class="block text-xs text-gray-500 mb-1">Daftar Tutup</label><input type="date" name="tanggal_daftar_tutup" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div class="col-span-3"><button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg">Simpan</button></div>
</form></details>
@endif
<div class="grid grid-cols-2 gap-4">
@forelse($wisuda as $w)
<div class="bg-white rounded-xl border border-gray-200 p-5">
    <div class="flex justify-between mb-2"><h3 class="font-semibold text-gray-900">{{ $w->nama }}</h3><span class="text-xs px-2.5 py-1 rounded-full h-fit {{ $w->status_badge['class'] }}">{{ $w->status_badge['label'] }}</span></div>
    <div class="text-xs text-gray-500 space-y-0.5 mb-3"><div>📅 {{ $w->tanggal->format('d F Y') }} · {{ $w->jam_mulai ? substr($w->jam_mulai,0,5) : '' }}</div><div>📍 {{ $w->lokasi }}</div><div>👥 {{ $w->pendaftaran_count }} pendaftar{{ $w->kuota ? ' / '.$w->kuota : '' }}</div></div>
    @if(in_array(auth()->user()->role,['akademik','admin']))<a href="{{ route('wisuda.show',$w) }}" class="text-xs text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg">Kelola Pendaftar →</a>@endif
</div>
@empty<div class="col-span-2 bg-white rounded-xl border border-gray-200 p-10 text-center text-sm text-gray-400">Belum ada periode wisuda</div>@endforelse
</div>
@endsection

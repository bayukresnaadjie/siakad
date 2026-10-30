@extends('layouts.app')
@section('title','Wisuda')
@section('subtitle', $mhs->nama . ' · ' . $mhs->nim)
@section('content')
<div class="grid grid-cols-3 gap-4">
<div class="bg-white rounded-xl border border-gray-200 p-5">
    <h2 class="text-sm font-semibold text-gray-900 mb-3">✅ Persyaratan Wisuda</h2>
    <div class="space-y-3">
        @foreach($syarat as $key=>$s)
        @if($key === 'semua_terpenuhi') @continue @endif
        <div class="flex items-start gap-2"><span class="{{ $s['status']?'text-green-500':'text-red-400' }} text-lg leading-none">{{ $s['status']?'✓':'✕' }}</span>
        <div><div class="text-sm text-gray-800">{{ $s['label'] }}</div><div class="text-xs text-gray-400">Saat ini: {{ $s['nilai'] }}</div></div></div>
        @endforeach
    </div>
    <div class="mt-4 text-center text-xs px-3 py-2 rounded-lg {{ $syarat['semua_terpenuhi']?'bg-green-50 text-green-700':'bg-amber-50 text-amber-700' }}">{{ $syarat['semua_terpenuhi'] ? 'Semua persyaratan terpenuhi' : 'Persyaratan belum lengkap' }}</div>
</div>
<div class="col-span-2 space-y-4">
    @if($pendaftaran)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between mb-3"><h2 class="text-sm font-semibold text-gray-900">🎉 Pendaftaran Wisuda Anda</h2><span class="text-xs px-2.5 py-1 rounded-full {{ $pendaftaran->status_badge['class'] }}">{{ $pendaftaran->status_badge['label'] }}</span></div>
        <div class="text-sm space-y-2"><div class="text-gray-500">Periode: <b class="text-gray-900">{{ $pendaftaran->wisuda->nama }}</b> · {{ $pendaftaran->wisuda->tanggal->format('d M Y') }}</div>
        <div class="text-gray-500">Judul Skripsi: <b class="text-gray-900">{{ $pendaftaran->judul_skripsi }}</b></div>
        <div class="text-gray-500">Pembimbing: <b class="text-gray-900">{{ $pendaftaran->pembimbing_skripsi }}</b></div>
        <div class="text-gray-500">IPK: <b class="text-indigo-600">{{ number_format($pendaftaran->ipk_lulus,2) }}</b> · Predikat: <b>{{ $pendaftaran->predikat_label }}</b></div>
        @if($pendaftaran->catatan)<div class="text-red-500 text-xs">Catatan: {{ $pendaftaran->catatan }}</div>@endif</div>
    </div>
    @endif
    @foreach($wisudaBuka as $w)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between mb-2"><div><h3 class="font-semibold text-gray-900">{{ $w->nama }}</h3><div class="text-xs text-gray-400">{{ $w->tanggal->format('l, d F Y') }} · {{ $w->lokasi }}</div></div><span class="text-xs px-2.5 py-1 rounded-full h-fit {{ $w->status_badge['class'] }}">{{ $w->status_badge['label'] }}</span></div>
        <div class="text-xs text-gray-500 mb-3">Pendaftaran: {{ $w->tanggal_daftar_buka->format('d M') }} – {{ $w->tanggal_daftar_tutup->format('d M Y') }}</div>
        @if(!$pendaftaran && $w->isOpen() && $syarat['semua_terpenuhi'])
        <form action="{{ route('wisuda.daftar',$w) }}" method="POST" class="grid grid-cols-2 gap-3">@csrf
            <div class="col-span-2"><label class="block text-xs text-gray-500 mb-1">Judul Skripsi</label><input name="judul_skripsi" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
            <div class="col-span-2"><label class="block text-xs text-gray-500 mb-1">Pembimbing</label><input name="pembimbing_skripsi" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
            <div><button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg">Daftar Wisuda</button></div>
        </form>
        @elseif(!$pendaftaran)<div class="text-xs text-amber-600">Anda belum dapat mendaftar: periode ditutup atau persyaratan belum terpenuhi.</div>@endif
    </div>
    @endforeach
    @if($wisudaBuka->isEmpty() && !$pendaftaran)<div class="bg-white rounded-xl border border-gray-200 p-10 text-center text-sm text-gray-400">Belum ada periode wisuda</div>@endif
</div>
</div>
@endsection

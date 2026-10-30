@extends('layouts.app')
@section('title',$beasiswa->nama)
@section('header-action')<a href="{{ route('beasiswa.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-lg">← Kembali</a>@endsection
@section('content')
<div class="max-w-2xl space-y-4">
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex justify-between mb-3"><div><div class="text-xs text-gray-400">{{ $beasiswa->penyelenggara }}</div><h2 class="text-xl font-bold text-gray-900">{{ $beasiswa->nama }}</h2></div><span class="text-xs px-2.5 py-1 rounded-full h-fit {{ $beasiswa->status_badge['class'] }}">{{ $beasiswa->status_badge['label'] }}</span></div>
    <p class="text-sm text-gray-600 mb-4">{{ $beasiswa->deskripsi }}</p>
    <div class="grid grid-cols-4 gap-3 text-center">
        <div class="bg-indigo-50 rounded-lg p-3"><div class="text-xs text-indigo-400">Nominal</div><div class="font-bold text-indigo-700 text-sm">{{ $beasiswa->nominal_format }}</div></div>
        <div class="bg-gray-50 rounded-lg p-3"><div class="text-xs text-gray-400">Kuota</div><div class="font-bold text-gray-900">{{ $beasiswa->kuota }}</div></div>
        <div class="bg-gray-50 rounded-lg p-3"><div class="text-xs text-gray-400">Min. IPK</div><div class="font-bold text-gray-900">{{ number_format($beasiswa->min_ipk,2) }}</div></div>
        <div class="bg-gray-50 rounded-lg p-3"><div class="text-xs text-gray-400">Tutup</div><div class="font-bold text-gray-900 text-sm">{{ $beasiswa->tanggal_tutup->format('d M Y') }}</div></div>
    </div>
</div>
@if($sudahDaftar)
<div class="bg-white rounded-xl border border-gray-200 p-6"><h3 class="text-sm font-semibold text-gray-900 mb-2">Status Pendaftaran Anda</h3>
    <span class="text-sm px-3 py-1 rounded-full {{ $sudahDaftar->status_badge['class'] }}">{{ $sudahDaftar->status_badge['label'] }}</span>
    @if($sudahDaftar->catatan_admin)<p class="text-sm text-gray-500 mt-3">Catatan: {{ $sudahDaftar->catatan_admin }}</p>@endif
    <p class="text-xs text-gray-400 mt-3">Motivasi: {{ $sudahDaftar->motivasi }}</p></div>
@elseif($beasiswa->isOpen())
<div class="bg-white rounded-xl border border-gray-200 p-6"><h3 class="text-sm font-semibold text-gray-900 mb-3">Daftar Beasiswa</h3>
    @if($mhs->ipk < $beasiswa->min_ipk)<div class="bg-red-50 text-red-600 text-sm rounded-lg px-4 py-3">IPK Anda ({{ number_format($mhs->ipk,2) }}) belum memenuhi syarat minimum {{ number_format($beasiswa->min_ipk,2) }}.</div>
    @else
    <form action="{{ route('beasiswa.daftar',$beasiswa) }}" method="POST">@csrf
        <label class="block text-xs font-medium text-gray-600 mb-1">Motivasi (min. 100 karakter)</label>
        <textarea name="motivasi" rows="5" required minlength="100" oninput="document.getElementById('cnt').textContent=this.value.length" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('motivasi') }}</textarea>
        <div class="text-xs text-gray-400 mt-1"><span id="cnt">0</span> karakter</div>
        <button class="mt-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg">Kirim Pendaftaran</button>
    </form>@endif
</div>
@else<div class="bg-gray-50 rounded-xl p-6 text-center text-sm text-gray-400">Pendaftaran beasiswa ini sudah ditutup</div>@endif
</div>
@endsection

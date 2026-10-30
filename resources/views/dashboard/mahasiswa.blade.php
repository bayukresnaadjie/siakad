@extends('layouts.app')
@section('title','Dashboard Mahasiswa')
@section('subtitle', $mhs->nama . ' · ' . $mhs->nim . ' · ' . $mhs->prodi->nama)
@section('content')
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
    <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl p-5 text-white"><div class="text-xs text-indigo-200 mb-1">IPK</div><div class="text-3xl font-bold">{{ number_format($mhs->ipk,2) }}</div><div class="text-xs text-indigo-200 mt-1">{{ $mhs->predikat }}</div></div>
    <div class="bg-white rounded-xl border border-gray-200 p-5"><div class="text-xs text-gray-400 mb-1">SKS Lulus</div><div class="text-2xl font-bold text-gray-900">{{ $mhs->total_sks_lulus }}</div><div class="text-xs text-gray-400 mt-1">dari {{ $mhs->prodi->total_sks_lulus }} SKS</div></div>
    <div class="bg-white rounded-xl border border-gray-200 p-5"><div class="text-xs text-gray-400 mb-1">SKS Semester Ini</div><div class="text-2xl font-bold text-indigo-600">{{ $totalSks }}</div><div class="text-xs text-gray-400 mt-1">maks {{ $mhs->prodi->maks_sks }} SKS</div></div>
    <div class="bg-white rounded-xl border border-gray-200 p-5"><div class="text-xs text-gray-400 mb-1">Semester</div><div class="text-2xl font-bold text-gray-900">{{ $mhs->semester_aktif }}</div><span class="text-xs px-2 py-0.5 rounded-full {{ $mhs->status_badge['class'] }}">{{ $mhs->status_badge['label'] }}</span></div>
</div>
<div class="grid grid-cols-3 gap-4 mb-4">
    <div class="col-span-2 bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100"><h2 class="text-sm font-semibold text-gray-900">📅 Jadwal Hari Ini</h2></div>
        <div class="divide-y divide-gray-50">
            @forelse($jadwalHariIni as $j)
            <div class="px-5 py-3 flex items-center gap-4">
                <div class="text-center bg-indigo-50 rounded-lg px-3 py-2"><div class="text-sm font-bold text-indigo-700">{{ substr($j->jam_mulai,0,5) }}</div><div class="text-xs text-indigo-400">{{ substr($j->jam_selesai,0,5) }}</div></div>
                <div><div class="text-sm font-medium text-gray-900">{{ $j->kelas->mataKuliah->nama }}</div><div class="text-xs text-gray-400">{{ $j->ruangan?->nama ?? '-' }} · {{ $j->kelas->dosen->nama_lengkap }}</div></div>
            </div>
            @empty<div class="px-5 py-8 text-center text-sm text-gray-400">Tidak ada kuliah hari ini</div>@endforelse
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-sm font-semibold text-gray-900 mb-3">💳 SPP Semester Ini</h2>
        @if($tagihan)
        <div class="text-xl font-bold text-gray-900">{{ $tagihan->total_format }}</div>
        <span class="text-xs px-2 py-0.5 rounded-full {{ $tagihan->status_badge['class'] }}">{{ $tagihan->status_badge['label'] }}</span>
        <div class="text-xs text-gray-400 mt-2">Jatuh tempo {{ $tagihan->jatuh_tempo->format('d M Y') }}</div>
        <a href="{{ route('spp.index') }}" class="block text-center mt-4 bg-indigo-50 text-indigo-700 text-sm font-medium py-2 rounded-lg hover:bg-indigo-100">Lihat Tagihan</a>
        @else<div class="text-sm text-gray-400">Belum ada tagihan</div>@endif
    </div>
</div>
<div class="grid grid-cols-2 gap-4">
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100 flex justify-between"><h2 class="text-sm font-semibold text-gray-900">📝 KRS Semester Ini</h2><a href="{{ route('krs.index') }}" class="text-xs text-indigo-600 hover:underline">Kelola</a></div>
        <div class="divide-y divide-gray-50">
            @forelse($krsAktif as $k)
            <div class="px-5 py-2.5 flex items-center justify-between"><div><div class="text-sm text-gray-900">{{ $k->kelas->mataKuliah->nama }}</div><div class="text-xs text-gray-400">{{ $k->kelas->mataKuliah->sks }} SKS</div></div><span class="text-xs px-2 py-0.5 rounded-full {{ $k->status_badge['class'] }}">{{ $k->status_badge['label'] }}</span></div>
            @empty<div class="px-5 py-8 text-center text-sm text-gray-400">Belum mengisi KRS</div>@endforelse
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100 flex justify-between"><h2 class="text-sm font-semibold text-gray-900">📊 Nilai Terbaru</h2><a href="{{ route('nilai.mahasiswa') }}" class="text-xs text-indigo-600 hover:underline">Semua</a></div>
        <div class="divide-y divide-gray-50">
            @forelse($nilaiTerbaru as $n)
            <div class="px-5 py-2.5 flex items-center justify-between"><div><div class="text-sm text-gray-900">{{ $n->kelas->mataKuliah->nama }}</div><div class="text-xs text-gray-400">{{ $n->semester->nama }}</div></div>
            @if($n->huruf)<span class="text-sm font-bold px-2.5 py-1 rounded-lg {{ $n->huruf_badge['class'] }}">{{ $n->huruf }}</span>@else<span class="text-xs text-gray-300">belum ada</span>@endif</div>
            @empty<div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada nilai</div>@endforelse
        </div>
    </div>
</div>
@endsection

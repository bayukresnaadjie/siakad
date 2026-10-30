@extends('layouts.app')
@section('title','Kartu Hasil Studi')
@section('subtitle', $mhs->nama . ' · ' . $semester->nama)
@section('header-action')
<div class="flex gap-2 no-print"><a href="{{ route('nilai.mahasiswa') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-lg">← Kembali</a>
<button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg">🖨️ Cetak</button></div>
@endsection
@section('content')
@php $sksAmbil = $nilai->sum(fn($n)=>$n->kelas->mataKuliah->sks); $mutu = $nilai->sum(fn($n)=>$n->bobot*$n->kelas->mataKuliah->sks); $ip = $khs?->ip_semester ?? ($sksAmbil>0 ? round($mutu/$sksAmbil,2) : 0); @endphp
<div class="bg-white rounded-xl border border-gray-200 p-8 max-w-3xl">
    <div class="text-center border-b-2 border-gray-800 pb-3 mb-5"><h1 class="text-lg font-bold">KARTU HASIL STUDI (KHS)</h1><div class="text-xs text-gray-500">{{ $semester->nama }}</div></div>
    <div class="text-sm mb-5 grid grid-cols-2 gap-1"><div>Nama: <b>{{ $mhs->nama }}</b></div><div>Prodi: <b>{{ $mhs->prodi->nama }}</b></div><div>NIM: <b>{{ $mhs->nim }}</b></div><div>Semester: <b>{{ $mhs->semester_aktif }}</b></div></div>
    <table class="w-full text-sm border border-gray-200"><thead class="bg-gray-50"><tr><th class="text-left px-3 py-2 text-xs text-gray-500">Kode</th><th class="text-left px-3 py-2 text-xs text-gray-500">Mata Kuliah</th><th class="px-3 py-2 text-xs text-gray-500">SKS</th><th class="px-3 py-2 text-xs text-gray-500">Nilai</th><th class="px-3 py-2 text-xs text-gray-500">Mutu</th></tr></thead>
    <tbody>@forelse($nilai as $n)<tr class="border-t border-gray-100"><td class="px-3 py-2 font-mono text-xs">{{ $n->kelas->mataKuliah->kode }}</td><td class="px-3 py-2">{{ $n->kelas->mataKuliah->nama }}</td><td class="px-3 py-2 text-center">{{ $n->kelas->mataKuliah->sks }}</td><td class="px-3 py-2 text-center font-bold">{{ $n->huruf }}</td><td class="px-3 py-2 text-center">{{ number_format($n->bobot*$n->kelas->mataKuliah->sks,2) }}</td></tr>
    @empty<tr><td colspan="5" class="px-3 py-8 text-center text-gray-400">Belum ada nilai semester ini</td></tr>@endforelse</tbody></table>
    <div class="grid grid-cols-3 gap-4 mt-5 text-center border-t-2 border-gray-800 pt-4">
        <div><div class="text-xs text-gray-500">SKS Diambil</div><div class="text-xl font-bold">{{ $khs?->total_sks_ambil ?? $sksAmbil }}</div></div>
        <div><div class="text-xs text-gray-500">IP Semester</div><div class="text-xl font-bold text-indigo-600">{{ number_format($ip,2) }}</div></div>
        <div><div class="text-xs text-gray-500">IPK</div><div class="text-xl font-bold">{{ number_format($khs?->ipk ?? $mhs->ipk,2) }}</div></div>
    </div>
    @unless($khs)<div class="text-xs text-amber-600 mt-4">* KHS resmi dibuat setelah nilai dikunci oleh bagian akademik.</div>@endunless
</div>
@endsection

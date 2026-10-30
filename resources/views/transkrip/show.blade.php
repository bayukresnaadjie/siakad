@extends('layouts.app')
@section('title','Transkrip Akademik')
@section('subtitle', $mahasiswa->nama . ' · ' . $mahasiswa->nim)
@section('header-action')<button onclick="window.print()" class="no-print bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg">🖨️ Cetak / Simpan PDF</button>@endsection
@section('content')
@php $pred = match(true){ $ipk>=3.51=>'Cumlaude', $ipk>=2.76=>'Sangat Memuaskan', $ipk>=2.00=>'Memuaskan', default=>'Kurang Memuaskan' }; @endphp
<div class="bg-white rounded-xl border border-gray-200 p-8 max-w-4xl">
    <div class="text-center border-b-2 border-gray-800 pb-4 mb-5">
        <div class="text-xs tracking-widest text-gray-500">UNIVERSITAS</div>
        <h1 class="text-xl font-bold text-gray-900">TRANSKRIP AKADEMIK</h1>
        <div class="text-xs text-gray-500">{{ $mahasiswa->prodi->fakultas->nama }}</div>
    </div>
    <div class="grid grid-cols-2 gap-x-8 gap-y-1 text-sm mb-6">
        <div class="flex"><span class="w-32 text-gray-500">Nama</span><b>: {{ $mahasiswa->nama }}</b></div>
        <div class="flex"><span class="w-32 text-gray-500">Program Studi</span><b>: {{ $mahasiswa->prodi->nama }} ({{ $mahasiswa->prodi->jenjang }})</b></div>
        <div class="flex"><span class="w-32 text-gray-500">NIM</span><b>: {{ $mahasiswa->nim }}</b></div>
        <div class="flex"><span class="w-32 text-gray-500">Angkatan</span><b>: {{ $mahasiswa->angkatan }}</b></div>
        <div class="flex"><span class="w-32 text-gray-500">TTL</span><b>: {{ $mahasiswa->tempat_lahir }}, {{ $mahasiswa->tanggal_lahir?->format('d M Y') }}</b></div>
        <div class="flex"><span class="w-32 text-gray-500">Dosen Wali</span><b>: {{ $mahasiswa->dosenWali?->nama_lengkap ?? '-' }}</b></div>
    </div>
    @forelse($nilaiPerSemester as $semId => $list)
    <div class="mb-5">
        <div class="text-sm font-semibold text-gray-800 bg-gray-100 px-3 py-1.5 rounded-t">{{ $list->first()->semester->nama }}</div>
        <table class="w-full text-sm border border-gray-200"><thead class="bg-gray-50"><tr>
            <th class="text-left px-3 py-1.5 text-xs text-gray-500 border-b">Kode</th><th class="text-left px-3 py-1.5 text-xs text-gray-500 border-b">Mata Kuliah</th>
            <th class="px-3 py-1.5 text-xs text-gray-500 border-b">SKS</th><th class="px-3 py-1.5 text-xs text-gray-500 border-b">Nilai</th><th class="px-3 py-1.5 text-xs text-gray-500 border-b">Bobot</th><th class="px-3 py-1.5 text-xs text-gray-500 border-b">Mutu</th></tr></thead>
        <tbody>
            @foreach($list as $n)
            <tr class="{{ $n->lulus ? '' : 'text-red-500' }}"><td class="px-3 py-1.5 font-mono text-xs border-b border-gray-50">{{ $n->kelas->mataKuliah->kode }}</td><td class="px-3 py-1.5 border-b border-gray-50">{{ $n->kelas->mataKuliah->nama }}</td>
            <td class="px-3 py-1.5 text-center border-b border-gray-50">{{ $n->kelas->mataKuliah->sks }}</td><td class="px-3 py-1.5 text-center font-bold border-b border-gray-50">{{ $n->huruf }}</td>
            <td class="px-3 py-1.5 text-center border-b border-gray-50">{{ number_format($n->bobot,2) }}</td><td class="px-3 py-1.5 text-center border-b border-gray-50">{{ number_format($n->bobot * $n->kelas->mataKuliah->sks,2) }}</td></tr>
            @endforeach
        </tbody></table>
    </div>
    @empty<div class="text-center text-sm text-gray-400 py-8">Belum ada nilai</div>@endforelse
    <div class="grid grid-cols-3 gap-4 border-t-2 border-gray-800 pt-4 mt-6 text-center">
        <div><div class="text-xs text-gray-500">Total SKS Lulus</div><div class="text-2xl font-bold text-gray-900">{{ $totalSks }}</div></div>
        <div><div class="text-xs text-gray-500">IPK</div><div class="text-2xl font-bold text-indigo-600">{{ number_format($ipk,2) }}</div></div>
        <div><div class="text-xs text-gray-500">Predikat</div><div class="text-lg font-bold text-gray-900">{{ $pred }}</div></div>
    </div>
    <div class="text-xs text-gray-400 mt-6">Dicetak: {{ now()->format('d F Y H:i') }} · Dokumen ini dihasilkan sistem SIAKAD.</div>
</div>
@endsection

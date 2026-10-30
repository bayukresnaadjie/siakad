@extends('layouts.app')
@section('title','Dashboard Dosen')
@section('subtitle', $dosen->nama_lengkap . ' · NIDN ' . $dosen->nidn)
@section('content')
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
    <div class="bg-white rounded-xl border border-gray-200 p-5"><div class="text-xs text-gray-400 mb-1">Kelas Diampu</div><div class="text-2xl font-bold text-gray-900">{{ $kelasSaya->count() }}</div></div>
    <div class="bg-white rounded-xl border border-gray-200 p-5"><div class="text-xs text-gray-400 mb-1">Total Mahasiswa</div><div class="text-2xl font-bold text-indigo-600">{{ $totalMahasiswa }}</div></div>
    <div class="bg-white rounded-xl border border-gray-200 p-5"><div class="text-xs text-gray-400 mb-1">Nilai Belum Diinput</div><div class="text-2xl font-bold {{ $nilaiBelumDiinput>0?'text-amber-600':'text-gray-300' }}">{{ $nilaiBelumDiinput }}</div></div>
    <div class="bg-white rounded-xl border border-gray-200 p-5"><div class="text-xs text-gray-400 mb-1">Mahasiswa Wali</div><div class="text-2xl font-bold text-gray-900">{{ $mahasiswaWali->count() }}</div></div>
</div>
<div class="bg-white rounded-xl border border-gray-200 mb-4">
    <div class="px-5 py-3 border-b border-gray-100"><h2 class="text-sm font-semibold text-gray-900">📚 Kelas Saya</h2></div>
    <table class="w-full text-sm"><thead class="bg-gray-50"><tr>
        <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Mata Kuliah</th><th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Jadwal</th>
        <th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-400">Mhs</th><th class="text-right px-5 py-2.5 text-xs font-semibold text-gray-400">Aksi</th></tr></thead>
    <tbody class="divide-y divide-gray-50">
        @forelse($kelasSaya as $k)
        <tr class="hover:bg-gray-50">
            <td class="px-5 py-3"><div class="font-medium text-gray-900">{{ $k->mataKuliah->nama }}</div><div class="text-xs text-gray-400">{{ $k->mataKuliah->kode }} · Kelas {{ $k->nama_kelas }} · {{ $k->mataKuliah->sks }} SKS</div></td>
            <td class="px-5 py-3 text-xs text-gray-500">@foreach($k->jadwal as $j){{ $j->hari_label }}, {{ substr($j->jam_mulai,0,5) }}-{{ substr($j->jam_selesai,0,5) }} ({{ $j->ruangan?->kode }})<br>@endforeach</td>
            <td class="px-5 py-3 text-center font-bold text-gray-900">{{ $k->jumlah_mahasiswa }}</td>
            <td class="px-5 py-3 text-right"><div class="flex justify-end gap-1">
                <a href="{{ route('absensi.index', $k) }}" class="text-xs text-blue-600 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-lg">Absensi</a>
                <a href="{{ route('nilai.kelas', $k) }}" class="text-xs text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg">Nilai</a></div></td>
        </tr>
        @empty<tr><td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada kelas semester ini</td></tr>@endforelse
    </tbody></table>
</div>
<div class="grid grid-cols-2 gap-4">
    @include('partials.krs-menunggu')
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100"><h2 class="text-sm font-semibold text-gray-900">👥 Mahasiswa Wali</h2></div>
        <div class="divide-y divide-gray-50">
            @forelse($mahasiswaWali as $m)
            <div class="px-5 py-2.5 flex items-center justify-between"><div><div class="text-sm font-medium text-gray-900">{{ $m->nama }}</div><div class="text-xs text-gray-400">{{ $m->nim }} · {{ $m->prodi->singkatan }}</div></div>
            <div class="text-right"><div class="text-sm font-bold text-indigo-600">IPK {{ number_format($m->ipk,2) }}</div><a href="{{ route('transkrip.mahasiswa', $m) }}" class="text-xs text-gray-400 hover:text-indigo-600">Transkrip</a></div></div>
            @empty<div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada mahasiswa wali</div>@endforelse
        </div>
    </div>
</div>
@endsection

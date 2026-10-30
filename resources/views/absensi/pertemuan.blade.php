@extends('layouts.app')
@section('title','Absensi Pertemuan ke-'.$pertemuan->pertemuan_ke)
@section('subtitle', $kelas->mataKuliah->nama.' · Kelas '.$kelas->nama_kelas)
@section('header-action')
<div class="flex gap-2">
    @if($pertemuan->pertemuan_ke > 1)<a href="{{ route('absensi.pertemuan',[$kelas,$pertemuan->pertemuan_ke-1]) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-3 py-2 rounded-lg">← Sebelumnya</a>@endif
    @if($pertemuan->pertemuan_ke < $kelas->jumlah_pertemuan)<a href="{{ route('absensi.pertemuan',[$kelas,$pertemuan->pertemuan_ke+1]) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-3 py-2 rounded-lg">Berikutnya →</a>@endif
    <a href="{{ route('absensi.index',$kelas) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-3 py-2 rounded-lg">Rekap</a>
</div>
@endsection
@section('content')
<form action="{{ route('absensi.store',[$kelas,$pertemuan]) }}" method="POST">@csrf
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-4 grid grid-cols-3 gap-4">
    <div><label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label><input type="date" name="tanggal" value="{{ $pertemuan->tanggal->format('Y-m-d') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div><label class="block text-xs font-medium text-gray-600 mb-1">Tipe</label>
        <select name="tipe" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">@foreach(['kuliah'=>'Kuliah','uts'=>'UTS','uas'=>'UAS','praktikum'=>'Praktikum','kuis'=>'Kuis'] as $v=>$l)<option value="{{ $v }}" {{ $pertemuan->tipe===$v?'selected':'' }}>{{ $l }}</option>@endforeach</select></div>
    <div><label class="block text-xs font-medium text-gray-600 mb-1">Materi</label><input type="text" name="materi" value="{{ $pertemuan->materi }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
</div>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 flex justify-between items-center"><h2 class="text-sm font-semibold text-gray-900">Daftar Hadir — Pertemuan {{ $pertemuan->pertemuan_ke }}</h2>
        <button type="button" onclick="document.querySelectorAll('input[value=hadir]').forEach(r=>r.checked=true)" class="text-xs bg-green-50 text-green-700 hover:bg-green-100 px-3 py-1.5 rounded-lg">✓ Semua Hadir</button></div>
    <table class="w-full text-sm"><thead class="bg-gray-50"><tr>
        <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Mahasiswa</th>
        <th class="px-3 py-2.5 text-xs font-semibold text-green-600">Hadir</th><th class="px-3 py-2.5 text-xs font-semibold text-blue-600">Izin</th>
        <th class="px-3 py-2.5 text-xs font-semibold text-yellow-600">Sakit</th><th class="px-3 py-2.5 text-xs font-semibold text-red-600">Alpha</th>
        <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Keterangan</th></tr></thead>
    <tbody class="divide-y divide-gray-50">
        @foreach($mahasiswaList as $i=>$krs)
        @php $a = $krs->mahasiswa->absensi->first(); $cur = $a?->status ?? 'hadir'; @endphp
        <tr class="hover:bg-gray-50"><td class="px-5 py-2.5"><div class="font-medium text-gray-900">{{ $krs->mahasiswa->nama }}</div><div class="text-xs font-mono text-gray-400">{{ $krs->mahasiswa->nim }}</div>
            <input type="hidden" name="absensi[{{ $i }}][mahasiswa_id]" value="{{ $krs->mahasiswa_id }}"/></td>
        @foreach(['hadir','izin','sakit','alpha'] as $st)
        <td class="px-3 py-2.5 text-center"><input type="radio" name="absensi[{{ $i }}][status]" value="{{ $st }}" {{ $cur===$st?'checked':'' }} class="w-4 h-4"/></td>
        @endforeach
        <td class="px-5 py-2.5"><input type="text" name="absensi[{{ $i }}][keterangan]" value="{{ $a?->keterangan }}" class="w-full border border-gray-200 rounded-lg px-2 py-1 text-xs"/></td></tr>
        @endforeach
    </tbody></table>
</div>
<div class="mt-4"><button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg">💾 Simpan Absensi</button></div>
</form>
@endsection

@extends('layouts.app')
@section('title','Absensi Kelas')
@section('subtitle', $kelas->mataKuliah->nama.' · Kelas '.$kelas->nama_kelas.' · '.$kelas->semester->nama)
@section('header-action')<a href="{{ route('nilai.kelas',$kelas) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg">📊 Input Nilai</a>@endsection
@section('content')
@php $ada = $kelas->pertemuan->pluck('pertemuan_ke')->all(); @endphp
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-5">
    <h2 class="text-sm font-semibold text-gray-900 mb-3">Pilih Pertemuan</h2>
    <div class="grid grid-cols-8 gap-2">
        @for($p=1; $p<=$kelas->jumlah_pertemuan; $p++)
        <a href="{{ route('absensi.pertemuan',[$kelas,$p]) }}" class="text-center py-2.5 rounded-lg text-sm font-semibold border transition {{ in_array($p,$ada) ? 'bg-green-50 border-green-200 text-green-700 hover:bg-green-100' : 'bg-gray-50 border-gray-200 text-gray-500 hover:border-indigo-300' }}">{{ $p }}{{ in_array($p,$ada)?' ✓':'' }}</a>
        @endfor
    </div>
    <div class="text-xs text-gray-400 mt-3">✓ = pertemuan sudah dibuat</div>
</div>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h2 class="text-sm font-semibold text-gray-900">📋 Rekap Kehadiran</h2></div>
    <table class="w-full text-sm"><thead class="bg-gray-50"><tr>
        <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Mahasiswa</th>
        <th class="px-3 py-2.5 text-xs font-semibold text-green-600">Hadir</th><th class="px-3 py-2.5 text-xs font-semibold text-blue-600">Izin</th>
        <th class="px-3 py-2.5 text-xs font-semibold text-yellow-600">Sakit</th><th class="px-3 py-2.5 text-xs font-semibold text-red-600">Alpha</th>
        <th class="px-5 py-2.5 text-xs font-semibold text-gray-400">Kehadiran</th></tr></thead>
    <tbody class="divide-y divide-gray-50">
        @forelse($mahasiswaList as $krs)
        @php $r = $rekapAbsensi[$krs->mahasiswa_id]; @endphp
        <tr class="hover:bg-gray-50"><td class="px-5 py-2.5"><div class="font-medium text-gray-900">{{ $krs->mahasiswa->nama }}</div><div class="text-xs font-mono text-gray-400">{{ $krs->mahasiswa->nim }}</div></td>
        <td class="px-3 py-2.5 text-center font-bold text-green-600">{{ $r['hadir'] }}</td><td class="px-3 py-2.5 text-center">{{ $r['izin'] }}</td>
        <td class="px-3 py-2.5 text-center">{{ $r['sakit'] }}</td><td class="px-3 py-2.5 text-center {{ $r['alpha']>0?'text-red-600 font-bold':'' }}">{{ $r['alpha'] }}</td>
        <td class="px-5 py-2.5"><div class="flex items-center gap-2"><div class="flex-1 bg-gray-100 rounded-full h-2"><div class="h-2 rounded-full {{ $r['persen']<75?'bg-red-400':'bg-green-500' }}" style="width:{{ $r['persen'] }}%"></div></div><span class="text-xs font-bold {{ $r['persen']<75?'text-red-600':'text-gray-700' }}">{{ $r['persen'] }}%</span></div></td></tr>
        @empty<tr><td colspan="6" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada mahasiswa</td></tr>@endforelse
    </tbody></table>
</div>
@endsection

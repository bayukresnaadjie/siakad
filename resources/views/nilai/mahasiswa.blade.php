@extends('layouts.app')
@section('title','Nilai & KHS')
@section('subtitle', $mhs->nama . ' · IPK ' . number_format($mhs->ipk,2))
@section('header-action')
<a href="{{ route('transkrip.show') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg">🎓 Lihat Transkrip</a>
@endsection
@section('content')
@if($khs->count() > 0)
<div class="grid grid-cols-4 gap-4 mb-5">
    @foreach($khs as $k)
    <a href="{{ route('khs.show',$k->semester_id) }}" class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-indigo-300 transition">
        <div class="text-xs text-gray-400 mb-1">{{ $k->semester->nama }}</div>
        <div class="text-2xl font-bold text-indigo-600">{{ number_format($k->ip_semester,2) }}</div>
        <div class="text-xs text-gray-400 mt-1">IP Semester · {{ $k->total_sks_lulus }}/{{ $k->total_sks_ambil }} SKS</div>
    </a>
    @endforeach
</div>
@endif
@forelse($nilaiPerSemester as $semId => $list)
<div class="bg-white rounded-xl border border-gray-200 mb-4 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex justify-between"><h2 class="text-sm font-semibold text-gray-900">{{ $list->first()->semester->nama }}</h2>
        <a href="{{ route('khs.show',$semId) }}" class="text-xs text-indigo-600 hover:underline">Lihat KHS →</a></div>
    <table class="w-full text-sm"><thead><tr>
        <th class="text-left px-5 py-2 text-xs font-semibold text-gray-400">Mata Kuliah</th><th class="px-3 py-2 text-xs font-semibold text-gray-400">SKS</th>
        <th class="px-3 py-2 text-xs font-semibold text-gray-400">Tugas</th><th class="px-3 py-2 text-xs font-semibold text-gray-400">Kuis</th><th class="px-3 py-2 text-xs font-semibold text-gray-400">UTS</th><th class="px-3 py-2 text-xs font-semibold text-gray-400">UAS</th>
        <th class="px-3 py-2 text-xs font-semibold text-gray-400">Akhir</th><th class="px-3 py-2 text-xs font-semibold text-gray-400">Huruf</th><th class="px-3 py-2 text-xs font-semibold text-gray-400">Bobot</th></tr></thead>
    <tbody class="divide-y divide-gray-50">
        @foreach($list as $n)
        <tr><td class="px-5 py-2.5"><div class="font-medium text-gray-900">{{ $n->kelas->mataKuliah->nama }}</div><div class="text-xs text-gray-400">{{ $n->kelas->mataKuliah->kode }}</div></td>
        <td class="px-3 py-2.5 text-center">{{ $n->kelas->mataKuliah->sks }}</td>
        <td class="px-3 py-2.5 text-center text-gray-500">{{ $n->nilai_tugas }}</td><td class="px-3 py-2.5 text-center text-gray-500">{{ $n->nilai_kuis }}</td>
        <td class="px-3 py-2.5 text-center text-gray-500">{{ $n->nilai_uts }}</td><td class="px-3 py-2.5 text-center text-gray-500">{{ $n->nilai_uas }}</td>
        <td class="px-3 py-2.5 text-center font-bold">{{ $n->nilai_akhir }}</td>
        <td class="px-3 py-2.5 text-center"><span class="text-xs font-bold px-2.5 py-1 rounded-lg {{ $n->huruf_badge['class'] }}">{{ $n->huruf }}</span></td>
        <td class="px-3 py-2.5 text-center text-gray-600">{{ number_format($n->bobot,2) }}</td></tr>
        @endforeach
    </tbody></table>
</div>
@empty
<div class="bg-white rounded-xl border border-gray-200 p-12 text-center"><div class="text-4xl mb-2">📊</div><div class="text-sm text-gray-400">Belum ada nilai yang diinput</div></div>
@endforelse
@endsection

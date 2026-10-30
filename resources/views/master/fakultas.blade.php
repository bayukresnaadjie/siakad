@extends('layouts.app')
@section('title','Fakultas & Program Studi')
@section('content')
<div class="grid grid-cols-1 gap-4">
@foreach($fakultas as $f)
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 bg-indigo-50 border-b border-indigo-100 flex justify-between"><div><h2 class="font-bold text-indigo-900">{{ $f->nama }}</h2><div class="text-xs text-indigo-500">{{ $f->kode }} · Dekan: {{ $f->dekan }}</div></div>
        <div class="text-right text-xs text-indigo-600"><div>{{ $f->programStudi->count() }} prodi</div><div>{{ $f->dosen_count }} dosen</div></div></div>
    <table class="w-full text-sm"><thead class="bg-gray-50"><tr><th class="text-left px-5 py-2 text-xs font-semibold text-gray-400">Kode</th><th class="text-left px-5 py-2 text-xs font-semibold text-gray-400">Program Studi</th><th class="text-center px-5 py-2 text-xs font-semibold text-gray-400">Jenjang</th><th class="text-left px-5 py-2 text-xs font-semibold text-gray-400">Ketua Prodi</th><th class="text-center px-5 py-2 text-xs font-semibold text-gray-400">Maks SKS</th><th class="text-center px-5 py-2 text-xs font-semibold text-gray-400">SKS Lulus</th></tr></thead>
    <tbody class="divide-y divide-gray-50">@foreach($f->programStudi as $p)<tr><td class="px-5 py-2.5 font-mono text-xs text-indigo-600">{{ $p->kode }}</td><td class="px-5 py-2.5 font-medium text-gray-900">{{ $p->nama }}</td>
    <td class="px-5 py-2.5 text-center"><span class="text-xs px-2 py-0.5 rounded-full {{ $p->jenjang_badge['class'] }}">{{ $p->jenjang_badge['label'] }}</span></td><td class="px-5 py-2.5 text-xs text-gray-600">{{ $p->ketua_prodi }}</td>
    <td class="px-5 py-2.5 text-center">{{ $p->maks_sks }}</td><td class="px-5 py-2.5 text-center">{{ $p->total_sks_lulus }}</td></tr>@endforeach</tbody></table>
</div>
@endforeach
</div>
@endsection

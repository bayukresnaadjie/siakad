@extends('layouts.app')
@section('title','Mata Kuliah')
@section('subtitle', $mk->total() . ' mata kuliah')
@section('content')
<form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex gap-3">
    <input name="search" value="{{ request('search') }}" placeholder="Cari nama / kode..." class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm"/>
    <select name="prodi_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm"><option value="">Semua Prodi</option>@foreach($prodiList as $p)<option value="{{ $p->id }}" {{ request('prodi_id')==$p->id?'selected':'' }}>{{ $p->nama }}</option>@endforeach</select>
    <select name="semester" class="border border-gray-200 rounded-lg px-3 py-2 text-sm"><option value="">Semua Smt</option>@for($i=1;$i<=8;$i++)<option value="{{ $i }}" {{ request('semester')==$i?'selected':'' }}>Semester {{ $i }}</option>@endfor</select>
    <button class="bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-lg">Filter</button>
</form>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden"><table class="w-full text-sm"><thead class="bg-gray-50 border-b border-gray-200"><tr>
    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Kode</th><th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Mata Kuliah</th><th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Prodi</th>
    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">SKS</th><th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">Smt</th><th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">Tipe</th></tr></thead>
<tbody class="divide-y divide-gray-100">
    @forelse($mk as $m)
    <tr class="hover:bg-gray-50"><td class="px-5 py-2.5 font-mono text-xs text-indigo-600">{{ $m->kode }}</td><td class="px-5 py-2.5 font-medium text-gray-900">{{ $m->nama }}</td><td class="px-5 py-2.5 text-xs text-gray-500">{{ $m->prodi->singkatan }}</td>
    <td class="px-5 py-2.5 text-center font-bold">{{ $m->sks }}</td><td class="px-5 py-2.5 text-center text-xs">{{ $m->semester }}</td>
    <td class="px-5 py-2.5 text-center"><span class="text-xs px-2 py-0.5 rounded-full {{ $m->tipe_badge['class'] }}">{{ $m->tipe_badge['label'] }}</span></td></tr>
    @empty<tr><td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">Tidak ada data</td></tr>@endforelse
</tbody></table>@if($mk->hasPages())<div class="px-5 py-3 border-t">{{ $mk->links() }}</div>@endif</div>
@endsection

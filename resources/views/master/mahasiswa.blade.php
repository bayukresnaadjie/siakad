@extends('layouts.app')
@section('title','Data Mahasiswa')
@section('subtitle', $mahasiswa->total() . ' mahasiswa')
@section('content')
<form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex gap-3">
    <input name="search" value="{{ request('search') }}" placeholder="Cari nama / NIM..." class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm"/>
    <select name="prodi_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm"><option value="">Semua Prodi</option>@foreach($prodiList as $p)<option value="{{ $p->id }}" {{ request('prodi_id')==$p->id?'selected':'' }}>{{ $p->nama }}</option>@endforeach</select>
    <input name="angkatan" value="{{ request('angkatan') }}" placeholder="Angkatan" class="w-28 border border-gray-200 rounded-lg px-3 py-2 text-sm"/>
    <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm"><option value="">Semua Status</option>@foreach(['aktif','cuti','drop_out','lulus','mengundurkan_diri'] as $s)<option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach</select>
    <button class="bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-lg">Filter</button>
</form>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden"><table class="w-full text-sm"><thead class="bg-gray-50 border-b border-gray-200"><tr>
    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Mahasiswa</th><th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Prodi</th>
    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">Angkatan</th><th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">Smt</th>
    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">IPK</th><th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Dosen Wali</th>
    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">Status</th><th class="px-5 py-3"></th></tr></thead>
<tbody class="divide-y divide-gray-100">
    @forelse($mahasiswa as $m)
    <tr class="hover:bg-gray-50"><td class="px-5 py-3"><div class="flex items-center gap-3"><img src="{{ $m->avatar_url }}" class="w-8 h-8 rounded-full"/><div><div class="font-medium text-gray-900">{{ $m->nama }}</div><div class="text-xs font-mono text-gray-400">{{ $m->nim }}</div></div></div></td>
    <td class="px-5 py-3 text-xs text-gray-600">{{ $m->prodi->nama }}</td><td class="px-5 py-3 text-center text-xs">{{ $m->angkatan }}</td><td class="px-5 py-3 text-center text-xs">{{ $m->semester_aktif }}</td>
    <td class="px-5 py-3 text-center font-bold {{ $m->ipk>=3.5?'text-green-600':($m->ipk<2?'text-red-500':'text-gray-900') }}">{{ number_format($m->ipk,2) }}</td>
    <td class="px-5 py-3 text-xs text-gray-500">{{ $m->dosenWali?->nama ?? '-' }}</td>
    <td class="px-5 py-3 text-center"><span class="text-xs px-2 py-0.5 rounded-full {{ $m->status_badge['class'] }}">{{ $m->status_badge['label'] }}</span></td>
    <td class="px-5 py-3 text-right"><a href="{{ route('master.mahasiswa.show',$m) }}" class="text-xs text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg">Detail</a></td></tr>
    @empty<tr><td colspan="8" class="px-5 py-12 text-center text-sm text-gray-400">Tidak ada data</td></tr>@endforelse
</tbody></table>@if($mahasiswa->hasPages())<div class="px-5 py-3 border-t">{{ $mahasiswa->links() }}</div>@endif</div>
@endsection

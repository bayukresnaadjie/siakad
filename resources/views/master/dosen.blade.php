@extends('layouts.app')
@section('title','Data Dosen')
@section('subtitle', $dosen->total() . ' dosen')
@section('content')
<form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex gap-3"><input name="search" value="{{ request('search') }}" placeholder="Cari nama / NIDN..." class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm"/><button class="bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-lg">Cari</button></form>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden"><table class="w-full text-sm"><thead class="bg-gray-50 border-b border-gray-200"><tr>
    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Dosen</th><th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">NIDN</th><th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Fakultas / Prodi</th>
    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Jabatan</th><th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">Pend.</th><th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">Status</th></tr></thead>
<tbody class="divide-y divide-gray-100">
    @forelse($dosen as $d)
    <tr class="hover:bg-gray-50"><td class="px-5 py-3"><div class="flex items-center gap-3"><img src="{{ $d->avatar_url }}" class="w-8 h-8 rounded-full"/><div><div class="font-medium text-gray-900">{{ $d->nama_lengkap }}</div><div class="text-xs text-gray-400">{{ $d->email_kampus }}</div></div></div></td>
    <td class="px-5 py-3 font-mono text-xs">{{ $d->nidn }}</td><td class="px-5 py-3 text-xs text-gray-600">{{ $d->fakultas->singkatan }} / {{ $d->prodi?->nama ?? '-' }}</td>
    <td class="px-5 py-3 text-xs text-gray-600">{{ $d->jabatan_label }}</td><td class="px-5 py-3 text-center text-xs">{{ $d->pendidikan_terakhir }}</td>
    <td class="px-5 py-3 text-center"><span class="text-xs px-2 py-0.5 rounded-full {{ $d->status_badge['class'] }}">{{ $d->status_badge['label'] }}</span></td></tr>
    @empty<tr><td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">Tidak ada data</td></tr>@endforelse
</tbody></table>@if($dosen->hasPages())<div class="px-5 py-3 border-t">{{ $dosen->links() }}</div>@endif</div>
@endsection

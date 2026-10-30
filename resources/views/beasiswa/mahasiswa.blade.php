@extends('layouts.app')
@section('title','Beasiswa')
@section('subtitle','IPK Anda: ' . number_format($mhs->ipk,2))
@section('content')
<h2 class="text-sm font-semibold text-gray-700 mb-3">🏅 Beasiswa Dibuka</h2>
<div class="grid grid-cols-2 gap-4 mb-6">
    @forelse($beasiswaBuka as $b)
    <a href="{{ route('beasiswa.show',$b) }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md hover:border-indigo-300 transition">
        <div class="flex justify-between mb-2"><h3 class="font-semibold text-gray-900">{{ $b->nama }}</h3><span class="text-xs px-2 py-0.5 rounded-full {{ $b->status_badge['class'] }}">{{ $b->status_badge['label'] }}</span></div>
        <div class="text-xl font-bold text-indigo-600 mb-2">{{ $b->nominal_format }}</div>
        <div class="text-xs text-gray-500 space-y-0.5"><div>Min. IPK {{ number_format($b->min_ipk,2) }} {!! $mhs->ipk >= $b->min_ipk ? '<span class="text-green-600">✓ memenuhi</span>' : '<span class="text-red-500">✕ belum memenuhi</span>' !!}</div>
        <div>Kuota {{ $b->kuota }} · Tutup {{ $b->tanggal_tutup->format('d M Y') }}</div></div>
    </a>
    @empty<div class="col-span-2 bg-white rounded-xl border border-gray-200 p-10 text-center text-sm text-gray-400">Tidak ada beasiswa yang dibuka</div>@endforelse
</div>
<h2 class="text-sm font-semibold text-gray-700 mb-3">📋 Pendaftaran Saya</h2>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden"><table class="w-full text-sm"><thead class="bg-gray-50"><tr>
    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Beasiswa</th><th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Tanggal Daftar</th>
    <th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-400">Status</th><th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Catatan</th></tr></thead>
<tbody class="divide-y divide-gray-50">
    @forelse($pendaftaranku as $p)
    <tr><td class="px-5 py-3 font-medium text-gray-900">{{ $p->beasiswa->nama }}</td><td class="px-5 py-3 text-xs text-gray-500">{{ $p->created_at->format('d M Y') }}</td>
    <td class="px-5 py-3 text-center"><span class="text-xs px-2 py-0.5 rounded-full {{ $p->status_badge['class'] }}">{{ $p->status_badge['label'] }}</span></td><td class="px-5 py-3 text-xs text-gray-500">{{ $p->catatan_admin ?? '—' }}</td></tr>
    @empty<tr><td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">Belum pernah mendaftar beasiswa</td></tr>@endforelse
</tbody></table></div>
@endsection

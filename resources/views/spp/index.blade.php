@extends('layouts.app')
@section('title','Tagihan SPP')
@section('subtitle', ($semester?->nama ?? '-') . ' · ' . $tagihan->total() . ' tagihan')
@section('header-action')
@if($semester && in_array(auth()->user()->role,['keuangan','admin']))
<form action="{{ route('spp.generate',$semester) }}" method="POST" onsubmit="return confirm('Generate tagihan untuk semua mahasiswa aktif yang belum punya tagihan?')">@csrf
    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg">⚙️ Generate Tagihan</button></form>
@endif
@endsection
@section('content')
@php $canVerify = in_array(auth()->user()->role,['keuangan','admin']); @endphp
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm"><thead class="bg-gray-50 border-b border-gray-200"><tr>
        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Mahasiswa</th><th class="text-right px-5 py-3 text-xs font-semibold text-gray-400">Total</th>
        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">Status</th><th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Jatuh Tempo</th>
        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Pembayaran</th></tr></thead>
    <tbody class="divide-y divide-gray-100">
        @forelse($tagihan as $t)
        <tr class="hover:bg-gray-50 align-top">
            <td class="px-5 py-3"><div class="font-medium text-gray-900">{{ $t->mahasiswa->nama }}</div><div class="text-xs text-gray-400">{{ $t->mahasiswa->nim }} · {{ $t->mahasiswa->prodi->singkatan }}</div></td>
            <td class="px-5 py-3 text-right font-bold text-gray-900">{{ $t->total_format }}</td>
            <td class="px-5 py-3 text-center"><span class="text-xs px-2 py-0.5 rounded-full {{ $t->status_badge['class'] }}">{{ $t->status_badge['label'] }}</span></td>
            <td class="px-5 py-3 text-xs {{ $t->isTerlambat()?'text-red-500 font-medium':'text-gray-500' }}">{{ $t->jatuh_tempo->format('d M Y') }}</td>
            <td class="px-5 py-3">
                @forelse($t->pembayaran as $p)
                <div class="flex items-center justify-between gap-3 mb-1.5 text-xs">
                    <div><span class="font-semibold">Rp {{ number_format($p->jumlah_bayar,0,',','.') }}</span> <span class="text-gray-400">· {{ ucfirst(str_replace('_',' ',$p->metode)) }}</span>
                    @if($p->bukti_bayar) · <a href="{{ asset('storage/'.$p->bukti_bayar) }}" target="_blank" class="text-indigo-600 hover:underline">bukti</a>@endif</div>
                    @if($p->status==='pending' && $canVerify)
                    <form action="{{ route('spp.verifikasi',$p) }}" method="POST" class="flex gap-1">@csrf @method('PATCH')
                        <button name="aksi" value="verify" class="bg-green-600 text-white px-2 py-0.5 rounded">✓</button><button name="aksi" value="reject" class="bg-red-100 text-red-600 px-2 py-0.5 rounded">✕</button></form>
                    @else<span class="px-2 py-0.5 rounded-full {{ $p->status_badge['class'] }}">{{ $p->status_badge['label'] }}</span>@endif
                </div>
                @empty<span class="text-xs text-gray-300">Belum ada pembayaran</span>@endforelse
            </td>
        </tr>
        @empty<tr><td colspan="5" class="px-5 py-12 text-center text-sm text-gray-400">Belum ada tagihan. Klik "Generate Tagihan".</td></tr>@endforelse
    </tbody></table>
    @if($tagihan->hasPages())<div class="px-5 py-3 border-t">{{ $tagihan->links() }}</div>@endif
</div>
@endsection

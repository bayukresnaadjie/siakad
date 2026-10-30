@extends('layouts.app')
@section('title','Pembayaran SPP')
@section('subtitle', $mhs->nama . ' · ' . $mhs->nim)
@section('content')
@forelse($tagihan as $t)
@php $terbayar = $t->pembayaran->where('status','verified')->sum('jumlah_bayar'); $sisa = max(0, (float)$t->total - $terbayar); @endphp
<div class="bg-white rounded-xl border border-gray-200 mb-4 overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between border-b border-gray-100">
        <div><div class="font-semibold text-gray-900">{{ $t->semester->nama }}</div><div class="text-xs font-mono text-gray-400">{{ $t->nomor_tagihan }}</div></div>
        <div class="text-right"><span class="text-xs px-2.5 py-1 rounded-full {{ $t->status_badge['class'] }}">{{ $t->status_badge['label'] }}</span>
        @if($t->isTerlambat())<div class="text-xs text-red-500 mt-1">⚠ Lewat jatuh tempo</div>@endif</div>
    </div>
    <div class="px-5 py-4 grid grid-cols-4 gap-4 text-sm">
        <div><div class="text-xs text-gray-400">Tagihan</div><div class="font-semibold">Rp {{ number_format($t->jumlah,0,',','.') }}</div></div>
        <div><div class="text-xs text-gray-400">Denda</div><div class="font-semibold {{ $t->denda>0?'text-red-500':'' }}">Rp {{ number_format($t->denda,0,',','.') }}</div></div>
        <div><div class="text-xs text-gray-400">Total</div><div class="font-bold text-indigo-600">{{ $t->total_format }}</div></div>
        <div><div class="text-xs text-gray-400">Jatuh Tempo</div><div class="font-semibold">{{ $t->jatuh_tempo->format('d M Y') }}</div></div>
    </div>
    @if($t->pembayaran->count() > 0)
    <div class="px-5 pb-4"><div class="text-xs font-semibold text-gray-500 mb-2">Riwayat Pembayaran</div>
        @foreach($t->pembayaran as $p)
        <div class="flex items-center justify-between py-2 border-t border-gray-50 text-sm"><div><span class="font-mono text-xs text-indigo-600">{{ $p->nomor_bayar }}</span><div class="text-xs text-gray-400">{{ ucfirst(str_replace('_',' ',$p->metode)) }} · {{ $p->created_at->format('d M Y') }}</div></div>
        <div class="text-right"><div class="font-semibold">Rp {{ number_format($p->jumlah_bayar,0,',','.') }}</div><span class="text-xs px-2 py-0.5 rounded-full {{ $p->status_badge['class'] }}">{{ $p->status_badge['label'] }}</span></div></div>
        @endforeach
    </div>
    @endif
    @if($t->status !== 'lunas')
    <form action="{{ route('spp.bayar',$t) }}" method="POST" enctype="multipart/form-data" class="px-5 py-4 bg-gray-50 border-t border-gray-100 grid grid-cols-4 gap-3 items-end">@csrf
        <div><label class="block text-xs text-gray-500 mb-1">Jumlah Bayar</label><input type="number" name="jumlah_bayar" value="{{ (int)$sisa }}" min="10000" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
        <div><label class="block text-xs text-gray-500 mb-1">Metode</label><select name="metode" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"><option value="transfer">Transfer Bank</option><option value="virtual_account">Virtual Account</option><option value="cash">Tunai</option></select></div>
        <div><label class="block text-xs text-gray-500 mb-1">Bukti Bayar</label><input type="file" name="bukti_bayar" accept="image/*" required class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 bg-white"/></div>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 rounded-lg">Upload Bukti</button>
    </form>
    @endif
</div>
@empty
<div class="bg-white rounded-xl border border-gray-200 p-12 text-center"><div class="text-4xl mb-2">💳</div><div class="text-sm text-gray-400">Belum ada tagihan SPP</div></div>
@endforelse
@endsection

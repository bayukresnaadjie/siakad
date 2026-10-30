@extends('layouts.app')
@section('title','Dashboard Keuangan')
@section('content')
<div class="grid grid-cols-3 xl:grid-cols-6 gap-4 mb-5">
    @foreach([['Total Tagihan',$stats['total_tagihan'],'text-gray-900'],['Lunas',$stats['sudah_lunas'],'text-green-600'],['Belum Bayar',$stats['belum_bayar'],'text-red-500'],['Pendapatan SPP','Rp '.number_format($stats['total_pendapatan']/1000000,1).'jt','text-indigo-600'],['Beasiswa Pending',$stats['beasiswa_pending'],'text-amber-600'],['Wisuda Pending',$stats['wisuda_pending'],'text-gray-900']] as [$l,$v,$c])
    <div class="bg-white rounded-xl border border-gray-200 p-4"><div class="text-xs text-gray-400 mb-2">{{ $l }}</div><div class="text-xl font-bold {{ $c }}">{{ $v }}</div></div>
    @endforeach
</div>
<div class="grid grid-cols-3 gap-4">
    <div class="col-span-2 bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100 flex justify-between"><h2 class="text-sm font-semibold text-gray-900">⚠️ Tagihan Belum Lunas</h2><a href="{{ route('spp.index') }}" class="text-xs text-indigo-600 hover:underline">Semua</a></div>
        <div class="divide-y divide-gray-50">
            @forelse($tagihanBelumBayar as $t)
            <div class="px-5 py-3 flex items-center justify-between"><div><div class="text-sm font-medium text-gray-900">{{ $t->mahasiswa->nama }}</div><div class="text-xs text-gray-400">{{ $t->mahasiswa->nim }} · {{ $t->mahasiswa->prodi->singkatan }}</div></div>
            <div class="text-right"><div class="text-sm font-bold text-gray-900">{{ $t->total_format }}</div><span class="text-xs px-2 py-0.5 rounded-full {{ $t->status_badge['class'] }}">{{ $t->status_badge['label'] }}</span></div></div>
            @empty<div class="px-5 py-8 text-center text-sm text-gray-400">Semua tagihan lunas 🎉</div>@endforelse
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100"><h2 class="text-sm font-semibold text-gray-900">🏅 Beasiswa Dibuka</h2></div>
        <div class="divide-y divide-gray-50">
            @forelse($beasiswaBuka as $b)
            <a href="{{ route('beasiswa.show', $b) }}" class="block px-5 py-3 hover:bg-gray-50"><div class="text-sm font-medium text-gray-900">{{ $b->nama }}</div><div class="text-xs text-gray-400">{{ $b->nominal_format }} · {{ $b->kuota }} kuota</div></a>
            @empty<div class="px-5 py-8 text-center text-sm text-gray-400">Tidak ada beasiswa dibuka</div>@endforelse
        </div>
    </div>
</div>
@endsection

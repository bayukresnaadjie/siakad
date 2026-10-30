@extends('layouts.app')
@section('title','Statistik Akademik')
@section('subtitle', $semester?->nama)
@section('content')
<div class="grid grid-cols-4 gap-4 mb-5">
    @foreach([['cumlaude','Cumlaude (≥3.51)','text-green-600'],['sangat_memuaskan','Sangat Memuaskan','text-blue-600'],['memuaskan','Memuaskan','text-amber-600'],['kurang','Kurang (<2.00)','text-red-500']] as [$k,$l,$c])
    <div class="bg-white rounded-xl border border-gray-200 p-4"><div class="text-xs text-gray-400 mb-1">{{ $l }}</div><div class="text-3xl font-bold {{ $c }}">{{ $distribusiIpk[$k] }}</div><div class="text-xs text-gray-400">mahasiswa aktif</div></div>
    @endforeach
</div>
<div class="grid grid-cols-3 gap-4 mb-4">
    <div class="bg-white rounded-xl border border-gray-200 p-5"><h3 class="text-sm font-semibold text-gray-900 mb-4">🏛️ Mahasiswa per Prodi</h3>
        @php $maxP = max($perProdi->max('aktif_count'),1); @endphp
        <div class="space-y-3">@foreach($perProdi as $p)<div><div class="flex justify-between text-xs mb-1"><span class="text-gray-600">{{ $p->nama }}</span><b>{{ $p->aktif_count }}</b></div><div class="bg-gray-100 rounded-full h-2"><div class="h-2 rounded-full bg-indigo-500" style="width:{{ ($p->aktif_count/$maxP)*100 }}%"></div></div></div>@endforeach</div></div>
    <div class="bg-white rounded-xl border border-gray-200 p-5"><h3 class="text-sm font-semibold text-gray-900 mb-4">🔤 Distribusi Nilai</h3>
        @php $maxN = max($distribusiNilai->max('total'),1); @endphp
        <div class="space-y-2">@forelse($distribusiNilai as $n)<div class="flex items-center gap-2"><span class="w-7 text-xs font-bold text-gray-700">{{ $n->huruf }}</span><div class="flex-1 bg-gray-100 rounded-full h-2"><div class="h-2 rounded-full bg-purple-500" style="width:{{ ($n->total/$maxN)*100 }}%"></div></div><span class="text-xs text-gray-500 w-6 text-right">{{ $n->total }}</span></div>@empty<div class="text-sm text-gray-300 text-center py-4">Belum ada nilai</div>@endforelse</div></div>
    <div class="bg-white rounded-xl border border-gray-200 p-5"><h3 class="text-sm font-semibold text-gray-900 mb-4">💳 Status SPP Semester Ini</h3>
        @php $totSpp = max(array_sum($statsSpp),1); @endphp
        <div class="space-y-3">@foreach([['lunas','Lunas','bg-green-500'],['sebagian','Sebagian','bg-yellow-400'],['belum_bayar','Belum Bayar','bg-red-400']] as [$k,$l,$c])<div><div class="flex justify-between text-xs mb-1"><span class="text-gray-600">{{ $l }}</span><b>{{ $statsSpp[$k] }}</b></div><div class="bg-gray-100 rounded-full h-2"><div class="h-2 rounded-full {{ $c }}" style="width:{{ ($statsSpp[$k]/$totSpp)*100 }}%"></div></div></div>@endforeach</div></div>
</div>
<div class="grid grid-cols-2 gap-4">
    <div class="bg-white rounded-xl border border-gray-200"><div class="px-5 py-3 border-b border-gray-100"><h3 class="text-sm font-semibold text-gray-900">📅 Per Angkatan</h3></div>
        <table class="w-full text-sm"><thead class="bg-gray-50"><tr><th class="text-left px-5 py-2 text-xs font-semibold text-gray-400">Angkatan</th><th class="px-5 py-2 text-xs font-semibold text-gray-400">Jumlah</th><th class="px-5 py-2 text-xs font-semibold text-gray-400">Rata-rata IPK</th></tr></thead>
        <tbody class="divide-y divide-gray-50">@foreach($perAngkatan as $a)<tr><td class="px-5 py-2.5 font-medium">{{ $a->angkatan }}</td><td class="px-5 py-2.5 text-center">{{ $a->total }}</td><td class="px-5 py-2.5 text-center font-bold text-indigo-600">{{ number_format($a->rata_ipk,2) }}</td></tr>@endforeach</tbody></table></div>
    <div class="bg-white rounded-xl border border-gray-200"><div class="px-5 py-3 border-b border-gray-100"><h3 class="text-sm font-semibold text-gray-900">🏆 10 IPK Tertinggi</h3></div>
        <div class="divide-y divide-gray-50">@foreach($topMahasiswa as $i=>$m)<div class="px-5 py-2.5 flex items-center gap-3"><span class="w-6 text-sm font-bold {{ $i<3?'text-amber-500':'text-gray-300' }}">{{ $i+1 }}</span><div class="flex-1"><div class="text-sm font-medium text-gray-900">{{ $m->nama }}</div><div class="text-xs text-gray-400">{{ $m->nim }} · {{ $m->prodi->singkatan }}</div></div><span class="font-bold text-indigo-600">{{ number_format($m->ipk,2) }}</span></div>@endforeach</div></div>
</div>
@endsection

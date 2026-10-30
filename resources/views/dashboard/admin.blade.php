@extends('layouts.app')
@section('title','Dashboard')
@section('subtitle','Ringkasan akademik universitas')
@section('content')
<div class="grid grid-cols-3 xl:grid-cols-6 gap-4 mb-5">
    @foreach([['Mahasiswa Aktif',$stats['total_mahasiswa'],'🧑‍🎓','text-gray-900'],['Dosen',$stats['total_dosen'],'👨‍🏫','text-gray-900'],['Fakultas',$stats['total_fakultas'],'🏛️','text-gray-900'],['Kelas Semester Ini',$stats['total_kelas'],'📚','text-indigo-600'],['Total KRS',$stats['total_krs'],'📝','text-gray-900'],['KRS Pending',$stats['krs_pending'],'⏳',$stats['krs_pending']>0?'text-amber-600':'text-gray-300']] as [$l,$v,$i,$c])
    <div class="bg-white rounded-xl border border-gray-200 p-4"><div class="flex justify-between mb-2"><span class="text-xs text-gray-400">{{ $l }}</span><span>{{ $i }}</span></div><div class="text-2xl font-bold {{ $c }}">{{ $v }}</div></div>
    @endforeach
</div>
<div class="grid grid-cols-2 gap-4">
    @include('partials.krs-menunggu')
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100 flex justify-between"><h2 class="text-sm font-semibold text-gray-900">🆕 Mahasiswa Terbaru</h2><a href="{{ route('master.mahasiswa') }}" class="text-xs text-indigo-600 hover:underline">Semua</a></div>
        <div class="divide-y divide-gray-50">
            @foreach($mahasiswaBaru as $m)
            <a href="{{ route('master.mahasiswa.show', $m) }}" class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50"><img src="{{ $m->avatar_url }}" class="w-8 h-8 rounded-full"/><div class="flex-1"><div class="text-sm font-medium text-gray-900">{{ $m->nama }}</div><div class="text-xs text-gray-400">{{ $m->nim }} · {{ $m->prodi->nama }}</div></div><span class="text-xs px-2 py-0.5 rounded-full {{ $m->status_badge['class'] }}">{{ $m->status_badge['label'] }}</span></a>
            @endforeach
        </div>
    </div>
</div>
@endsection

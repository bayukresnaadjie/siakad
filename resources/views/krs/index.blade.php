@extends('layouts.app')
@section('title','KRS Online')
@section('subtitle', $semester ? 'Periode KRS: '.$semester->tanggal_krs_mulai?->format('d M').' – '.$semester->tanggal_krs_selesai?->format('d M Y') : 'Tidak ada semester aktif')
@php $draft = $krsAktif->where('status','draft')->count(); $open = $semester?->isKrsOpen(); @endphp
@section('header-action')
@if($draft > 0)
<form action="{{ route('krs.ajukan') }}" method="POST" onsubmit="return confirm('Ajukan {{ $draft }} mata kuliah ke dosen wali?')">@csrf
    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">📨 Ajukan KRS ({{ $draft }})</button>
</form>
@endif
@endsection
@section('content')
<div class="grid grid-cols-4 gap-4 mb-5">
    <div class="bg-white rounded-xl border border-gray-200 p-4"><div class="text-xs text-gray-400 mb-1">SKS Disetujui</div><div class="text-2xl font-bold text-indigo-600">{{ $totalSks }} <span class="text-sm font-normal text-gray-400">/ {{ $mhs->prodi->maks_sks }}</span></div></div>
    <div class="bg-white rounded-xl border border-gray-200 p-4"><div class="text-xs text-gray-400 mb-1">Dosen Wali</div><div class="text-sm font-semibold text-gray-900 mt-1">{{ $mhs->dosenWali?->nama_lengkap ?? '-' }}</div></div>
    <div class="bg-white rounded-xl border border-gray-200 p-4"><div class="text-xs text-gray-400 mb-1">Status Periode KRS</div><span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $open ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $open ? '● Dibuka' : '● Ditutup' }}</span></div>
    <div class="bg-white rounded-xl border border-gray-200 p-4"><div class="text-xs text-gray-400 mb-1">IPK</div><div class="text-2xl font-bold text-gray-900">{{ number_format($mhs->ipk,2) }}</div></div>
</div>

<div class="bg-white rounded-xl border border-gray-200 mb-5">
    <div class="px-5 py-3 border-b border-gray-100"><h2 class="text-sm font-semibold text-gray-900">📝 KRS Saya</h2></div>
    <table class="w-full text-sm"><thead class="bg-gray-50"><tr>
        <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Mata Kuliah</th><th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-400">SKS</th>
        <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Dosen</th><th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Jadwal</th>
        <th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-400">Status</th><th class="text-right px-5 py-2.5 text-xs font-semibold text-gray-400">Aksi</th></tr></thead>
    <tbody class="divide-y divide-gray-50">
        @forelse($krsAktif as $k)
        <tr class="hover:bg-gray-50">
            <td class="px-5 py-3"><div class="font-medium text-gray-900">{{ $k->kelas->mataKuliah->nama }}</div><div class="text-xs text-gray-400">{{ $k->kelas->mataKuliah->kode }} · Kelas {{ $k->kelas->nama_kelas }}</div>
            @if($k->status==='ditolak' && $k->catatan)<div class="text-xs text-red-500 mt-0.5">Catatan: {{ $k->catatan }}</div>@endif</td>
            <td class="px-5 py-3 text-center font-bold">{{ $k->kelas->mataKuliah->sks }}</td>
            <td class="px-5 py-3 text-xs text-gray-600">{{ $k->kelas->dosen->nama_lengkap }}</td>
            <td class="px-5 py-3 text-xs text-gray-500">@foreach($k->kelas->jadwal as $j){{ $j->hari_label }} {{ substr($j->jam_mulai,0,5) }}-{{ substr($j->jam_selesai,0,5) }}<br>@endforeach</td>
            <td class="px-5 py-3 text-center"><span class="text-xs px-2 py-0.5 rounded-full {{ $k->status_badge['class'] }}">{{ $k->status_badge['label'] }}</span></td>
            <td class="px-5 py-3 text-right">@if(in_array($k->status,['draft','ditolak']))
                <form action="{{ route('krs.destroy',$k) }}" method="POST" onsubmit="return confirm('Hapus dari KRS?')">@csrf @method('DELETE')<button class="text-xs text-red-600 bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-lg">Hapus</button></form>@endif</td>
        </tr>
        @empty<tr><td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">Belum ada mata kuliah di KRS</td></tr>@endforelse
    </tbody></table>
</div>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="px-5 py-3 border-b border-gray-100"><h2 class="text-sm font-semibold text-gray-900">📚 Kelas Tersedia ({{ $kelasTersedia->count() }})</h2></div>
    <table class="w-full text-sm"><thead class="bg-gray-50"><tr>
        <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Mata Kuliah</th><th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-400">SKS</th>
        <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Dosen</th><th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Jadwal</th>
        <th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-400">Kuota</th><th class="text-right px-5 py-2.5 text-xs font-semibold text-gray-400">Aksi</th></tr></thead>
    <tbody class="divide-y divide-gray-50">
        @forelse($kelasTersedia as $k)
        @php $penuh = $k->terisi >= $k->kapasitas; @endphp
        <tr class="hover:bg-gray-50">
            <td class="px-5 py-3"><div class="font-medium text-gray-900">{{ $k->mataKuliah->nama }}</div><div class="text-xs text-gray-400">{{ $k->mataKuliah->kode }} · Sem {{ $k->mataKuliah->semester }} · Kelas {{ $k->nama_kelas }}</div></td>
            <td class="px-5 py-3 text-center font-bold">{{ $k->mataKuliah->sks }}</td>
            <td class="px-5 py-3 text-xs text-gray-600">{{ $k->dosen->nama_lengkap }}</td>
            <td class="px-5 py-3 text-xs text-gray-500">@foreach($k->jadwal as $j){{ $j->hari_label }} {{ substr($j->jam_mulai,0,5) }}-{{ substr($j->jam_selesai,0,5) }}<br>@endforeach</td>
            <td class="px-5 py-3 text-center text-xs {{ $penuh?'text-red-500 font-bold':'text-gray-600' }}">{{ $k->terisi }}/{{ $k->kapasitas }}</td>
            <td class="px-5 py-3 text-right"><form action="{{ route('krs.store') }}" method="POST">@csrf<input type="hidden" name="kelas_id" value="{{ $k->id }}"/>
                <button class="text-xs px-3 py-1 rounded-lg {{ (!$open||$penuh)?'bg-gray-100 text-gray-300 cursor-not-allowed':'bg-indigo-600 text-white hover:bg-indigo-700' }}" {{ (!$open||$penuh)?'disabled':'' }}>+ Ambil</button></form></td>
        </tr>
        @empty<tr><td colspan="6" class="px-5 py-8 text-center text-sm text-gray-400">Tidak ada kelas tersedia</td></tr>@endforelse
    </tbody></table>
</div>
@endsection

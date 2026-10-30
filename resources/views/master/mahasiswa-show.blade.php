@extends('layouts.app')
@section('title','Detail Mahasiswa')
@section('subtitle', $mahasiswa->nim)
@section('header-action')<div class="flex gap-2"><a href="{{ route('transkrip.mahasiswa',$mahasiswa) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg">🎓 Transkrip</a><a href="{{ route('master.mahasiswa') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-lg">← Kembali</a></div>@endsection
@section('content')
<div class="max-w-4xl space-y-4">
<div class="bg-white rounded-xl border border-gray-200 p-6 flex gap-5">
    <img src="{{ $mahasiswa->avatar_url }}" class="w-20 h-20 rounded-xl"/>
    <div class="flex-1"><div class="flex justify-between"><div><h2 class="text-xl font-bold text-gray-900">{{ $mahasiswa->nama }}</h2><div class="text-sm text-gray-500">{{ $mahasiswa->prodi->nama }} · {{ $mahasiswa->prodi->fakultas->nama }}</div></div>
        <span class="text-xs px-2.5 py-1 rounded-full h-fit {{ $mahasiswa->status_badge['class'] }}">{{ $mahasiswa->status_badge['label'] }}</span></div>
    <div class="grid grid-cols-4 gap-3 mt-4">
        <div class="bg-indigo-50 rounded-lg p-3 text-center"><div class="text-xs text-indigo-400">IPK</div><div class="text-xl font-bold text-indigo-700">{{ number_format($mahasiswa->ipk,2) }}</div></div>
        <div class="bg-gray-50 rounded-lg p-3 text-center"><div class="text-xs text-gray-400">SKS Lulus</div><div class="text-xl font-bold">{{ $mahasiswa->total_sks_lulus }}</div></div>
        <div class="bg-gray-50 rounded-lg p-3 text-center"><div class="text-xs text-gray-400">Semester</div><div class="text-xl font-bold">{{ $mahasiswa->semester_aktif }}</div></div>
        <div class="bg-gray-50 rounded-lg p-3 text-center"><div class="text-xs text-gray-400">Angkatan</div><div class="text-xl font-bold">{{ $mahasiswa->angkatan }}</div></div>
    </div></div>
</div>
<div class="bg-white rounded-xl border border-gray-200 p-6 grid grid-cols-2 gap-3 text-sm">
    <div><span class="text-gray-400">Jenis Kelamin:</span> {{ $mahasiswa->jenis_kelamin==='L'?'Laki-laki':'Perempuan' }}</div>
    <div><span class="text-gray-400">TTL:</span> {{ $mahasiswa->tempat_lahir }}, {{ $mahasiswa->tanggal_lahir?->format('d M Y') }}</div>
    <div><span class="text-gray-400">No. HP:</span> {{ $mahasiswa->nomor_hp ?? '-' }}</div>
    <div><span class="text-gray-400">Email Kampus:</span> {{ $mahasiswa->email_kampus ?? '-' }}</div>
    <div><span class="text-gray-400">Dosen Wali:</span> {{ $mahasiswa->dosenWali?->nama_lengkap ?? '-' }}</div>
    <div><span class="text-gray-400">Alamat:</span> {{ $mahasiswa->alamat ?? '-' }}</div>
</div>
<div class="bg-white rounded-xl border border-gray-200"><div class="px-5 py-3 border-b border-gray-100"><h3 class="text-sm font-semibold text-gray-900">📊 Riwayat KHS</h3></div>
<table class="w-full text-sm"><thead class="bg-gray-50"><tr><th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Semester</th><th class="px-5 py-2.5 text-xs font-semibold text-gray-400">SKS Ambil</th><th class="px-5 py-2.5 text-xs font-semibold text-gray-400">SKS Lulus</th><th class="px-5 py-2.5 text-xs font-semibold text-gray-400">IP</th><th class="px-5 py-2.5 text-xs font-semibold text-gray-400">IPK</th></tr></thead>
<tbody class="divide-y divide-gray-50">@forelse($mahasiswa->khs as $k)<tr><td class="px-5 py-2.5">{{ $k->semester->nama }}</td><td class="px-5 py-2.5 text-center">{{ $k->total_sks_ambil }}</td><td class="px-5 py-2.5 text-center">{{ $k->total_sks_lulus }}</td><td class="px-5 py-2.5 text-center font-bold text-indigo-600">{{ number_format($k->ip_semester,2) }}</td><td class="px-5 py-2.5 text-center">{{ number_format($k->ipk,2) }}</td></tr>
@empty<tr><td colspan="5" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada KHS</td></tr>@endforelse</tbody></table></div>
</div>
@endsection

@extends('layouts.app')
@section('title',$beasiswa->nama)
@section('subtitle', $beasiswa->nominal_format . ' · kuota ' . $beasiswa->kuota . ' · min. IPK ' . number_format($beasiswa->min_ipk,2))
@section('header-action')<a href="{{ route('beasiswa.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-lg">← Kembali</a>@endsection
@section('content')
@php $canProses = in_array(auth()->user()->role,['keuangan','admin']); @endphp
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h2 class="text-sm font-semibold text-gray-900">Pendaftar ({{ $pendaftaran->count() }})</h2></div>
    <table class="w-full text-sm"><thead class="bg-gray-50"><tr>
        <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Mahasiswa</th><th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-400">IPK</th>
        <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Motivasi</th><th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-400">Status</th>
        @if($canProses)<th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Proses</th>@endif</tr></thead>
    <tbody class="divide-y divide-gray-50">
        @forelse($pendaftaran as $p)
        <tr class="align-top"><td class="px-5 py-3"><div class="font-medium text-gray-900">{{ $p->mahasiswa->nama }}</div><div class="text-xs text-gray-400">{{ $p->mahasiswa->nim }} · {{ $p->mahasiswa->prodi->singkatan }}</div></td>
        <td class="px-5 py-3 text-center font-bold {{ $p->mahasiswa->ipk >= $beasiswa->min_ipk ? 'text-green-600':'text-red-500' }}">{{ number_format($p->mahasiswa->ipk,2) }}</td>
        <td class="px-5 py-3 text-xs text-gray-500 max-w-xs">{{ \Illuminate\Support\Str::limit($p->motivasi,120) }}</td>
        <td class="px-5 py-3 text-center"><span class="text-xs px-2 py-0.5 rounded-full {{ $p->status_badge['class'] }}">{{ $p->status_badge['label'] }}</span></td>
        @if($canProses)<td class="px-5 py-3"><form action="{{ route('beasiswa.proses',$p) }}" method="POST" class="flex gap-1">@csrf @method('PATCH')
            <select name="aksi" class="border border-gray-200 rounded-lg px-2 py-1 text-xs"><option value="lolos_berkas">Lolos Berkas</option><option value="tidak_lolos">Tidak Lolos</option><option value="diterima">Diterima</option><option value="ditolak">Ditolak</option></select>
            <input name="catatan" placeholder="Catatan" class="border border-gray-200 rounded-lg px-2 py-1 text-xs w-28"/><button class="bg-indigo-600 text-white text-xs px-2.5 py-1 rounded-lg">OK</button></form></td>@endif</tr>
        @empty<tr><td colspan="5" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada pendaftar</td></tr>@endforelse
    </tbody></table>
</div>
@endsection

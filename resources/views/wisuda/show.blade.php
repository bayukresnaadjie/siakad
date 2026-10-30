@extends('layouts.app')
@section('title',$wisuda->nama)
@section('subtitle', $wisuda->tanggal->format('d F Y') . ' · ' . $wisuda->lokasi)
@section('header-action')<a href="{{ route('wisuda.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-lg">← Kembali</a>@endsection
@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h2 class="text-sm font-semibold text-gray-900">Pendaftar ({{ $pendaftaran->count() }})</h2></div>
    <table class="w-full text-sm"><thead class="bg-gray-50"><tr>
        <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Mahasiswa</th><th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Skripsi</th>
        <th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-400">IPK / SKS</th><th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-400">Predikat</th>
        <th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-400">Status</th><th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-400">Verifikasi</th></tr></thead>
    <tbody class="divide-y divide-gray-50">
        @forelse($pendaftaran as $p)
        <tr class="align-top"><td class="px-5 py-3"><div class="font-medium text-gray-900">{{ $p->mahasiswa->nama }}</div><div class="text-xs text-gray-400">{{ $p->mahasiswa->nim }} · {{ $p->mahasiswa->prodi->singkatan }}</div></td>
        <td class="px-5 py-3 text-xs text-gray-600 max-w-xs"><div>{{ $p->judul_skripsi }}</div><div class="text-gray-400 mt-0.5">{{ $p->pembimbing_skripsi }}</div></td>
        <td class="px-5 py-3 text-center"><div class="font-bold text-indigo-600">{{ number_format($p->ipk_lulus,2) }}</div><div class="text-xs text-gray-400">{{ $p->total_sks_lulus }} SKS</div></td>
        <td class="px-5 py-3 text-center text-xs font-medium">{{ $p->predikat_label }}</td>
        <td class="px-5 py-3 text-center"><span class="text-xs px-2 py-0.5 rounded-full {{ $p->status_badge['class'] }}">{{ $p->status_badge['label'] }}</span></td>
        <td class="px-5 py-3">@if($p->status==='pending')<form action="{{ route('wisuda.verifikasi',$p) }}" method="POST" class="flex gap-1">@csrf @method('PATCH')
            <input name="catatan" placeholder="Catatan" class="border border-gray-200 rounded-lg px-2 py-1 text-xs w-24"/>
            <button name="aksi" value="diverifikasi" class="bg-green-600 text-white text-xs px-2 py-1 rounded-lg">✓</button><button name="aksi" value="ditolak" class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-lg">✕</button></form>@else<span class="text-xs text-gray-300">—</span>@endif</td></tr>
        @empty<tr><td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">Belum ada pendaftar</td></tr>@endforelse
    </tbody></table>
</div>
@endsection

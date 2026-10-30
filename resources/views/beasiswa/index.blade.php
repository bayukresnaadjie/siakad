@extends('layouts.app')
@section('title','Beasiswa')
@section('subtitle', $beasiswa->total() . ' program beasiswa')
@section('content')
@if(in_array(auth()->user()->role,['keuangan','admin']))
<details class="bg-white rounded-xl border border-gray-200 mb-5"><summary class="px-5 py-3 cursor-pointer text-sm font-semibold text-indigo-700">➕ Buat Beasiswa Baru</summary>
<form action="{{ route('beasiswa.store') }}" method="POST" class="p-5 border-t border-gray-100 grid grid-cols-3 gap-4">@csrf
    <div class="col-span-2"><label class="block text-xs text-gray-500 mb-1">Nama</label><input name="nama" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div><label class="block text-xs text-gray-500 mb-1">Penyelenggara</label><input name="penyelenggara" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div class="col-span-3"><label class="block text-xs text-gray-500 mb-1">Deskripsi</label><textarea name="deskripsi" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></textarea></div>
    <div><label class="block text-xs text-gray-500 mb-1">Nominal (Rp)</label><input type="number" name="nominal" required min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div><label class="block text-xs text-gray-500 mb-1">Kuota</label><input type="number" name="kuota" value="10" required min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div><label class="block text-xs text-gray-500 mb-1">Min. IPK</label><input type="number" step="0.01" name="min_ipk" value="3.00" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div><label class="block text-xs text-gray-500 mb-1">Maks. Semester</label><input type="number" name="maks_semester" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div><label class="block text-xs text-gray-500 mb-1">Tanggal Buka</label><input type="date" name="tanggal_buka" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div><label class="block text-xs text-gray-500 mb-1">Tanggal Tutup</label><input type="date" name="tanggal_tutup" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
    <div class="col-span-3"><button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg">Simpan</button></div>
</form></details>
@endif
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden"><table class="w-full text-sm"><thead class="bg-gray-50 border-b border-gray-200"><tr>
    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Beasiswa</th><th class="text-right px-5 py-3 text-xs font-semibold text-gray-400">Nominal</th>
    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">Kuota</th><th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">Pendaftar</th>
    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Periode</th><th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">Status</th><th class="px-5 py-3"></th></tr></thead>
<tbody class="divide-y divide-gray-100">
    @forelse($beasiswa as $b)
    <tr class="hover:bg-gray-50"><td class="px-5 py-3"><div class="font-medium text-gray-900">{{ $b->nama }}</div><div class="text-xs text-gray-400">{{ $b->penyelenggara }}</div></td>
    <td class="px-5 py-3 text-right font-semibold">{{ $b->nominal_format }}</td><td class="px-5 py-3 text-center">{{ $b->kuota }}</td>
    <td class="px-5 py-3 text-center font-bold text-indigo-600">{{ $b->pendaftaran_count }}</td>
    <td class="px-5 py-3 text-xs text-gray-500">{{ $b->tanggal_buka->format('d M') }} – {{ $b->tanggal_tutup->format('d M Y') }}</td>
    <td class="px-5 py-3 text-center"><span class="text-xs px-2 py-0.5 rounded-full {{ $b->status_badge['class'] }}">{{ $b->status_badge['label'] }}</span></td>
    <td class="px-5 py-3 text-right"><a href="{{ route('beasiswa.show',$b) }}" class="text-xs text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">Detail</a></td></tr>
    @empty<tr><td colspan="7" class="px-5 py-10 text-center text-sm text-gray-400">Belum ada beasiswa</td></tr>@endforelse
</tbody></table>@if($beasiswa->hasPages())<div class="px-5 py-3 border-t">{{ $beasiswa->links() }}</div>@endif</div>
@endsection

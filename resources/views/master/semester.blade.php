@extends('layouts.app')
@section('title','Semester Akademik')
@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden"><table class="w-full text-sm"><thead class="bg-gray-50 border-b border-gray-200"><tr>
    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Semester</th><th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Perkuliahan</th><th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Periode KRS</th>
    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">UTS / UAS</th><th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">Status</th><th class="px-5 py-3"></th></tr></thead>
<tbody class="divide-y divide-gray-100">
    @foreach($semester as $s)
    <tr class="hover:bg-gray-50 {{ $s->is_aktif?'bg-indigo-50/40':'' }}"><td class="px-5 py-3"><div class="font-medium text-gray-900">{{ $s->nama }}</div><div class="text-xs font-mono text-gray-400">{{ $s->kode }} · {{ $s->tipe_label }}</div></td>
    <td class="px-5 py-3 text-xs text-gray-600">{{ $s->tanggal_mulai->format('d M Y') }} – {{ $s->tanggal_selesai->format('d M Y') }}</td>
    <td class="px-5 py-3 text-xs text-gray-600">{{ $s->tanggal_krs_mulai?->format('d M') }} – {{ $s->tanggal_krs_selesai?->format('d M Y') }}</td>
    <td class="px-5 py-3 text-xs text-gray-600">{{ $s->tanggal_uts?->format('d M') ?? '-' }} / {{ $s->tanggal_uas?->format('d M') ?? '-' }}</td>
    <td class="px-5 py-3 text-center"><span class="text-xs px-2.5 py-1 rounded-full {{ $s->is_aktif?'bg-green-100 text-green-700':'bg-gray-100 text-gray-500' }}">{{ $s->is_aktif?'● Aktif':'Nonaktif' }}</span></td>
    <td class="px-5 py-3 text-right">@unless($s->is_aktif)<form action="{{ route('master.semester.aktifkan',$s) }}" method="POST" onsubmit="return confirm('Aktifkan semester ini?')">@csrf @method('PATCH')<button class="text-xs text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded-lg">Aktifkan</button></form>@endunless</td></tr>
    @endforeach
</tbody></table></div>
@endsection

<div class="grid grid-cols-3 gap-4">
@foreach($hari as $h)
@php $items = ($jadwal[$h] ?? collect())->sortBy('jam_mulai'); @endphp
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-4 py-2.5 bg-indigo-50 border-b border-indigo-100 flex justify-between"><span class="text-sm font-semibold text-indigo-800">{{ ucfirst($h) }}</span><span class="text-xs text-indigo-400">{{ $items->count() }} kelas</span></div>
    <div class="divide-y divide-gray-50">
        @forelse($items as $j)
        <div class="px-4 py-3">
            <div class="text-xs font-bold text-indigo-600 mb-0.5">{{ substr($j->jam_mulai,0,5) }} – {{ substr($j->jam_selesai,0,5) }}</div>
            <div class="text-sm font-medium text-gray-900">{{ $j->kelas->mataKuliah->nama }}</div>
            <div class="text-xs text-gray-400">Kelas {{ $j->kelas->nama_kelas }} · 📍 {{ $j->ruangan?->nama ?? '-' }}</div>
            @if($mode === 'mahasiswa')<div class="text-xs text-gray-500 mt-0.5">👤 {{ $j->kelas->dosen->nama_lengkap }}</div>@endif
        </div>
        @empty<div class="px-4 py-6 text-center text-xs text-gray-300">Tidak ada jadwal</div>@endforelse
    </div>
</div>
@endforeach
</div>

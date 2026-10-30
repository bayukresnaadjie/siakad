<div class="bg-white rounded-xl border border-gray-200 shadow-sm">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-900">📨 KRS Menunggu Persetujuan</h2>
        <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">{{ $krsMenunggu->count() }}</span>
    </div>
    <div class="divide-y divide-gray-50">
        @forelse($krsMenunggu as $k)
        <div class="px-5 py-3 flex items-center justify-between gap-3">
            <div class="min-w-0">
                <div class="text-sm font-medium text-gray-900 truncate">{{ $k->mahasiswa->nama }} <span class="text-xs font-mono text-gray-400">{{ $k->mahasiswa->nim }}</span></div>
                <div class="text-xs text-gray-500 truncate">{{ $k->kelas->mataKuliah->nama }} · {{ $k->kelas->mataKuliah->sks }} SKS</div>
            </div>
            <form action="{{ route('krs.approve', $k) }}" method="POST" class="flex gap-1 flex-shrink-0">@csrf @method('PATCH')
                <button name="aksi" value="setujui" class="text-xs bg-green-600 text-white px-2.5 py-1 rounded-lg hover:bg-green-700">✓ Setujui</button>
                <button name="aksi" value="tolak" class="text-xs bg-red-100 text-red-600 px-2.5 py-1 rounded-lg hover:bg-red-200">✕</button>
            </form>
        </div>
        @empty
        <div class="px-5 py-8 text-center text-sm text-gray-400">Tidak ada KRS yang menunggu 🎉</div>
        @endforelse
    </div>
</div>

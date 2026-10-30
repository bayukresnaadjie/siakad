@extends('layouts.app')
@section('title','Input Nilai')
@section('subtitle', $kelas->mataKuliah->nama.' · Kelas '.$kelas->nama_kelas.' · '.$kelas->semester->nama)
@section('header-action')
<div class="flex gap-2">
    <a href="{{ route('absensi.index',$kelas) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg">Absensi</a>
    @if(in_array(auth()->user()->role,['akademik','admin']))
    <form action="{{ route('nilai.kunci',$kelas) }}" method="POST" onsubmit="return confirm('Kunci nilai dan generate KHS? Nilai tidak bisa diubah lagi.')">@csrf @method('PATCH')
        <button class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded-lg">🔒 Kunci Nilai & Generate KHS</button></form>
    @endif
</div>
@endsection
@section('content')
<div class="bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-3 mb-4 text-xs text-indigo-700">Bobot nilai: <b>Tugas 20%</b> · <b>Kuis 10%</b> · <b>UTS 30%</b> · <b>UAS 40%</b>. Nilai akhir dihitung otomatis saat keempat komponen terisi. Lulus jika nilai akhir ≥ 55 (C).</div>
<form action="{{ route('nilai.update',$kelas) }}" method="POST">@csrf @method('PUT')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm"><thead class="bg-gray-50 border-b border-gray-200"><tr>
        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400">#</th><th class="text-left px-4 py-3 text-xs font-semibold text-gray-400">Mahasiswa</th>
        <th class="px-2 py-3 text-xs font-semibold text-gray-400">Tugas</th><th class="px-2 py-3 text-xs font-semibold text-gray-400">Kuis</th>
        <th class="px-2 py-3 text-xs font-semibold text-gray-400">UTS</th><th class="px-2 py-3 text-xs font-semibold text-gray-400">UAS</th>
        <th class="px-4 py-3 text-xs font-semibold text-gray-400">Akhir</th><th class="px-4 py-3 text-xs font-semibold text-gray-400">Huruf</th></tr></thead>
    <tbody class="divide-y divide-gray-50">
        @forelse($mahasiswaList as $i=>$krs)
        @php $n = $krs->nilai; $lock = $n?->dikunci; @endphp
        <tr class="nilai-row hover:bg-gray-50 {{ $lock?'bg-gray-50':'' }}">
            <td class="px-4 py-2 text-xs text-gray-400">{{ $i+1 }}</td>
            <td class="px-4 py-2"><div class="font-medium text-gray-900">{{ $krs->mahasiswa->nama }}</div><div class="text-xs font-mono text-gray-400">{{ $krs->mahasiswa->nim }}</div>
                <input type="hidden" name="nilai[{{ $i }}][krs_id]" value="{{ $krs->id }}"/></td>
            @foreach([['nilai_tugas',0.20],['nilai_kuis',0.10],['nilai_uts',0.30],['nilai_uas',0.40]] as [$f,$w])
            <td class="px-2 py-2"><input type="number" step="0.01" min="0" max="100" name="nilai[{{ $i }}][{{ $f }}]" value="{{ $n?->$f }}" data-w="{{ $w }}" {{ $lock?'readonly':'' }}
                class="ni w-20 border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-center focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $lock?'bg-gray-100':'' }}"/></td>
            @endforeach
            <td class="px-4 py-2 text-center font-bold text-gray-900 akhir">{{ $n?->nilai_akhir ?? '—' }}</td>
            <td class="px-4 py-2 text-center huruf">@if($n?->huruf)<span class="text-xs font-bold px-2.5 py-1 rounded-lg {{ $n->huruf_badge['class'] }}">{{ $n->huruf }}</span>@else<span class="text-gray-300">—</span>@endif @if($lock)<span title="Terkunci">🔒</span>@endif</td>
        </tr>
        @empty<tr><td colspan="8" class="px-5 py-10 text-center text-sm text-gray-400">Belum ada mahasiswa dengan KRS disetujui</td></tr>@endforelse
    </tbody></table>
</div>
<div class="mt-4 flex gap-3"><a href="{{ url()->previous() }}" class="bg-gray-100 text-gray-700 text-sm font-medium px-5 py-2.5 rounded-lg">Kembali</a>
<button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg">💾 Simpan Semua Nilai</button></div>
</form>
<script>
function huruf(n){return n>=85?'A':n>=80?'A-':n>=75?'B+':n>=70?'B':n>=65?'B-':n>=60?'C+':n>=55?'C':n>=50?'C-':n>=40?'D':'E';}
document.querySelectorAll('.nilai-row').forEach(tr=>{
    const upd=()=>{const ins=[...tr.querySelectorAll('.ni')];
        if(ins.some(i=>i.value===''))return;
        const a=ins.reduce((s,i)=>s+parseFloat(i.value)*parseFloat(i.dataset.w),0);
        tr.querySelector('.akhir').textContent=a.toFixed(2);
        tr.querySelector('.huruf').innerHTML='<span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-indigo-100 text-indigo-700">'+huruf(a)+'</span>';};
    tr.querySelectorAll('.ni').forEach(i=>i.addEventListener('input',upd));
});
</script>
@endsection

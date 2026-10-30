@extends('layouts.app')
@section('title','Jadwal Kelas')
@section('subtitle', ($semester?->nama ?? '-') . ' · ' . $kelas->total() . ' kelas')
@section('content')
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-5">
    <h2 class="text-sm font-semibold text-gray-900 mb-3">➕ Tambah Jadwal</h2>
    <form action="{{ route('jadwal.store') }}" method="POST" class="grid grid-cols-6 gap-3 items-end" id="form-jadwal">@csrf
        <div class="col-span-2"><label class="block text-xs text-gray-500 mb-1">Kelas</label>
            <select name="kelas_id" id="f-kelas" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                @foreach($kelas as $k)<option value="{{ $k->id }}">{{ $k->mataKuliah->nama }} — {{ $k->nama_kelas }}</option>@endforeach</select></div>
        <div><label class="block text-xs text-gray-500 mb-1">Ruangan</label>
            <select name="ruangan_id" id="f-ruangan" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">@foreach($ruangan as $r)<option value="{{ $r->id }}">{{ $r->kode }}</option>@endforeach</select></div>
        <div><label class="block text-xs text-gray-500 mb-1">Hari</label>
            <select name="hari" id="f-hari" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">@foreach($hari as $h)<option value="{{ $h }}">{{ ucfirst($h) }}</option>@endforeach</select></div>
        <div><label class="block text-xs text-gray-500 mb-1">Mulai</label><input type="time" name="jam_mulai" id="f-mulai" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
        <div><label class="block text-xs text-gray-500 mb-1">Selesai</label><input type="time" name="jam_selesai" id="f-selesai" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"/></div>
        <div class="col-span-6 flex items-center gap-3">
            <button type="button" onclick="cekBentrok()" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">Cek Bentrok</button>
            <button type="submit" class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">Simpan Jadwal</button>
            <span id="bentrok-info" class="text-xs"></span>
        </div>
    </form>
</div>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm"><thead class="bg-gray-50 border-b border-gray-200"><tr>
        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Mata Kuliah</th><th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Dosen</th>
        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400">Jadwal</th><th class="text-center px-5 py-3 text-xs font-semibold text-gray-400">Aksi</th></tr></thead>
    <tbody class="divide-y divide-gray-100">
        @foreach($kelas as $k)
        <tr class="hover:bg-gray-50">
            <td class="px-5 py-3"><div class="font-medium text-gray-900">{{ $k->mataKuliah->nama }}</div><div class="text-xs text-gray-400">{{ $k->mataKuliah->kode }} · Kelas {{ $k->nama_kelas }} · {{ $k->mataKuliah->sks }} SKS</div></td>
            <td class="px-5 py-3 text-xs text-gray-600">{{ $k->dosen->nama_lengkap }}</td>
            <td class="px-5 py-3 text-xs text-gray-600">@forelse($k->jadwal as $j){{ $j->hari_label }}, {{ substr($j->jam_mulai,0,5) }}-{{ substr($j->jam_selesai,0,5) }} · {{ $j->ruangan?->kode }}<br>@empty<span class="text-amber-500">Belum dijadwalkan</span>@endforelse</td>
            <td class="px-5 py-3 text-center"><div class="flex justify-center gap-1">
                <a href="{{ route('absensi.index',$k) }}" class="text-xs text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">Absensi</a>
                <a href="{{ route('nilai.kelas',$k) }}" class="text-xs text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">Nilai</a></div></td>
        </tr>
        @endforeach
    </tbody></table>
    @if($kelas->hasPages())<div class="px-5 py-3 border-t">{{ $kelas->links() }}</div>@endif
</div>
<script>
async function cekBentrok(){
    const f = id => document.getElementById(id).value;
    const info = document.getElementById('bentrok-info');
    const res = await fetch('{{ route("jadwal.cek-bentrok") }}',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
        body:JSON.stringify({ruangan_id:f('f-ruangan'),hari:f('f-hari'),jam_mulai:f('f-mulai'),jam_selesai:f('f-selesai'),kelas_id:f('f-kelas')})});
    const d = await res.json();
    info.textContent = (d.bentrok ? '⚠️ ' : '✓ ') + d.pesan;
    info.className = 'text-xs ' + (d.bentrok ? 'text-red-600' : 'text-green-600');
}
</script>
@endsection

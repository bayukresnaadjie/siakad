<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>SIAKAD — @yield('title','Dashboard')</title>
<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>@media print{aside,header,.no-print{display:none!important}main{margin-left:0!important}body{background:#fff!important}}</style>
</head>
<body class="bg-slate-50 font-sans antialiased">
@php
$u = auth()->user(); $role = $u->role; $sem = \App\Models\Semester::aktif();
$menu = [['route'=>'dashboard','label'=>'Dashboard','icon'=>'🏠']];
if ($role === 'mahasiswa') {
    $menu = array_merge($menu, [
        ['divider'=>'AKADEMIK'],
        ['route'=>'krs.index','label'=>'KRS Online','icon'=>'📝'],
        ['route'=>'jadwal.index','label'=>'Jadwal Kuliah','icon'=>'📅'],
        ['route'=>'nilai.mahasiswa','label'=>'Nilai & KHS','icon'=>'📊'],
        ['route'=>'transkrip.show','label'=>'Transkrip','icon'=>'🎓'],
        ['divider'=>'LAYANAN'],
        ['route'=>'spp.index','label'=>'Pembayaran SPP','icon'=>'💳'],
        ['route'=>'beasiswa.index','label'=>'Beasiswa','icon'=>'🏅'],
        ['route'=>'wisuda.index','label'=>'Wisuda','icon'=>'🎉'],
    ]);
} elseif ($role === 'dosen') {
    $menu[] = ['route'=>'jadwal.index','label'=>'Jadwal Mengajar','icon'=>'📅'];
} elseif ($role === 'keuangan') {
    $menu = array_merge($menu, [
        ['divider'=>'KEUANGAN'],
        ['route'=>'spp.index','label'=>'Tagihan SPP','icon'=>'💳'],
        ['route'=>'beasiswa.index','label'=>'Beasiswa','icon'=>'🏅'],
        ['route'=>'laporan.index','label'=>'Laporan','icon'=>'📈'],
    ]);
} else {
    $menu = array_merge($menu, [
        ['divider'=>'AKADEMIK'],
        ['route'=>'jadwal.index','label'=>'Jadwal Kelas','icon'=>'📅'],
        ['route'=>'wisuda.index','label'=>'Wisuda','icon'=>'🎉'],
        ['route'=>'beasiswa.index','label'=>'Beasiswa','icon'=>'🏅'],
        ['route'=>'spp.index','label'=>'Tagihan SPP','icon'=>'💳'],
        ['divider'=>'MASTER DATA'],
        ['route'=>'master.mahasiswa','label'=>'Mahasiswa','icon'=>'🧑‍🎓'],
        ['route'=>'master.dosen','label'=>'Dosen','icon'=>'👨‍🏫'],
        ['route'=>'master.mata-kuliah','label'=>'Mata Kuliah','icon'=>'📚'],
        ['route'=>'master.fakultas','label'=>'Fakultas & Prodi','icon'=>'🏛️'],
        ['route'=>'master.semester','label'=>'Semester','icon'=>'🗓️'],
        ['divider'=>'LAPORAN'],
        ['route'=>'laporan.index','label'=>'Statistik','icon'=>'📈'],
    ]);
}
$idLabel = $role==='mahasiswa' ? $u->mahasiswa?->nim : ($role==='dosen' ? $u->dosen?->nidn : ucfirst($role));
@endphp
<div class="flex min-h-screen">
<aside class="w-64 bg-indigo-950 flex flex-col fixed inset-y-0 left-0 z-30">
    <div class="flex items-center gap-3 px-5 py-4 border-b border-indigo-900">
        <div class="w-9 h-9 bg-indigo-500 rounded-xl flex items-center justify-center text-lg">🎓</div>
        <div><div class="font-bold text-white text-sm">SIAKAD</div><div class="text-xs text-indigo-300">Sistem Informasi Akademik</div></div>
    </div>
    <nav class="flex-1 px-3 py-3 overflow-y-auto space-y-0.5">
        @foreach($menu as $item)
            @if(isset($item['divider']))
            <div class="pt-3 pb-1 px-3"><span class="text-xs font-semibold text-indigo-400 tracking-wider">{{ $item['divider'] }}</span></div>
            @else
            @php $active = request()->routeIs($item['route'], $item['route'].'.*'); @endphp
            <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ $active ? 'bg-indigo-600 text-white' : 'text-indigo-200 hover:bg-indigo-900 hover:text-white' }}">
                <span>{{ $item['icon'] }}</span>{{ $item['label'] }}
            </a>
            @endif
        @endforeach
    </nav>
    <div class="px-4 py-3 border-t border-indigo-900">
        <div class="flex items-center gap-2 px-2 mb-2">
            <img src="{{ $u->avatar_url }}" class="w-8 h-8 rounded-full flex-shrink-0"/>
            <div class="min-w-0"><div class="text-xs font-medium text-white truncate">{{ $u->name }}</div><div class="text-xs text-indigo-300 truncate">{{ $idLabel }}</div></div>
        </div>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="w-full text-left px-3 py-1.5 text-xs text-indigo-300 hover:text-red-300 hover:bg-indigo-900 rounded-lg transition">🚪 Keluar</button>
        </form>
    </div>
</aside>
<main class="flex-1 ml-64">
    <header class="bg-white border-b border-gray-200 px-8 py-3.5 sticky top-0 z-10">
        <div class="flex items-center justify-between">
            <div><h1 class="text-base font-semibold text-gray-900">@yield('title','Dashboard')</h1>
            @hasSection('subtitle')<p class="text-xs text-gray-400">@yield('subtitle')</p>@endif</div>
            <div class="flex items-center gap-3">
                @if($sem)<span class="text-xs bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full font-medium">📆 {{ $sem->nama }}</span>@endif
                @yield('header-action')
            </div>
        </div>
    </header>
    <div class="px-8 pt-4">
        @foreach(['success'=>'green','error'=>'red','info'=>'blue'] as $type=>$color)
        @if(session($type))<div class="bg-{{ $color }}-50 border border-{{ $color }}-200 text-{{ $color }}-700 px-4 py-3 rounded-lg text-sm mb-4">{{ session($type) }}</div>@endif
        @endforeach
        @if($errors->any())<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    </div>
    <div class="px-8 py-5 pb-10">@yield('content')</div>
</main>
</div>
</body>
</html>

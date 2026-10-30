<?php

namespace App\Http\Controllers;

use App\Models\{Wisuda, PendaftaranWisuda, Mahasiswa};
use Illuminate\Http\Request;

class WisudaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'mahasiswa') {
            $mhs         = $user->mahasiswa;
            $wisudaBuka  = Wisuda::whereIn('status', ['buka','akan_datang'])->get();
            $pendaftaran = PendaftaranWisuda::where('mahasiswa_id', $mhs->id)->with('wisuda')->first();
            $syarat      = $this->cekSyarat($mhs);
            return view('wisuda.mahasiswa', compact('wisudaBuka','pendaftaran','mhs','syarat'));
        }

        $wisuda = Wisuda::withCount('pendaftaran')->orderByDesc('tanggal')->paginate(10);
        return view('wisuda.index', compact('wisuda'));
    }

    public function show(Wisuda $wisuda)
    {
        if (!in_array(auth()->user()->role, ['akademik','admin'])) abort(403);
        $pendaftaran = PendaftaranWisuda::where('wisuda_id', $wisuda->id)
            ->with('mahasiswa.prodi')
            ->orderByDesc('ipk_lulus')
            ->get();
        return view('wisuda.show', compact('wisuda','pendaftaran'));
    }

    public function daftar(Request $request, Wisuda $wisuda)
    {
        $mhs = auth()->user()->mahasiswa;

        if (!$wisuda->isOpen()) return back()->with('error', 'Pendaftaran wisuda sudah ditutup.');

        $syarat = $this->cekSyarat($mhs);
        if (!$syarat['semua_terpenuhi']) {
            return back()->with('error', 'Persyaratan wisuda belum terpenuhi.');
        }

        if (PendaftaranWisuda::where('wisuda_id',$wisuda->id)->where('mahasiswa_id',$mhs->id)->exists()) {
            return back()->with('error', 'Anda sudah terdaftar wisuda ini.');
        }

        $request->validate([
            'judul_skripsi'      => 'required|string|max:255',
            'pembimbing_skripsi' => 'required|string|max:100',
        ]);

        $predikat = match(true) {
            $mhs->ipk >= 3.51 => 'cumlaude',
            $mhs->ipk >= 2.76 => 'sangat_memuaskan',
            default           => 'memuaskan',
        };

        PendaftaranWisuda::create([
            'wisuda_id'          => $wisuda->id,
            'mahasiswa_id'       => $mhs->id,
            'judul_skripsi'      => $request->judul_skripsi,
            'pembimbing_skripsi' => $request->pembimbing_skripsi,
            'ipk_lulus'          => $mhs->ipk,
            'total_sks_lulus'    => $mhs->total_sks_lulus,
            'predikat'           => $predikat,
        ]);

        return back()->with('success', 'Pendaftaran wisuda berhasil dikirim.');
    }

    public function verifikasi(Request $request, PendaftaranWisuda $pendaftaran)
    {
        if (!in_array(auth()->user()->role, ['akademik','admin'])) abort(403);
        $request->validate(['aksi'=>'required|in:diverifikasi,ditolak','catatan'=>'nullable|string']);

        $pendaftaran->update(['status'=>$request->aksi,'catatan'=>$request->catatan]);

        if ($request->aksi === 'diverifikasi') {
            $pendaftaran->mahasiswa->update(['status'=>'lulus']);
        }

        return back()->with('success', 'Pendaftaran wisuda berhasil ' . ($request->aksi === 'diverifikasi' ? 'diverifikasi' : 'ditolak') . '.');
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['akademik','admin'])) abort(403);
        $validated = $request->validate([
            'nama'                  => 'required|string',
            'tanggal'               => 'required|date',
            'lokasi'                => 'nullable|string',
            'jam_mulai'             => 'nullable',
            'kuota'                 => 'nullable|integer',
            'tanggal_daftar_buka'   => 'required|date',
            'tanggal_daftar_tutup'  => 'required|date|after:tanggal_daftar_buka',
        ]);

        Wisuda::create(array_merge($validated, ['status'=>'buka']));
        return redirect()->route('wisuda.index')->with('success','Wisuda berhasil dibuat.');
    }

    // Cek persyaratan wisuda mahasiswa
    private function cekSyarat(Mahasiswa $mhs): array
    {
        $totalSksProdi = $mhs->prodi->total_sks_lulus;
        $syarat = [
            'sks_cukup'     => ['label'=>"Total SKS lulus ≥ {$totalSksProdi}",  'status'=>$mhs->total_sks_lulus >= $totalSksProdi, 'nilai'=>$mhs->total_sks_lulus . ' SKS'],
            'ipk_cukup'     => ['label'=>'IPK minimal 2.00',                    'status'=>$mhs->ipk >= 2.00,                       'nilai'=>number_format($mhs->ipk, 2)],
            'spp_lunas'     => ['label'=>'Tidak ada tunggakan SPP',             'status'=>!\App\Models\TagihanSpp::where('mahasiswa_id',$mhs->id)->where('status','!=','lunas')->exists(), 'nilai'=>'—'],
            'status_aktif'  => ['label'=>'Status mahasiswa aktif',              'status'=>$mhs->status === 'aktif',               'nilai'=>ucfirst($mhs->status)],
        ];
        $syarat['semua_terpenuhi'] = collect($syarat)->every(fn($s) => is_array($s) && $s['status']);
        return $syarat;
    }
}

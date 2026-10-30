<?php

namespace App\Http\Controllers;

use App\Models\{Mahasiswa, Dosen, Kelas, Krs, Nilai, TagihanSpp, Semester, Fakultas, Beasiswa};

class DashboardController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $semester = Semester::aktif();

        return match($user->role) {
            'mahasiswa' => $this->dashboardMahasiswa($user, $semester),
            'dosen'     => $this->dashboardDosen($user, $semester),
            'keuangan'  => $this->dashboardKeuangan($semester),
            default     => $this->dashboardAdmin($semester),
        };
    }

    private function dashboardMahasiswa($user, $semester)
    {
        $mhs = $user->mahasiswa ?? abort(404, 'Data mahasiswa tidak ditemukan.');

        $krsAktif = $mhs->krs()->where('semester_id', $semester?->id)
            ->with(['kelas.mataKuliah','kelas.jadwal.ruangan','kelas.dosen'])
            ->get();

        $totalSks = $krsAktif->where('status','disetujui')
            ->sum(fn($k) => $k->kelas->mataKuliah->sks ?? 0);

        $nilaiTerbaru = $mhs->nilai()
            ->with(['kelas.mataKuliah','semester'])
            ->orderByDesc('created_at')
            ->take(5)->get();

        $tagihan = TagihanSpp::where('mahasiswa_id', $mhs->id)
            ->where('semester_id', $semester?->id)
            ->first();

        $jadwalHariIni = $krsAktif->where('status','disetujui')
            ->flatMap(fn($k) => $k->kelas->jadwal)
            ->filter(fn($j) => $j->hari === strtolower(now()->locale('id')->dayName))
            ->sortBy('jam_mulai');

        return view('dashboard.mahasiswa', compact('mhs','krsAktif','totalSks','nilaiTerbaru','tagihan','jadwalHariIni','semester'));
    }

    private function dashboardDosen($user, $semester)
    {
        $dosen = $user->dosen ?? abort(404, 'Data dosen tidak ditemukan.');

        $kelasSaya = Kelas::where('dosen_id', $dosen->id)
            ->where('semester_id', $semester?->id)
            ->with(['mataKuliah','jadwal.ruangan'])
            ->withCount(['krs as jumlah_mahasiswa' => fn($q) => $q->where('status','disetujui')])
            ->get();

        $totalMahasiswa = $kelasSaya->sum('jumlah_mahasiswa');
        $nilaiBelumDiinput = $kelasSaya->sum(fn($k) =>
            $k->krs()->where('status','disetujui')->whereDoesntHave('nilai')->count()
        );

        $mahasiswaWali = $dosen->mahasiswaWali()->with('prodi')->where('status','aktif')->get();

        $krsMenunggu = Krs::whereIn('mahasiswa_id', $dosen->mahasiswaWali()->pluck('id'))
            ->where('semester_id', $semester?->id)
            ->where('status', 'diajukan')
            ->with(['mahasiswa', 'kelas.mataKuliah'])
            ->get();

        return view('dashboard.dosen', compact('dosen','kelasSaya','totalMahasiswa','nilaiBelumDiinput','mahasiswaWali','krsMenunggu','semester'));
    }

    private function dashboardKeuangan($semester)
    {
        $stats = [
            'total_tagihan'      => TagihanSpp::where('semester_id', $semester?->id)->count(),
            'sudah_lunas'        => TagihanSpp::where('semester_id', $semester?->id)->where('status','lunas')->count(),
            'belum_bayar'        => TagihanSpp::where('semester_id', $semester?->id)->where('status','belum_bayar')->count(),
            'total_pendapatan'   => TagihanSpp::where('semester_id', $semester?->id)->where('status','lunas')->sum('total'),
            'beasiswa_pending'   => \App\Models\PendaftaranBeasiswa::where('status','pending')->count(),
            'wisuda_pending'     => \App\Models\PendaftaranWisuda::where('status','pending')->count(),
        ];

        $tagihanBelumBayar = TagihanSpp::where('semester_id', $semester?->id)
            ->where('status','!=','lunas')
            ->with(['mahasiswa.prodi'])
            ->latest()->take(10)->get();

        $beasiswaBuka = Beasiswa::where('status','buka')->get();

        return view('dashboard.keuangan', compact('stats','tagihanBelumBayar','beasiswaBuka','semester'));
    }

    private function dashboardAdmin($semester)
    {
        $stats = [
            'total_mahasiswa' => Mahasiswa::where('status','aktif')->count(),
            'total_dosen'     => Dosen::where('aktif',true)->count(),
            'total_kelas'     => Kelas::where('semester_id', $semester?->id)->count(),
            'total_krs'       => Krs::where('semester_id', $semester?->id)->count(),
            'krs_pending'     => Krs::where('semester_id', $semester?->id)->where('status','diajukan')->count(),
            'total_fakultas'  => Fakultas::count(),
        ];

        $krsMenunggu = Krs::where('semester_id', $semester?->id)
            ->where('status','diajukan')
            ->with(['mahasiswa','kelas.mataKuliah'])
            ->latest()->take(10)->get();

        $mahasiswaBaru = Mahasiswa::with('prodi')->latest()->take(5)->get();

        return view('dashboard.admin', compact('stats','krsMenunggu','mahasiswaBaru','semester'));
    }
}

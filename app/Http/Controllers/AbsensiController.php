<?php

namespace App\Http\Controllers;

use App\Models\{Absensi, Pertemuan, Kelas, Krs};
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Kelas $kelas)
    {
        $dosen = auth()->user()->dosen;
        if ($kelas->dosen_id !== $dosen?->id && !in_array(auth()->user()->role, ['akademik','admin'])) abort(403);

        $kelas->load(['mataKuliah','semester','pertemuan']);
        $mahasiswaList = Krs::where('kelas_id', $kelas->id)
            ->where('status','disetujui')
            ->with('mahasiswa')
            ->get();

        $rekapAbsensi = [];
        foreach ($mahasiswaList as $krs) {
            $absensiMhs = Absensi::whereHas('pertemuan', fn($q) => $q->where('kelas_id', $kelas->id))
                ->where('mahasiswa_id', $krs->mahasiswa_id)
                ->get();

            $rekapAbsensi[$krs->mahasiswa_id] = [
                'hadir' => $absensiMhs->where('status','hadir')->count(),
                'izin'  => $absensiMhs->where('status','izin')->count(),
                'sakit' => $absensiMhs->where('status','sakit')->count(),
                'alpha' => $absensiMhs->where('status','alpha')->count(),
                'total' => $absensiMhs->count(),
                'persen'=> $absensiMhs->count() > 0
                    ? round(($absensiMhs->where('status','hadir')->count() / $absensiMhs->count()) * 100)
                    : 0,
            ];
        }

        return view('absensi.index', compact('kelas','mahasiswaList','rekapAbsensi'));
    }

    public function pertemuan(Kelas $kelas, int $pertemuanKe)
    {
        $dosen = auth()->user()->dosen;
        if ($kelas->dosen_id !== $dosen?->id && !in_array(auth()->user()->role, ['akademik','admin'])) abort(403);

        $pertemuan = Pertemuan::firstOrCreate(
            ['kelas_id' => $kelas->id, 'pertemuan_ke' => $pertemuanKe],
            ['tanggal' => today()->toDateString(), 'materi' => 'Pertemuan ke-' . $pertemuanKe, 'tipe' => 'kuliah']
        );

        $mahasiswaList = Krs::where('kelas_id', $kelas->id)
            ->where('status','disetujui')
            ->with(['mahasiswa', 'mahasiswa.absensi' => fn($q) => $q->where('pertemuan_id', $pertemuan->id)])
            ->get();

        return view('absensi.pertemuan', compact('kelas','pertemuan','mahasiswaList'));
    }

    public function store(Request $request, Kelas $kelas, Pertemuan $pertemuan)
    {
        $dosen = auth()->user()->dosen;
        if ($kelas->dosen_id !== $dosen?->id && !in_array(auth()->user()->role, ['akademik','admin'])) abort(403);

        $request->validate([
            'materi'                  => 'nullable|string',
            'tipe'                    => 'required|in:kuliah,uts,uas,praktikum,kuis',
            'absensi'                 => 'required|array',
            'absensi.*.mahasiswa_id'  => 'required|exists:mahasiswa,id',
            'absensi.*.status'        => 'required|in:hadir,izin,sakit,alpha',
        ]);

        $pertemuan->update(['materi' => $request->materi, 'tipe' => $request->tipe, 'tanggal' => $request->tanggal ?? today()]);

        foreach ($request->absensi as $data) {
            Absensi::updateOrCreate(
                ['pertemuan_id' => $pertemuan->id, 'mahasiswa_id' => $data['mahasiswa_id']],
                ['status' => $data['status'], 'keterangan' => $data['keterangan'] ?? null]
            );
        }

        return back()->with('success', 'Absensi pertemuan ke-' . $pertemuan->pertemuan_ke . ' berhasil disimpan.');
    }
}

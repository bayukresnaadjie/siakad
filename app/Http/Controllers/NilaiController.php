<?php

namespace App\Http\Controllers;

use App\Models\{Nilai, Kelas, Krs, Khs, Mahasiswa, Semester};
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    // Dosen: lihat & input nilai per kelas
    public function kelas(Kelas $kelas)
    {
        $dosen = auth()->user()->dosen;
        if ($kelas->dosen_id !== $dosen?->id && !in_array(auth()->user()->role, ['akademik','admin'])) abort(403);

        $kelas->load(['mataKuliah','semester','dosen']);
        $mahasiswaList = Krs::where('kelas_id', $kelas->id)
            ->where('status','disetujui')
            ->with(['mahasiswa','nilai'])
            ->get();

        return view('nilai.kelas', compact('kelas','mahasiswaList'));
    }

    // Dosen: input/update nilai
    public function update(Request $request, Kelas $kelas)
    {
        $dosen = auth()->user()->dosen;
        if ($kelas->dosen_id !== $dosen?->id && !in_array(auth()->user()->role, ['akademik','admin'])) abort(403);

        $request->validate([
            'nilai'                  => 'required|array',
            'nilai.*.krs_id'         => 'required|exists:krs,id',
            'nilai.*.nilai_tugas'    => 'nullable|numeric|min:0|max:100',
            'nilai.*.nilai_kuis'     => 'nullable|numeric|min:0|max:100',
            'nilai.*.nilai_uts'      => 'nullable|numeric|min:0|max:100',
            'nilai.*.nilai_uas'      => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($request->nilai as $data) {
            $krs   = Krs::find($data['krs_id']);
            $nilai = Nilai::firstOrNew([
                'krs_id'       => $krs->id,
                'mahasiswa_id' => $krs->mahasiswa_id,
                'kelas_id'     => $kelas->id,
                'semester_id'  => $kelas->semester_id,
            ]);

            if ($nilai->dikunci) continue;

            $nilai->fill([
                'nilai_tugas'  => $data['nilai_tugas']  ?? null,
                'nilai_kuis'   => $data['nilai_kuis']   ?? null,
                'nilai_uts'    => $data['nilai_uts']    ?? null,
                'nilai_uas'    => $data['nilai_uas']    ?? null,
                'diinput_oleh' => $dosen?->id,
                'diinput_at'   => now(),
            ]);
            $nilai->save();

            // Hitung nilai akhir jika semua komponen terisi
            if ($nilai->nilai_tugas !== null && $nilai->nilai_kuis !== null && $nilai->nilai_uts !== null && $nilai->nilai_uas !== null) {
                $nilai->hitungNilaiAkhir();
                // Update IPK mahasiswa
                $nilai->mahasiswa->hitungIpk();
            }
        }

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    public function kunci(Kelas $kelas)
    {
        if (!in_array(auth()->user()->role, ['akademik','admin'])) abort(403);
        Nilai::where('kelas_id', $kelas->id)->whereNotNull('nilai_akhir')->update(['dikunci' => true]);

        // Generate KHS untuk semua mahasiswa di kelas ini
        $this->generateKhs($kelas);

        return back()->with('success', 'Nilai berhasil dikunci. KHS telah digenerate.');
    }

    private function generateKhs(Kelas $kelas)
    {
        $semester = $kelas->semester;
        $mahasiswaIds = Krs::where('kelas_id', $kelas->id)->where('status','disetujui')->pluck('mahasiswa_id')->unique();

        foreach ($mahasiswaIds as $mhsId) {
            $mhs = Mahasiswa::find($mhsId);
            $nilaiSemester = Nilai::where('mahasiswa_id', $mhsId)
                ->where('semester_id', $semester->id)
                ->whereNotNull('bobot')
                ->join('kelas','nilai.kelas_id','=','kelas.id')
                ->join('mata_kuliah','kelas.mata_kuliah_id','=','mata_kuliah.id')
                ->selectRaw('SUM(nilai.bobot * mata_kuliah.sks) as total_mutu, SUM(mata_kuliah.sks) as total_sks, SUM(CASE WHEN nilai.lulus=1 THEN mata_kuliah.sks ELSE 0 END) as sks_lulus')
                ->first();

            $totalSks  = (int)($nilaiSemester->total_sks  ?? 0);
            $sksLulus  = (int)($nilaiSemester->sks_lulus  ?? 0);
            $totalMutu = (float)($nilaiSemester->total_mutu ?? 0);
            $ip        = $totalSks > 0 ? round($totalMutu / $totalSks, 2) : 0;

            Khs::updateOrCreate(
                ['mahasiswa_id' => $mhsId, 'semester_id' => $semester->id],
                ['total_sks_ambil' => $totalSks, 'total_sks_lulus' => $sksLulus, 'ip_semester' => $ip, 'ipk' => $mhs->ipk]
            );
        }
    }

    // Mahasiswa: lihat nilai sendiri
    public function mahasiswa()
    {
        $mhs      = auth()->user()->mahasiswa;
        $semester = Semester::aktif();

        $nilaiPerSemester = Nilai::where('mahasiswa_id', $mhs->id)
            ->with(['kelas.mataKuliah','semester'])
            ->whereNotNull('huruf')
            ->get()
            ->groupBy('semester_id');

        $khs = $mhs->khs()->with('semester')->get();

        return view('nilai.mahasiswa', compact('mhs','nilaiPerSemester','khs','semester'));
    }
}

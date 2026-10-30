<?php

namespace App\Http\Controllers;

use App\Models\{Mahasiswa, Nilai, Khs, Semester};

class TranskripController extends Controller
{
    public function show(?Mahasiswa $mahasiswa = null)
    {
        $user = auth()->user();

        // Mahasiswa hanya bisa lihat transkrip sendiri
        if ($user->role === 'mahasiswa') {
            $mahasiswa = $user->mahasiswa;
        } elseif (!$mahasiswa) {
            abort(404);
        } elseif (!in_array($user->role, ['akademik','admin']) && $user->dosen?->id !== $mahasiswa->dosen_wali_id) {
            abort(403);
        }

        $mahasiswa->load(['prodi.fakultas','dosenWali']);

        // Semua nilai yang sudah ada huruf, dikelompokkan per semester
        $nilaiPerSemester = Nilai::where('mahasiswa_id', $mahasiswa->id)
            ->whereNotNull('huruf')
            ->with(['kelas.mataKuliah','semester'])
            ->get()
            ->groupBy('semester_id')
            ->sortKeys();

        $khsList = Khs::where('mahasiswa_id', $mahasiswa->id)->with('semester')->orderBy('semester_id')->get();

        $totalSks   = $nilaiPerSemester->flatten()->where('lulus', true)->sum(fn($n) => $n->kelas->mataKuliah->sks);
        $totalMutu  = $nilaiPerSemester->flatten()->where('lulus', true)->sum(fn($n) => $n->bobot * $n->kelas->mataKuliah->sks);
        $ipk        = $totalSks > 0 ? round($totalMutu / $totalSks, 2) : 0;

        return view('transkrip.show', compact('mahasiswa','nilaiPerSemester','khsList','totalSks','ipk'));
    }

    public function khs(Semester $semester)
    {
        $mhs = auth()->user()->mahasiswa ?? abort(403);

        $nilai = Nilai::where('mahasiswa_id', $mhs->id)
            ->where('semester_id', $semester->id)
            ->whereNotNull('huruf')
            ->with('kelas.mataKuliah')
            ->get();

        $khs = \App\Models\Khs::where('mahasiswa_id', $mhs->id)->where('semester_id', $semester->id)->first();

        return view('transkrip.khs', compact('mhs','semester','nilai','khs'));
    }
}

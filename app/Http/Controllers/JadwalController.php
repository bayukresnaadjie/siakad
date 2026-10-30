<?php

namespace App\Http\Controllers;

use App\Models\{Kelas, Krs, Semester, JadwalKelas, Ruangan};
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $semester = Semester::aktif();
        $hari     = ['senin','selasa','rabu','kamis','jumat','sabtu'];

        if ($user->role === 'mahasiswa') {
            $mhs      = $user->mahasiswa;
            $kelasIds = Krs::where('mahasiswa_id', $mhs->id)
                ->where('semester_id', $semester?->id)
                ->where('status','disetujui')
                ->pluck('kelas_id');

            $jadwal = JadwalKelas::whereIn('kelas_id', $kelasIds)
                ->with(['kelas.mataKuliah','kelas.dosen','ruangan'])
                ->get()
                ->groupBy('hari');

            return view('jadwal.mahasiswa', compact('jadwal','hari','semester','mhs'));
        }

        if ($user->role === 'dosen') {
            $dosen  = $user->dosen;
            $jadwal = JadwalKelas::whereHas('kelas', fn($q) => $q->where('dosen_id',$dosen->id)->where('semester_id',$semester?->id))
                ->with(['kelas.mataKuliah','ruangan'])
                ->get()
                ->groupBy('hari');

            return view('jadwal.dosen', compact('jadwal','hari','semester','dosen'));
        }

        // Admin/Akademik
        $kelas = Kelas::where('semester_id', $semester?->id)
            ->with(['mataKuliah','dosen','jadwal.ruangan'])
            ->orderBy('mata_kuliah_id')
            ->paginate(20);

        $ruangan = Ruangan::where('aktif',true)->get();
        return view('jadwal.index', compact('kelas','ruangan','semester','hari'));
    }

    // Cek bentrok jadwal
    public function cekBentrok(Request $request)
    {
        $request->validate([
            'ruangan_id'  => 'required|exists:ruangan,id',
            'hari'        => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'kelas_id'    => 'nullable',
        ]);

        $bentrok = JadwalKelas::where('ruangan_id', $request->ruangan_id)
            ->where('hari', $request->hari)
            ->where('kelas_id', '!=', $request->kelas_id ?? 0)
            ->where(function($q) use ($request) {
                $q->whereBetween('jam_mulai',   [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai',[$request->jam_mulai, $request->jam_selesai])
                  ->orWhere(fn($q2) => $q2->where('jam_mulai','<=',$request->jam_mulai)->where('jam_selesai','>=',$request->jam_selesai));
            })
            ->with('kelas.mataKuliah')
            ->first();

        return response()->json([
            'bentrok' => (bool) $bentrok,
            'pesan'   => $bentrok ? 'Bentrok dengan: ' . $bentrok->kelas->mataKuliah->nama : 'Jadwal tersedia',
        ]);
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['akademik','admin'])) abort(403);

        $validated = $request->validate([
            'kelas_id'    => 'required|exists:kelas,id',
            'ruangan_id'  => 'required|exists:ruangan,id',
            'hari'        => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        // Cek bentrok ruangan
        $bentrok = JadwalKelas::where('ruangan_id',$validated['ruangan_id'])
            ->where('hari',$validated['hari'])
            ->where(function($q) use ($validated) {
                $q->whereBetween('jam_mulai',   [$validated['jam_mulai'],$validated['jam_selesai']])
                  ->orWhereBetween('jam_selesai',[$validated['jam_mulai'],$validated['jam_selesai']]);
            })->exists();

        if ($bentrok) return back()->with('error','Jadwal bentrok dengan kelas lain di ruangan yang sama.');

        JadwalKelas::create($validated);
        return back()->with('success','Jadwal berhasil ditambahkan.');
    }
}

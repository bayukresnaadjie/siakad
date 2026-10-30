<?php

namespace App\Http\Controllers;

use App\Models\{Krs, Kelas, Semester, Mahasiswa, Nilai};
use Illuminate\Http\Request;

class KrsController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $semester = Semester::aktif();
        $mhs      = $user->mahasiswa;

        $krsAktif = Krs::where('mahasiswa_id', $mhs->id)
            ->where('semester_id', $semester?->id)
            ->with(['kelas.mataKuliah','kelas.dosen','kelas.jadwal.ruangan'])
            ->get();

        $totalSks = $krsAktif->where('status','disetujui')
            ->sum(fn($k) => $k->kelas->mataKuliah->sks ?? 0);

        $kelasTersedia = Kelas::where('semester_id', $semester?->id)
            ->whereHas('mataKuliah', fn($q) => $q->where('prodi_id', $mhs->prodi_id))
            ->whereNotIn('id', $krsAktif->pluck('kelas_id'))
            ->with(['mataKuliah','dosen','jadwal.ruangan'])
            ->withCount(['krs as terisi' => fn($q) => $q->where('status','disetujui')])
            ->get();

        return view('krs.index', compact('krsAktif','totalSks','kelasTersedia','semester','mhs'));
    }

    public function store(Request $request)
    {
        $user     = auth()->user();
        $mhs      = $user->mahasiswa;
        $semester = Semester::aktif();

        if (!$semester?->isKrsOpen()) {
            return back()->with('error', 'Periode KRS belum dibuka atau sudah ditutup.');
        }

        $request->validate(['kelas_id' => 'required|exists:kelas,id']);

        $kelas = Kelas::with('mataKuliah')->findOrFail($request->kelas_id);

        // Cek sudah ada di KRS
        if (Krs::where('mahasiswa_id',$mhs->id)->where('kelas_id',$kelas->id)->where('semester_id',$semester->id)->exists()) {
            return back()->with('error', 'Mata kuliah ini sudah ada di KRS Anda.');
        }

        // Cek kapasitas
        $terisi = Krs::where('kelas_id',$kelas->id)->where('status','disetujui')->count();
        if ($terisi >= $kelas->kapasitas) {
            return back()->with('error', 'Kelas sudah penuh.');
        }

        // Cek batas SKS
        $totalSksAktif = Krs::where('mahasiswa_id',$mhs->id)
            ->where('semester_id',$semester->id)
            ->where('status','!=' ,'ditolak')
            ->join('kelas','krs.kelas_id','=','kelas.id')
            ->join('mata_kuliah','kelas.mata_kuliah_id','=','mata_kuliah.id')
            ->sum('mata_kuliah.sks');

        if ($totalSksAktif + $kelas->mataKuliah->sks > $mhs->prodi->maks_sks) {
            return back()->with('error', 'Melebihi batas maksimal SKS (' . $mhs->prodi->maks_sks . ' SKS).');
        }

        Krs::create([
            'mahasiswa_id' => $mhs->id,
            'kelas_id'     => $kelas->id,
            'semester_id'  => $semester->id,
            'status'       => 'draft',
        ]);

        return back()->with('success', 'Mata kuliah ' . $kelas->mataKuliah->nama . ' berhasil ditambahkan ke KRS.');
    }

    public function ajukan(Request $request)
    {
        $mhs      = auth()->user()->mahasiswa;
        $semester = Semester::aktif();

        $krsDraft = Krs::where('mahasiswa_id',$mhs->id)
            ->where('semester_id',$semester->id)
            ->where('status','draft')
            ->get();

        if ($krsDraft->isEmpty()) {
            return back()->with('error', 'Tidak ada KRS draft untuk diajukan.');
        }

        $krsDraft->each(fn($k) => $k->update(['status' => 'diajukan']));

        return back()->with('success', $krsDraft->count() . ' mata kuliah berhasil diajukan untuk persetujuan dosen wali.');
    }

    public function destroy(Krs $krs)
    {
        $mhs = auth()->user()->mahasiswa;
        if ($krs->mahasiswa_id !== $mhs->id) abort(403);
        if (!in_array($krs->status, ['draft','ditolak'])) {
            return back()->with('error', 'Tidak bisa menghapus KRS yang sudah diajukan.');
        }
        $krs->delete();
        return back()->with('success', 'Mata kuliah berhasil dihapus dari KRS.');
    }

    // Dosen wali: approve/tolak KRS
    public function approveKrs(Request $request, Krs $krs)
    {
        $dosen = auth()->user()->dosen;
        if ($krs->mahasiswa->dosen_wali_id !== $dosen?->id && !in_array(auth()->user()->role, ['akademik','admin'])) abort(403);

        $request->validate(['aksi' => 'required|in:setujui,tolak', 'catatan' => 'nullable|string']);

        $krs->update([
            'status'         => $request->aksi === 'setujui' ? 'disetujui' : 'ditolak',
            'disetujui_oleh' => $dosen?->id,
            'disetujui_at'   => now(),
            'catatan'        => $request->catatan,
        ]);

        // Buat record nilai kosong jika disetujui
        if ($request->aksi === 'setujui') {
            Nilai::firstOrCreate([
                'krs_id'       => $krs->id,
                'mahasiswa_id' => $krs->mahasiswa_id,
                'kelas_id'     => $krs->kelas_id,
                'semester_id'  => $krs->semester_id,
            ]);
        }

        return back()->with('success', 'KRS berhasil ' . ($request->aksi === 'setujui' ? 'disetujui' : 'ditolak') . '.');
    }
}

<?php
namespace App\Http\Controllers;
use App\Models\{Fakultas, ProgramStudi, MataKuliah, Semester, Ruangan, Dosen, Mahasiswa, Kelas, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class MasterController extends Controller {
    private function cekAkses() { if (!in_array(auth()->user()->role, ['admin','akademik'])) abort(403); }

    // Mahasiswa
    public function mahasiswa(Request $request) {
        $this->cekAkses();
        $mahasiswa = Mahasiswa::with(['prodi','dosenWali'])
            ->when($request->search, fn($q) => $q->where('nama','like',"%{$request->search}%")->orWhere('nim','like',"%{$request->search}%"))
            ->when($request->prodi_id, fn($q) => $q->where('prodi_id',$request->prodi_id))
            ->when($request->angkatan, fn($q) => $q->where('angkatan',$request->angkatan))
            ->when($request->status, fn($q) => $q->where('status',$request->status))
            ->orderBy('nim')->paginate(20)->withQueryString();
        $prodiList = ProgramStudi::where('aktif',true)->get();
        return view('master.mahasiswa', compact('mahasiswa','prodiList'));
    }
    public function mahasiswaShow(Mahasiswa $mahasiswa) {
        $this->cekAkses();
        $mahasiswa->load(['prodi.fakultas','dosenWali','khs.semester']);
        return view('master.mahasiswa-show', compact('mahasiswa'));
    }

    // Dosen
    public function dosen(Request $request) {
        $this->cekAkses();
        $dosen = Dosen::with(['fakultas','prodi'])
            ->when($request->search, fn($q) => $q->where('nama','like',"%{$request->search}%")->orWhere('nidn','like',"%{$request->search}%"))
            ->orderBy('nama')->paginate(20)->withQueryString();
        return view('master.dosen', compact('dosen'));
    }

    // Mata Kuliah
    public function mataKuliah(Request $request) {
        $this->cekAkses();
        $mk = MataKuliah::with('prodi')
            ->when($request->prodi_id, fn($q) => $q->where('prodi_id',$request->prodi_id))
            ->when($request->semester, fn($q) => $q->where('semester',$request->semester))
            ->when($request->search, fn($q) => $q->where('nama','like',"%{$request->search}%")->orWhere('kode','like',"%{$request->search}%"))
            ->orderBy('prodi_id')->orderBy('semester')->paginate(25)->withQueryString();
        $prodiList = ProgramStudi::where('aktif',true)->get();
        return view('master.mata-kuliah', compact('mk','prodiList'));
    }

    // Fakultas & Prodi
    public function fakultas() {
        $this->cekAkses();
        $fakultas = Fakultas::with('programStudi')->withCount('dosen')->get();
        return view('master.fakultas', compact('fakultas'));
    }

    // Semester
    public function semester() {
        $this->cekAkses();
        $semester = Semester::orderByDesc('kode')->get();
        return view('master.semester', compact('semester'));
    }
    public function aktifkanSemester(Semester $semester) {
        $this->cekAkses();
        Semester::query()->update(['is_aktif'=>false]);
        $semester->update(['is_aktif'=>true]);
        return back()->with('success','Semester '.$semester->nama.' berhasil diaktifkan.');
    }
}

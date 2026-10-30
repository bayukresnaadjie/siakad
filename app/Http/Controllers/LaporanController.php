<?php
namespace App\Http\Controllers;
use App\Models\{Mahasiswa, ProgramStudi, Nilai, Krs, Semester, Dosen, TagihanSpp};
class LaporanController extends Controller {
    public function index() {
        if (!in_array(auth()->user()->role, ['admin','akademik','keuangan'])) abort(403);
        $semester = Semester::aktif();
        $perProdi = ProgramStudi::withCount(['mahasiswa as aktif_count' => fn($q) => $q->where('status','aktif')])->get();
        $distribusiIpk = [
            'cumlaude'          => Mahasiswa::where('status','aktif')->where('ipk','>=',3.51)->count(),
            'sangat_memuaskan'  => Mahasiswa::where('status','aktif')->whereBetween('ipk',[2.76,3.50])->count(),
            'memuaskan'         => Mahasiswa::where('status','aktif')->whereBetween('ipk',[2.00,2.75])->count(),
            'kurang'            => Mahasiswa::where('status','aktif')->where('ipk','<',2.00)->where('ipk','>',0)->count(),
        ];
        $perAngkatan = Mahasiswa::selectRaw('angkatan, count(*) as total, AVG(ipk) as rata_ipk')->groupBy('angkatan')->orderBy('angkatan','desc')->get();
        $distribusiNilai = Nilai::selectRaw('huruf, count(*) as total')->whereNotNull('huruf')->groupBy('huruf')->orderByRaw("FIELD(huruf,'A','A-','B+','B','B-','C+','C','C-','D','E')")->get();
        $topMahasiswa = Mahasiswa::with('prodi')->where('status','aktif')->orderByDesc('ipk')->take(10)->get();
        $statsSpp = [
            'lunas'       => TagihanSpp::where('semester_id',$semester?->id)->where('status','lunas')->count(),
            'sebagian'    => TagihanSpp::where('semester_id',$semester?->id)->where('status','sebagian')->count(),
            'belum_bayar' => TagihanSpp::where('semester_id',$semester?->id)->where('status','belum_bayar')->count(),
        ];
        return view('laporan.index', compact('perProdi','distribusiIpk','perAngkatan','distribusiNilai','topMahasiswa','statsSpp','semester'));
    }
}

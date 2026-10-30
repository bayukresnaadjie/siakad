<?php

namespace App\Http\Controllers;

use App\Models\{Beasiswa, PendaftaranBeasiswa, Mahasiswa};
use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'mahasiswa') {
            $mhs           = $user->mahasiswa;
            $beasiswaBuka  = Beasiswa::where('status','buka')->get();
            $pendaftaranku = PendaftaranBeasiswa::where('mahasiswa_id',$mhs->id)->with('beasiswa')->get();
            return view('beasiswa.mahasiswa', compact('beasiswaBuka','pendaftaranku','mhs'));
        }

        $beasiswa = Beasiswa::withCount('pendaftaran')->orderByDesc('created_at')->paginate(15);
        return view('beasiswa.index', compact('beasiswa'));
    }

    public function show(Beasiswa $beasiswa)
    {
        $user = auth()->user();
        if ($user->role === 'mahasiswa') {
            $mhs        = $user->mahasiswa;
            $sudahDaftar = PendaftaranBeasiswa::where('beasiswa_id',$beasiswa->id)->where('mahasiswa_id',$mhs->id)->first();
            return view('beasiswa.show', compact('beasiswa','mhs','sudahDaftar'));
        }
        $pendaftaran = PendaftaranBeasiswa::where('beasiswa_id',$beasiswa->id)->with('mahasiswa.prodi')->get();
        return view('beasiswa.detail', compact('beasiswa','pendaftaran'));
    }

    public function daftar(Request $request, Beasiswa $beasiswa)
    {
        $mhs = auth()->user()->mahasiswa;
        if (!$beasiswa->isOpen()) return back()->with('error','Beasiswa sudah tidak bisa didaftar.');
        if ($mhs->ipk < $beasiswa->min_ipk) return back()->with('error','IPK Anda tidak memenuhi syarat minimum.');
        if (PendaftaranBeasiswa::where('beasiswa_id',$beasiswa->id)->where('mahasiswa_id',$mhs->id)->exists()) {
            return back()->with('error','Anda sudah mendaftar beasiswa ini.');
        }

        $request->validate(['motivasi'=>'required|string|min:100']);

        PendaftaranBeasiswa::create([
            'beasiswa_id'  => $beasiswa->id,
            'mahasiswa_id' => $mhs->id,
            'motivasi'     => $request->motivasi,
        ]);

        return back()->with('success','Pendaftaran beasiswa berhasil dikirim.');
    }

    public function proses(Request $request, PendaftaranBeasiswa $pendaftaran)
    {
        if (!in_array(auth()->user()->role,['keuangan','admin'])) abort(403);
        $request->validate(['aksi'=>'required|in:lolos_berkas,tidak_lolos,diterima,ditolak','catatan'=>'nullable|string']);

        $pendaftaran->update([
            'status'        => $request->aksi,
            'catatan_admin' => $request->catatan,
            'diproses_oleh' => auth()->id(),
            'diproses_at'   => now(),
        ]);

        return back()->with('success','Status beasiswa berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role,['keuangan','admin'])) abort(403);
        $validated = $request->validate([
            'nama'           => 'required|string',
            'deskripsi'      => 'nullable|string',
            'penyelenggara'  => 'nullable|string',
            'nominal'        => 'required|numeric|min:0',
            'kuota'          => 'required|integer|min:1',
            'min_ipk'        => 'required|numeric|min:0|max:4',
            'maks_semester'  => 'nullable|integer',
            'tanggal_buka'   => 'required|date',
            'tanggal_tutup'  => 'required|date|after:tanggal_buka',
        ]);

        Beasiswa::create(array_merge($validated,['status'=>'buka']));
        return redirect()->route('beasiswa.index')->with('success','Beasiswa berhasil dibuat.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\{TagihanSpp, PembayaranSpp, Mahasiswa, Semester};
use Illuminate\Http\Request;

class SppController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'mahasiswa') {
            $mhs     = $user->mahasiswa;
            $tagihan = TagihanSpp::where('mahasiswa_id', $mhs->id)
                ->with(['semester','pembayaran'])
                ->orderByDesc('created_at')->get();
            return view('spp.mahasiswa', compact('tagihan','mhs'));
        }

        // Keuangan/Admin
        $semester = Semester::aktif();
        $tagihan  = TagihanSpp::where('semester_id', $semester?->id)
            ->with(['mahasiswa.prodi','pembayaran'])
            ->orderByDesc('created_at')->paginate(20);
        return view('spp.index', compact('tagihan','semester'));
    }

    public function bayar(Request $request, TagihanSpp $tagihan)
    {
        if (auth()->user()->mahasiswa?->id !== $tagihan->mahasiswa_id) abort(403);

        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:10000',
            'metode'       => 'required|in:transfer,cash,virtual_account',
            'bukti_bayar'  => 'required|image|max:2048',
        ]);

        $path = $request->file('bukti_bayar')->store('bukti-spp', 'public');

        PembayaranSpp::create([
            'tagihan_spp_id' => $tagihan->id,
            'mahasiswa_id'   => $tagihan->mahasiswa_id,
            'nomor_bayar'    => 'PAY-' . now()->format('YmdHis') . '-' . $tagihan->mahasiswa_id,
            'jumlah_bayar'   => $request->jumlah_bayar,
            'metode'         => $request->metode,
            'bukti_bayar'    => $path,
            'status'         => 'pending',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi.');
    }

    public function verifikasi(Request $request, PembayaranSpp $pembayaran)
    {
        if (!in_array(auth()->user()->role, ['keuangan','admin'])) abort(403);

        $request->validate(['aksi' => 'required|in:verify,reject']);

        if ($request->aksi === 'verify') {
            $pembayaran->update(['status'=>'verified','diverifikasi_oleh'=>auth()->id(),'diverifikasi_at'=>now()]);
            $tagihan = $pembayaran->tagihan;
            $totalBayar = $tagihan->pembayaran()->where('status','verified')->sum('jumlah_bayar');
            $tagihan->update(['status' => $totalBayar >= (float)$tagihan->total ? 'lunas' : 'sebagian']);
        } else {
            $pembayaran->update(['status' => 'rejected']);
        }

        return back()->with('success', 'Pembayaran berhasil ' . ($request->aksi === 'verify' ? 'diverifikasi' : 'ditolak') . '.');
    }

    public function generate(Semester $semester)
    {
        if (!in_array(auth()->user()->role, ['keuangan','admin'])) abort(403);

        $mahasiswaAktif = Mahasiswa::where('status','aktif')->get();
        $generated = 0;

        foreach ($mahasiswaAktif as $mhs) {
            if (!TagihanSpp::where('mahasiswa_id',$mhs->id)->where('semester_id',$semester->id)->exists()) {
                TagihanSpp::create([
                    'mahasiswa_id'  => $mhs->id,
                    'semester_id'   => $semester->id,
                    'nomor_tagihan' => 'SPP-' . $semester->kode . '-' . $mhs->nim,
                    'jumlah'        => 4500000,
                    'denda'         => 0,
                    'diskon'        => 0,
                    'total'         => 4500000,
                    'status'        => 'belum_bayar',
                    'jatuh_tempo'   => $semester->tanggal_mulai?->addMonth(),
                ]);
                $generated++;
            }
        }

        return back()->with('success', "Berhasil generate {$generated} tagihan SPP.");
    }
}

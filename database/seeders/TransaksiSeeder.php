<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{TagihanSpp, PembayaranSpp, Beasiswa, PendaftaranBeasiswa, Wisuda, PendaftaranWisuda, Mahasiswa, Semester, User};

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $semAktif = Semester::where('is_aktif',true)->first();
        $semLalu  = Semester::where('is_aktif',false)->first();
        $keuangan = User::where('email','keuangan@siakad.test')->first();

        $ahmad  = Mahasiswa::where('nim','2021SI001')->first();
        $putri  = Mahasiswa::where('nim','2021SI002')->first();
        $rizky  = Mahasiswa::where('nim','2022SI001')->first();
        $nurul  = Mahasiswa::where('nim','2022SI002')->first();
        $fajar  = Mahasiswa::where('nim','2022TI001')->first();
        $maya   = Mahasiswa::where('nim','2023SI001')->first();

        // ── TAGIHAN SPP ───────────────────────────────────────────────────────
        $sppData = [
            [$ahmad,  $semAktif, 5000000, 'lunas'],
            [$putri,  $semAktif, 5000000, 'lunas'],
            [$rizky,  $semAktif, 4500000, 'belum_bayar'],
            [$nurul,  $semAktif, 4500000, 'sebagian'],
            [$fajar,  $semAktif, 4500000, 'lunas'],
            [$maya,   $semAktif, 4000000, 'belum_bayar'],
        ];

        foreach ($sppData as [$mhs, $sem, $jumlah, $status]) {
            $tagihan = TagihanSpp::create([
                'mahasiswa_id'  => $mhs->id,
                'semester_id'   => $sem->id,
                'nomor_tagihan' => 'SPP-' . $sem->kode . '-' . $mhs->nim,
                'jumlah'        => $jumlah,
                'denda'         => $status === 'belum_bayar' ? 100000 : 0,
                'diskon'        => 0,
                'total'         => $jumlah + ($status === 'belum_bayar' ? 100000 : 0),
                'status'        => $status,
                'jatuh_tempo'   => now()->addDays(30)->toDateString(),
            ]);

            if ($status === 'lunas') {
                PembayaranSpp::create([
                    'tagihan_spp_id'   => $tagihan->id,
                    'mahasiswa_id'     => $mhs->id,
                    'nomor_bayar'      => 'PAY-' . now()->format('YmdHis') . '-' . $mhs->id,
                    'jumlah_bayar'     => $jumlah,
                    'metode'           => 'transfer',
                    'status'           => 'verified',
                    'diverifikasi_oleh'=> $keuangan->id,
                    'diverifikasi_at'  => now()->subDays(5),
                ]);
            } elseif ($status === 'sebagian') {
                PembayaranSpp::create([
                    'tagihan_spp_id'   => $tagihan->id,
                    'mahasiswa_id'     => $mhs->id,
                    'nomor_bayar'      => 'PAY-' . now()->format('YmdHis') . '-' . $mhs->id,
                    'jumlah_bayar'     => $jumlah / 2,
                    'metode'           => 'virtual_account',
                    'status'           => 'verified',
                    'diverifikasi_oleh'=> $keuangan->id,
                    'diverifikasi_at'  => now()->subDays(3),
                ]);
            }
        }

        // ── BEASISWA ──────────────────────────────────────────────────────────
        $bea1 = Beasiswa::create([
            'nama'          => 'Beasiswa Prestasi Akademik 2024',
            'deskripsi'     => 'Beasiswa untuk mahasiswa berprestasi dengan IPK minimal 3.50',
            'penyelenggara' => 'Universitas',
            'nominal'       => 5000000,
            'kuota'         => 10,
            'min_ipk'       => 3.50,
            'maks_semester' => 8,
            'tanggal_buka'  => now()->subDays(30)->toDateString(),
            'tanggal_tutup' => now()->addDays(30)->toDateString(),
            'status'        => 'buka',
        ]);

        $bea2 = Beasiswa::create([
            'nama'          => 'Beasiswa Yayasan Pendidikan Nusantara',
            'deskripsi'     => 'Beasiswa dari yayasan eksternal untuk mahasiswa kurang mampu',
            'penyelenggara' => 'Yayasan Pendidikan Nusantara',
            'nominal'       => 8000000,
            'kuota'         => 5,
            'min_ipk'       => 3.00,
            'tanggal_buka'  => now()->subDays(10)->toDateString(),
            'tanggal_tutup' => now()->addDays(20)->toDateString(),
            'status'        => 'buka',
        ]);

        // Pendaftaran beasiswa
        PendaftaranBeasiswa::create(['beasiswa_id'=>$bea1->id,'mahasiswa_id'=>$ahmad->id,'motivasi'=>'Saya ingin melanjutkan studi dengan prestasi terbaik.','status'=>'diterima','diproses_oleh'=>$keuangan->id,'diproses_at'=>now()->subDays(5)]);
        PendaftaranBeasiswa::create(['beasiswa_id'=>$bea1->id,'mahasiswa_id'=>$putri->id,'motivasi'=>'Saya berkomitmen untuk terus berprestasi dalam akademik.','status'=>'diterima','diproses_oleh'=>$keuangan->id,'diproses_at'=>now()->subDays(5)]);
        PendaftaranBeasiswa::create(['beasiswa_id'=>$bea1->id,'mahasiswa_id'=>$nurul->id,'motivasi'=>'Beasiswa ini akan sangat membantu pendidikan saya.','status'=>'pending']);
        PendaftaranBeasiswa::create(['beasiswa_id'=>$bea2->id,'mahasiswa_id'=>$rizky->id,'motivasi'=>'Saya membutuhkan bantuan untuk biaya pendidikan.','status'=>'lolos_berkas','diproses_oleh'=>$keuangan->id,'diproses_at'=>now()->subDays(2)]);
        PendaftaranBeasiswa::create(['beasiswa_id'=>$bea2->id,'mahasiswa_id'=>$maya->id, 'motivasi'=>'Mohon pertimbangan untuk beasiswa ini.','status'=>'pending']);

        // ── WISUDA ────────────────────────────────────────────────────────────
        $wisuda = Wisuda::create([
            'nama'                 => 'Wisuda Periode III Tahun 2024',
            'tanggal'              => now()->addMonths(3)->toDateString(),
            'lokasi'               => 'Aula Utama Universitas',
            'jam_mulai'            => '08:00',
            'kuota'                => 500,
            'tanggal_daftar_buka'  => now()->toDateString(),
            'tanggal_daftar_tutup' => now()->addMonths(2)->toDateString(),
            'status'               => 'buka',
        ]);

        PendaftaranWisuda::create([
            'wisuda_id'          => $wisuda->id,
            'mahasiswa_id'       => $ahmad->id,
            'judul_skripsi'      => 'Sistem Informasi Manajemen Berbasis Web untuk UMKM',
            'pembimbing_skripsi' => 'Dr. Budi Santoso, M.Kom.',
            'ipk_lulus'          => 3.75,
            'total_sks_lulus'    => 144,
            'predikat'           => 'cumlaude',
            'status'             => 'diverifikasi',
        ]);

        PendaftaranWisuda::create([
            'wisuda_id'          => $wisuda->id,
            'mahasiswa_id'       => $putri->id,
            'judul_skripsi'      => 'Implementasi Machine Learning untuk Prediksi Nilai Akademik',
            'pembimbing_skripsi' => 'Dr. Siti Rahayu, M.T.',
            'ipk_lulus'          => 3.89,
            'total_sks_lulus'    => 144,
            'predikat'           => 'cumlaude',
            'status'             => 'pending',
        ]);

        $this->command->info('✅ Transaksi seeded: SPP, beasiswa, wisuda');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Kelas, JadwalKelas, Krs, Pertemuan, Absensi, Nilai, Ruangan, MataKuliah, Semester, Dosen, Mahasiswa};

class AkademikSeeder extends Seeder
{
    public function run(): void
    {
        $semAktif = Semester::where('is_aktif', true)->first();
        $semLalu  = Semester::where('is_aktif', false)->first();

        $dosenBudi = Dosen::where('nidn','0101010001')->first();
        $dosenSiti = Dosen::where('nidn','0101010002')->first();
        $dosenAndi = Dosen::where('nidn','0101010003')->first();

        $ruangA101 = Ruangan::where('kode','GD-A-101')->first();
        $ruangA102 = Ruangan::where('kode','GD-A-102')->first();
        $ruangA201 = Ruangan::where('kode','GD-A-201')->first();
        $lab01     = Ruangan::where('kode','LAB-01')->first();

        $ahmad   = Mahasiswa::where('nim','2021SI001')->first();
        $putri   = Mahasiswa::where('nim','2021SI002')->first();
        $rizky   = Mahasiswa::where('nim','2022SI001')->first();
        $nurul   = Mahasiswa::where('nim','2022SI002')->first();
        $fajar   = Mahasiswa::where('nim','2022TI001')->first();
        $maya    = Mahasiswa::where('nim','2023SI001')->first();

        $mkPBO  = MataKuliah::where('kode','SI004')->first();
        $mkBD   = MataKuliah::where('kode','SI005')->first();
        $mkRPL  = MataKuliah::where('kode','SI008')->first();
        $mkWeb  = MataKuliah::where('kode','SI009')->first();
        $mkADS  = MataKuliah::where('kode','SI010')->first();
        $mkDM   = MataKuliah::where('kode','SI012')->first();
        $mkTI7  = MataKuliah::where('kode','TI007')->first();

        // ── KELAS SEMESTER AKTIF ──────────────────────────────────────────────
        $kelasPBO = Kelas::create(['mata_kuliah_id'=>$mkPBO->id,'semester_id'=>$semAktif->id,'dosen_id'=>$dosenBudi->id,'nama_kelas'=>'A','kapasitas'=>40,'jumlah_pertemuan'=>16]);
        $kelasBD  = Kelas::create(['mata_kuliah_id'=>$mkBD->id, 'semester_id'=>$semAktif->id,'dosen_id'=>$dosenSiti->id,'nama_kelas'=>'A','kapasitas'=>40,'jumlah_pertemuan'=>16]);
        $kelasRPL = Kelas::create(['mata_kuliah_id'=>$mkRPL->id,'semester_id'=>$semAktif->id,'dosen_id'=>$dosenBudi->id,'nama_kelas'=>'A','kapasitas'=>40,'jumlah_pertemuan'=>16]);
        $kelasWeb = Kelas::create(['mata_kuliah_id'=>$mkWeb->id,'semester_id'=>$semAktif->id,'dosen_id'=>$dosenSiti->id,'nama_kelas'=>'A','kapasitas'=>40,'jumlah_pertemuan'=>16]);
        $kelasADS = Kelas::create(['mata_kuliah_id'=>$mkADS->id,'semester_id'=>$semAktif->id,'dosen_id'=>$dosenBudi->id,'nama_kelas'=>'A','kapasitas'=>40,'jumlah_pertemuan'=>16]);
        $kelasDM  = Kelas::create(['mata_kuliah_id'=>$mkDM->id, 'semester_id'=>$semAktif->id,'dosen_id'=>$dosenSiti->id,'nama_kelas'=>'A','kapasitas'=>40,'jumlah_pertemuan'=>16]);
        $kelasTI7 = Kelas::create(['mata_kuliah_id'=>$mkTI7->id,'semester_id'=>$semAktif->id,'dosen_id'=>$dosenAndi->id,'nama_kelas'=>'A','kapasitas'=>35,'jumlah_pertemuan'=>16]);

        // ── JADWAL ────────────────────────────────────────────────────────────
        JadwalKelas::create(['kelas_id'=>$kelasPBO->id,'ruangan_id'=>$ruangA101->id,'hari'=>'senin',  'jam_mulai'=>'08:00','jam_selesai'=>'10:30']);
        JadwalKelas::create(['kelas_id'=>$kelasBD->id, 'ruangan_id'=>$ruangA102->id,'hari'=>'senin',  'jam_mulai'=>'10:30','jam_selesai'=>'13:00']);
        JadwalKelas::create(['kelas_id'=>$kelasRPL->id,'ruangan_id'=>$ruangA201->id,'hari'=>'selasa', 'jam_mulai'=>'08:00','jam_selesai'=>'10:30']);
        JadwalKelas::create(['kelas_id'=>$kelasWeb->id,'ruangan_id'=>$lab01->id,    'hari'=>'selasa', 'jam_mulai'=>'10:30','jam_selesai'=>'13:00']);
        JadwalKelas::create(['kelas_id'=>$kelasADS->id,'ruangan_id'=>$ruangA101->id,'hari'=>'rabu',   'jam_mulai'=>'08:00','jam_selesai'=>'10:30']);
        JadwalKelas::create(['kelas_id'=>$kelasDM->id, 'ruangan_id'=>$ruangA102->id,'hari'=>'kamis',  'jam_mulai'=>'08:00','jam_selesai'=>'10:30']);
        JadwalKelas::create(['kelas_id'=>$kelasTI7->id,'ruangan_id'=>$ruangA201->id,'hari'=>'jumat',  'jam_mulai'=>'08:00','jam_selesai'=>'10:30']);

        // ── KRS SEMESTER AKTIF ────────────────────────────────────────────────
        // Ahmad & Putri (semester 7 → ambil semester 4 keatas)
        foreach ([$ahmad, $putri] as $mhs) {
            foreach ([$kelasRPL, $kelasWeb, $kelasADS, $kelasDM] as $kls) {
                Krs::create(['mahasiswa_id'=>$mhs->id,'kelas_id'=>$kls->id,'semester_id'=>$semAktif->id,'status'=>'disetujui','disetujui_oleh'=>$dosenBudi->id,'disetujui_at'=>now()->subDays(10)]);
            }
        }
        // Rizky & Nurul (semester 5 → ambil semester 3)
        foreach ([$rizky, $nurul] as $mhs) {
            foreach ([$kelasPBO, $kelasBD] as $kls) {
                Krs::create(['mahasiswa_id'=>$mhs->id,'kelas_id'=>$kls->id,'semester_id'=>$semAktif->id,'status'=>'disetujui','disetujui_oleh'=>$dosenSiti->id,'disetujui_at'=>now()->subDays(10)]);
            }
        }
        // Maya (semester 3)
        Krs::create(['mahasiswa_id'=>$maya->id,'kelas_id'=>$kelasPBO->id,'semester_id'=>$semAktif->id,'status'=>'diajukan']);
        Krs::create(['mahasiswa_id'=>$maya->id,'kelas_id'=>$kelasBD->id, 'semester_id'=>$semAktif->id,'status'=>'diajukan']);
        // Fajar (TI)
        Krs::create(['mahasiswa_id'=>$fajar->id,'kelas_id'=>$kelasTI7->id,'semester_id'=>$semAktif->id,'status'=>'disetujui','disetujui_oleh'=>$dosenAndi->id,'disetujui_at'=>now()->subDays(10)]);

        // ── PERTEMUAN & ABSENSI ───────────────────────────────────────────────
        $kelasList = [$kelasRPL, $kelasWeb, $kelasPBO];
        foreach ($kelasList as $kls) {
            for ($p = 1; $p <= 8; $p++) {
                $pertemuan = Pertemuan::create([
                    'kelas_id'     => $kls->id,
                    'pertemuan_ke' => $p,
                    'tanggal'      => now()->subWeeks(8 - $p)->toDateString(),
                    'materi'       => 'Pertemuan ke-' . $p,
                    'tipe'         => $p === 8 ? 'uts' : 'kuliah',
                ]);

                // Absensi per mahasiswa
                $mhsList = Krs::where('kelas_id', $kls->id)->where('status','disetujui')->pluck('mahasiswa_id');
                foreach ($mhsList as $mhsId) {
                    Absensi::create([
                        'pertemuan_id' => $pertemuan->id,
                        'mahasiswa_id' => $mhsId,
                        'status'       => $p <= 6 ? 'hadir' : (rand(0,3) === 0 ? 'alpha' : 'hadir'),
                    ]);
                }
            }
        }

        // ── NILAI ─────────────────────────────────────────────────────────────
        $nilaiData = [
            // Ahmad
            ['mhs'=>$ahmad, 'kelas'=>$kelasRPL, 'tugas'=>88,'kuis'=>85,'uts'=>82,'uas'=>86],
            ['mhs'=>$ahmad, 'kelas'=>$kelasWeb,  'tugas'=>90,'kuis'=>88,'uts'=>85,'uas'=>89],
            ['mhs'=>$ahmad, 'kelas'=>$kelasADS,  'tugas'=>85,'kuis'=>82,'uts'=>80,'uas'=>84],
            ['mhs'=>$ahmad, 'kelas'=>$kelasDM,   'tugas'=>78,'kuis'=>75,'uts'=>72,'uas'=>76],
            // Putri
            ['mhs'=>$putri, 'kelas'=>$kelasRPL, 'tugas'=>92,'kuis'=>90,'uts'=>88,'uas'=>91],
            ['mhs'=>$putri, 'kelas'=>$kelasWeb,  'tugas'=>95,'kuis'=>92,'uts'=>90,'uas'=>93],
            ['mhs'=>$putri, 'kelas'=>$kelasADS,  'tugas'=>88,'kuis'=>86,'uts'=>84,'uas'=>87],
            ['mhs'=>$putri, 'kelas'=>$kelasDM,   'tugas'=>82,'kuis'=>80,'uts'=>78,'uas'=>81],
            // Rizky
            ['mhs'=>$rizky, 'kelas'=>$kelasPBO, 'tugas'=>78,'kuis'=>75,'uts'=>72,'uas'=>76],
            ['mhs'=>$rizky, 'kelas'=>$kelasBD,  'tugas'=>80,'kuis'=>78,'uts'=>75,'uas'=>79],
            // Nurul
            ['mhs'=>$nurul, 'kelas'=>$kelasPBO, 'tugas'=>85,'kuis'=>83,'uts'=>80,'uas'=>84],
            ['mhs'=>$nurul, 'kelas'=>$kelasBD,  'tugas'=>88,'kuis'=>86,'uts'=>83,'uas'=>87],
        ];

        foreach ($nilaiData as $nd) {
            $krs = Krs::where('mahasiswa_id',$nd['mhs']->id)->where('kelas_id',$nd['kelas']->id)->first();
            if (!$krs) continue;
            $nilai = Nilai::create([
                'krs_id'       => $krs->id,
                'mahasiswa_id' => $nd['mhs']->id,
                'kelas_id'     => $nd['kelas']->id,
                'semester_id'  => $semAktif->id,
                'nilai_tugas'  => $nd['tugas'],
                'nilai_kuis'   => $nd['kuis'],
                'nilai_uts'    => $nd['uts'],
                'nilai_uas'    => $nd['uas'],
                'diinput_oleh' => $dosenBudi->id,
                'diinput_at'   => now(),
            ]);
            $nilai->hitungNilaiAkhir();
        }

        $this->command->info('✅ Akademik seeded: kelas, jadwal, KRS, pertemuan, absensi, nilai');
    }
}

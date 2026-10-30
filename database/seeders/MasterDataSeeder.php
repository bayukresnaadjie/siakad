<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\Semester;
use App\Models\Ruangan;
use App\Models\MataKuliah;
use App\Models\Kurikulum;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── FAKULTAS ──────────────────────────────────────────────────────────
        $ftik = Fakultas::create(['kode'=>'FTIK','nama'=>'Fakultas Teknologi Informasi dan Komputer','singkatan'=>'FTIK','dekan'=>'Prof. Dr. Hendra Gunawan','telpon'=>'0274-123456','email'=>'ftik@univ.ac.id']);
        $fekon = Fakultas::create(['kode'=>'FEKON','nama'=>'Fakultas Ekonomi dan Bisnis','singkatan'=>'FEB','dekan'=>'Dr. Ratna Dewi, M.M.','telpon'=>'0274-123457','email'=>'feb@univ.ac.id']);
        $fh    = Fakultas::create(['kode'=>'FH','nama'=>'Fakultas Hukum','singkatan'=>'FH','dekan'=>'Dr. Yusuf Hakim, S.H., M.H.','telpon'=>'0274-123458','email'=>'fh@univ.ac.id']);

        // ── PROGRAM STUDI ─────────────────────────────────────────────────────
        $si   = ProgramStudi::create(['fakultas_id'=>$ftik->id,'kode'=>'SI','nama'=>'Sistem Informasi','singkatan'=>'SI','jenjang'=>'S1','ketua_prodi'=>'Dr. Budi Santoso, M.Kom.','maks_sks'=>24,'total_sks_lulus'=>144]);
        $ti   = ProgramStudi::create(['fakultas_id'=>$ftik->id,'kode'=>'TI','nama'=>'Teknik Informatika','singkatan'=>'TI','jenjang'=>'S1','ketua_prodi'=>'Dr. Andi Wijaya, M.Kom.','maks_sks'=>24,'total_sks_lulus'=>144]);
        $mn   = ProgramStudi::create(['fakultas_id'=>$fekon->id,'kode'=>'MN','nama'=>'Manajemen','singkatan'=>'MN','jenjang'=>'S1','ketua_prodi'=>'Dr. Dewi Kartini, M.Si.','maks_sks'=>24,'total_sks_lulus'=>144]);

        // ── SEMESTER ──────────────────────────────────────────────────────────
        $semLalu  = Semester::create(['kode'=>'20231','nama'=>'Ganjil 2023/2024','tipe'=>'ganjil','tahun_akademik_mulai'=>2023,'tahun_akademik_selesai'=>2024,
            'tanggal_mulai'=>now()->subMonths(7)->toDateString(),'tanggal_selesai'=>now()->subMonths(2)->toDateString(),
            'tanggal_krs_mulai'=>now()->subMonths(8)->toDateString(),'tanggal_krs_selesai'=>now()->subMonths(7)->subDays(10)->toDateString(),
            'tanggal_uts'=>now()->subMonths(5)->toDateString(),'tanggal_uas'=>now()->subMonths(3)->toDateString(),'is_aktif'=>false]);
        // Semester aktif memakai tanggal relatif agar periode KRS selalu terbuka saat demo
        $semAktif = Semester::create(['kode'=>'20232','nama'=>'Genap 2023/2024','tipe'=>'genap','tahun_akademik_mulai'=>2023,'tahun_akademik_selesai'=>2024,
            'tanggal_mulai'=>now()->subDays(14)->toDateString(),'tanggal_selesai'=>now()->addMonths(5)->toDateString(),
            'tanggal_krs_mulai'=>now()->subDays(14)->toDateString(),'tanggal_krs_selesai'=>now()->addDays(21)->toDateString(),
            'tanggal_uts'=>now()->addMonths(2)->toDateString(),'tanggal_uas'=>now()->addMonths(4)->toDateString(),'is_aktif'=>true]);

        // ── RUANGAN ───────────────────────────────────────────────────────────
        $ruangan = [
            ['fakultas_id'=>$ftik->id, 'kode'=>'GD-A-101','nama'=>'Ruang A-101','gedung'=>'Gedung A','lantai'=>'1','kapasitas'=>40,'tipe'=>'kuliah'],
            ['fakultas_id'=>$ftik->id, 'kode'=>'GD-A-102','nama'=>'Ruang A-102','gedung'=>'Gedung A','lantai'=>'1','kapasitas'=>40,'tipe'=>'kuliah'],
            ['fakultas_id'=>$ftik->id, 'kode'=>'GD-A-201','nama'=>'Ruang A-201','gedung'=>'Gedung A','lantai'=>'2','kapasitas'=>50,'tipe'=>'kuliah'],
            ['fakultas_id'=>$ftik->id, 'kode'=>'LAB-01',  'nama'=>'Lab Komputer 1','gedung'=>'Gedung B','lantai'=>'1','kapasitas'=>30,'tipe'=>'lab'],
            ['fakultas_id'=>$ftik->id, 'kode'=>'LAB-02',  'nama'=>'Lab Komputer 2','gedung'=>'Gedung B','lantai'=>'1','kapasitas'=>30,'tipe'=>'lab'],
            ['fakultas_id'=>$fekon->id,'kode'=>'GD-C-101','nama'=>'Ruang C-101','gedung'=>'Gedung C','lantai'=>'1','kapasitas'=>45,'tipe'=>'kuliah'],
            ['kode'=>'AULA-01','nama'=>'Aula Utama','gedung'=>'Gedung Utama','lantai'=>'1','kapasitas'=>500,'tipe'=>'aula'],
        ];
        foreach ($ruangan as $r) Ruangan::create($r);

        // ── MATA KULIAH SI ────────────────────────────────────────────────────
        $mk_si = [
            // Semester 1
            ['kode'=>'SI001','nama'=>'Pengantar Teknologi Informasi','sks'=>3,'semester'=>1,'tipe'=>'wajib'],
            ['kode'=>'SI002','nama'=>'Algoritma dan Pemrograman','sks'=>3,'semester'=>1,'tipe'=>'wajib'],
            ['kode'=>'SI003','nama'=>'Matematika Diskrit','sks'=>3,'semester'=>1,'tipe'=>'wajib'],
            ['kode'=>'UNI001','nama'=>'Pendidikan Agama','sks'=>2,'semester'=>1,'tipe'=>'wajib'],
            ['kode'=>'UNI002','nama'=>'Bahasa Indonesia','sks'=>2,'semester'=>1,'tipe'=>'wajib'],
            // Semester 2
            ['kode'=>'SI004','nama'=>'Pemrograman Berorientasi Objek','sks'=>3,'semester'=>2,'tipe'=>'wajib'],
            ['kode'=>'SI005','nama'=>'Basis Data','sks'=>3,'semester'=>2,'tipe'=>'wajib'],
            ['kode'=>'SI006','nama'=>'Jaringan Komputer','sks'=>3,'semester'=>2,'tipe'=>'wajib'],
            ['kode'=>'SI007','nama'=>'Sistem Operasi','sks'=>3,'semester'=>2,'tipe'=>'wajib'],
            ['kode'=>'UNI003','nama'=>'Bahasa Inggris','sks'=>2,'semester'=>2,'tipe'=>'wajib'],
            // Semester 3
            ['kode'=>'SI008','nama'=>'Rekayasa Perangkat Lunak','sks'=>3,'semester'=>3,'tipe'=>'wajib'],
            ['kode'=>'SI009','nama'=>'Pemrograman Web','sks'=>3,'semester'=>3,'tipe'=>'wajib'],
            ['kode'=>'SI010','nama'=>'Analisis dan Desain Sistem','sks'=>3,'semester'=>3,'tipe'=>'wajib'],
            ['kode'=>'SI011','nama'=>'Statistika','sks'=>3,'semester'=>3,'tipe'=>'wajib'],
            // Semester 4
            ['kode'=>'SI012','nama'=>'Data Mining','sks'=>3,'semester'=>4,'tipe'=>'wajib'],
            ['kode'=>'SI013','nama'=>'Keamanan Sistem Informasi','sks'=>3,'semester'=>4,'tipe'=>'wajib'],
            ['kode'=>'SI014','nama'=>'Manajemen Proyek TI','sks'=>3,'semester'=>4,'tipe'=>'pilihan'],
            ['kode'=>'SI015','nama'=>'E-Commerce','sks'=>3,'semester'=>4,'tipe'=>'pilihan'],
        ];
        foreach ($mk_si as $mk) MataKuliah::create(array_merge($mk, ['prodi_id'=>$si->id,'sks_teori'=>$mk['sks']]));

        // ── MATA KULIAH TI ────────────────────────────────────────────────────
        $mk_ti = [
            ['kode'=>'TI001','nama'=>'Pengantar Ilmu Komputer','sks'=>3,'semester'=>1,'tipe'=>'wajib'],
            ['kode'=>'TI002','nama'=>'Pemrograman Dasar','sks'=>3,'semester'=>1,'tipe'=>'wajib'],
            ['kode'=>'TI003','nama'=>'Kalkulus','sks'=>3,'semester'=>1,'tipe'=>'wajib'],
            ['kode'=>'TI004','nama'=>'Struktur Data','sks'=>3,'semester'=>2,'tipe'=>'wajib'],
            ['kode'=>'TI005','nama'=>'Pemrograman Lanjut','sks'=>3,'semester'=>2,'tipe'=>'wajib'],
            ['kode'=>'TI006','nama'=>'Arsitektur Komputer','sks'=>3,'semester'=>2,'tipe'=>'wajib'],
            ['kode'=>'TI007','nama'=>'Kecerdasan Buatan','sks'=>3,'semester'=>3,'tipe'=>'wajib'],
            ['kode'=>'TI008','nama'=>'Machine Learning','sks'=>3,'semester'=>4,'tipe'=>'pilihan'],
        ];
        foreach ($mk_ti as $mk) MataKuliah::create(array_merge($mk, ['prodi_id'=>$ti->id,'sks_teori'=>$mk['sks']]));

        // ── KURIKULUM ─────────────────────────────────────────────────────────
        $kurSI = Kurikulum::create(['prodi_id'=>$si->id,'nama'=>'Kurikulum 2022 SI','tahun_berlaku'=>2022,'is_aktif'=>true]);

        $this->command->info('✅ Master data seeded');
    }
}

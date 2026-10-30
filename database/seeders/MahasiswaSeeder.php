<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\Dosen;
use App\Models\User;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $si = ProgramStudi::where('kode','SI')->first();
        $ti = ProgramStudi::where('kode','TI')->first();
        $mn = ProgramStudi::where('kode','MN')->first();

        $dosenBudi = Dosen::where('nidn','0101010001')->first();
        $dosenSiti = Dosen::where('nidn','0101010002')->first();
        $dosenAndi = Dosen::where('nidn','0101010003')->first();

        $mhsData = [
            ['email'=>'ahmad@siakad.test',  'nim'=>'2021SI001','nama'=>'Ahmad Fauzi',    'prodi_id'=>$si->id,'dosen_wali_id'=>$dosenBudi->id,'angkatan'=>'2021','semester_aktif'=>7,'ipk'=>3.75,'total_sks_lulus'=>120,'jenis_kelamin'=>'L','tempat_lahir'=>'Jakarta',    'tanggal_lahir'=>'2003-05-12','nomor_hp'=>'081299990001','status'=>'aktif'],
            ['email'=>'putri@siakad.test',  'nim'=>'2021SI002','nama'=>'Putri Lestari',  'prodi_id'=>$si->id,'dosen_wali_id'=>$dosenBudi->id,'angkatan'=>'2021','semester_aktif'=>7,'ipk'=>3.89,'total_sks_lulus'=>120,'jenis_kelamin'=>'P','tempat_lahir'=>'Bandung',    'tanggal_lahir'=>'2003-08-20','nomor_hp'=>'081299990002','status'=>'aktif'],
            ['email'=>'rizky@siakad.test',  'nim'=>'2022SI001','nama'=>'Rizky Pratama',  'prodi_id'=>$si->id,'dosen_wali_id'=>$dosenSiti->id,'angkatan'=>'2022','semester_aktif'=>5,'ipk'=>3.45,'total_sks_lulus'=>80, 'jenis_kelamin'=>'L','tempat_lahir'=>'Surabaya',   'tanggal_lahir'=>'2004-02-14','nomor_hp'=>'081299990003','status'=>'aktif'],
            ['email'=>'nurul@siakad.test',  'nim'=>'2022SI002','nama'=>'Nurul Hidayah',  'prodi_id'=>$si->id,'dosen_wali_id'=>$dosenSiti->id,'angkatan'=>'2022','semester_aktif'=>5,'ipk'=>3.60,'total_sks_lulus'=>80, 'jenis_kelamin'=>'P','tempat_lahir'=>'Yogyakarta',  'tanggal_lahir'=>'2004-06-08','nomor_hp'=>'081299990004','status'=>'aktif'],
            ['email'=>'fajar@siakad.test',  'nim'=>'2022TI001','nama'=>'Fajar Ramadhan', 'prodi_id'=>$ti->id,'dosen_wali_id'=>$dosenAndi->id,'angkatan'=>'2022','semester_aktif'=>5,'ipk'=>3.20,'total_sks_lulus'=>78, 'jenis_kelamin'=>'L','tempat_lahir'=>'Medan',      'tanggal_lahir'=>'2004-03-25','nomor_hp'=>'081299990005','status'=>'aktif'],
            ['email'=>'maya@siakad.test',   'nim'=>'2023SI001','nama'=>'Maya Sari',      'prodi_id'=>$si->id,'dosen_wali_id'=>$dosenBudi->id,'angkatan'=>'2023','semester_aktif'=>3,'ipk'=>3.50,'total_sks_lulus'=>40, 'jenis_kelamin'=>'P','tempat_lahir'=>'Semarang',   'tanggal_lahir'=>'2005-09-15','nomor_hp'=>'081299990006','status'=>'aktif'],
        ];

        foreach ($mhsData as $m) {
            $user = User::where('email', $m['email'])->first();
            Mahasiswa::create(array_merge(
                collect($m)->except(['email'])->toArray(),
                ['user_id' => $user->id, 'email_kampus' => $m['nim'] . '@student.univ.ac.id']
            ));
        }

        $this->command->info('✅ ' . count($mhsData) . ' mahasiswa seeded');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\User;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $ftik  = Fakultas::where('kode','FTIK')->first();
        $fekon = Fakultas::where('kode','FEKON')->first();
        $si    = ProgramStudi::where('kode','SI')->first();
        $ti    = ProgramStudi::where('kode','TI')->first();
        $mn    = ProgramStudi::where('kode','MN')->first();

        $dosenData = [
            [
                'email'=>'budi.dosen@siakad.test',
                'data'=>['fakultas_id'=>$ftik->id,'prodi_id'=>$si->id,'nidn'=>'0101010001','nama'=>'Budi Santoso','gelar_depan'=>'Dr.','gelar_belakang'=>'M.Kom.','jenis_kelamin'=>'L','tempat_lahir'=>'Jakarta','tanggal_lahir'=>'1975-01-15','pendidikan_terakhir'=>'S3','jabatan_fungsional'=>'lektor_kepala','status'=>'tetap','nomor_hp'=>'081211110001','email_kampus'=>'budi@univ.ac.id'],
            ],
            [
                'email'=>'siti.dosen@siakad.test',
                'data'=>['fakultas_id'=>$ftik->id,'prodi_id'=>$si->id,'nidn'=>'0101010002','nama'=>'Siti Rahayu','gelar_depan'=>'Dr.','gelar_belakang'=>'M.T.','jenis_kelamin'=>'P','tempat_lahir'=>'Bandung','tanggal_lahir'=>'1978-03-22','pendidikan_terakhir'=>'S3','jabatan_fungsional'=>'lektor','status'=>'tetap','nomor_hp'=>'081211110002','email_kampus'=>'siti@univ.ac.id'],
            ],
            [
                'email'=>'andi.dosen@siakad.test',
                'data'=>['fakultas_id'=>$ftik->id,'prodi_id'=>$ti->id,'nidn'=>'0101010003','nama'=>'Andi Wijaya','gelar_belakang'=>'M.Kom.','jenis_kelamin'=>'L','tempat_lahir'=>'Yogyakarta','tanggal_lahir'=>'1982-07-10','pendidikan_terakhir'=>'S2','jabatan_fungsional'=>'lektor','status'=>'tetap','nomor_hp'=>'081211110003','email_kampus'=>'andi@univ.ac.id'],
            ],
            [
                'email'=>'dewi.dosen@siakad.test',
                'data'=>['fakultas_id'=>$fekon->id,'prodi_id'=>$mn->id,'nidn'=>'0101010004','nama'=>'Dewi Kartini','gelar_belakang'=>'M.Si.','jenis_kelamin'=>'P','tempat_lahir'=>'Surabaya','tanggal_lahir'=>'1980-05-18','pendidikan_terakhir'=>'S2','jabatan_fungsional'=>'asisten_ahli','status'=>'tetap','nomor_hp'=>'081211110004','email_kampus'=>'dewi@univ.ac.id'],
            ],
            [
                'email'=>'rudi.dosen@siakad.test',
                'data'=>['fakultas_id'=>$ftik->id,'prodi_id'=>$ti->id,'nidn'=>'0101010005','nama'=>'Rudi Hermawan','gelar_depan'=>'Prof.','gelar_belakang'=>'Ph.D.','jenis_kelamin'=>'L','tempat_lahir'=>'Semarang','tanggal_lahir'=>'1965-11-30','pendidikan_terakhir'=>'S3','jabatan_fungsional'=>'guru_besar','status'=>'tetap','nomor_hp'=>'081211110005','email_kampus'=>'rudi@univ.ac.id'],
            ],
        ];

        foreach ($dosenData as $d) {
            $user = User::where('email', $d['email'])->first();
            Dosen::create(array_merge($d['data'], ['user_id' => $user->id]));
        }

        $this->command->info('✅ ' . count($dosenData) . ' dosen seeded');
    }
}

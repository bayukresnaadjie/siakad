<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Admin & Staff
            ['name'=>'Super Admin',       'email'=>'admin@siakad.test',     'role'=>'admin'],
            ['name'=>'Staf Akademik',     'email'=>'akademik@siakad.test',  'role'=>'akademik'],
            ['name'=>'Staf Keuangan',     'email'=>'keuangan@siakad.test',  'role'=>'keuangan'],

            // Dosen
            ['name'=>'Dr. Budi Santoso',      'email'=>'budi.dosen@siakad.test',   'role'=>'dosen'],
            ['name'=>'Dr. Siti Rahayu',       'email'=>'siti.dosen@siakad.test',   'role'=>'dosen'],
            ['name'=>'Andi Wijaya, M.Kom.',   'email'=>'andi.dosen@siakad.test',   'role'=>'dosen'],
            ['name'=>'Dewi Kartini, M.Si.',   'email'=>'dewi.dosen@siakad.test',   'role'=>'dosen'],
            ['name'=>'Prof. Rudi Hermawan',   'email'=>'rudi.dosen@siakad.test',   'role'=>'dosen'],

            // Mahasiswa
            ['name'=>'Ahmad Fauzi',       'email'=>'ahmad@siakad.test',     'role'=>'mahasiswa'],
            ['name'=>'Putri Lestari',     'email'=>'putri@siakad.test',     'role'=>'mahasiswa'],
            ['name'=>'Rizky Pratama',     'email'=>'rizky@siakad.test',     'role'=>'mahasiswa'],
            ['name'=>'Nurul Hidayah',     'email'=>'nurul@siakad.test',     'role'=>'mahasiswa'],
            ['name'=>'Fajar Ramadhan',    'email'=>'fajar@siakad.test',     'role'=>'mahasiswa'],
            ['name'=>'Maya Sari',         'email'=>'maya@siakad.test',      'role'=>'mahasiswa'],
        ];

        foreach ($users as $u) {
            User::create([
                'name'              => $u['name'],
                'email'             => $u['email'],
                'role'              => $u['role'],
                'aktif'             => true,
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('✅ ' . count($users) . ' users created');
    }
}

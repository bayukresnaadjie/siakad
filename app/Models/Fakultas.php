<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $table    = 'fakultas';
    protected $fillable = ['kode','nama','singkatan','dekan','telpon','email','alamat','aktif'];
    protected $casts    = ['aktif' => 'boolean'];

    public function programStudi() { return $this->hasMany(ProgramStudi::class); }
    public function dosen()        { return $this->hasMany(Dosen::class); }
    public function ruangan()      { return $this->hasMany(Ruangan::class); }

    public function getTotalMahasiswaAttribute(): int
    {
        return Mahasiswa::whereHas('prodi', fn($q) => $q->where('fakultas_id', $this->id))->count();
    }
}

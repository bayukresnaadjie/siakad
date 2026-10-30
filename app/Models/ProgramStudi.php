<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $table    = 'program_studi';
    protected $fillable = ['fakultas_id','kode','nama','singkatan','jenjang','ketua_prodi','maks_sks','total_sks_lulus','aktif'];
    protected $casts    = ['aktif' => 'boolean'];

    public function fakultas()   { return $this->belongsTo(Fakultas::class); }
    public function mahasiswa()  { return $this->hasMany(Mahasiswa::class, 'prodi_id'); }
    public function dosen()      { return $this->hasMany(Dosen::class, 'prodi_id'); }
    public function mataKuliah() { return $this->hasMany(MataKuliah::class, 'prodi_id'); }
    public function kurikulum()  { return $this->hasMany(Kurikulum::class, 'prodi_id'); }

    public function getJenjangBadgeAttribute(): array
    {
        return match($this->jenjang) {
            'S1'      => ['label'=>'S1',      'class'=>'bg-blue-100 text-blue-700'],
            'S2'      => ['label'=>'S2',      'class'=>'bg-purple-100 text-purple-700'],
            'S3'      => ['label'=>'S3',      'class'=>'bg-red-100 text-red-700'],
            'D3'      => ['label'=>'D3',      'class'=>'bg-green-100 text-green-700'],
            'D4'      => ['label'=>'D4',      'class'=>'bg-teal-100 text-teal-700'],
            'Profesi' => ['label'=>'Profesi', 'class'=>'bg-orange-100 text-orange-700'],
            default   => ['label'=>$this->jenjang,'class'=>'bg-gray-100 text-gray-600'],
        };
    }
}

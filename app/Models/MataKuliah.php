<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table    = 'mata_kuliah';
    protected $fillable = ['prodi_id','kode','nama','sks','sks_teori','sks_praktikum','semester','tipe','deskripsi','aktif'];
    protected $casts    = ['aktif'=>'boolean'];

    public function prodi()     { return $this->belongsTo(ProgramStudi::class, 'prodi_id'); }
    public function kelas()     { return $this->hasMany(Kelas::class); }
    public function prasyarat() { return $this->belongsToMany(MataKuliah::class, 'prasyarat_mk', 'mata_kuliah_id', 'prasyarat_id'); }

    public function getTipeBadgeAttribute(): array
    {
        return match($this->tipe) {
            'wajib'    => ['label'=>'Wajib',    'class'=>'bg-blue-100 text-blue-700'],
            'pilihan'  => ['label'=>'Pilihan',  'class'=>'bg-green-100 text-green-700'],
            'pratikum' => ['label'=>'Praktikum','class'=>'bg-orange-100 text-orange-700'],
            default    => ['label'=>ucfirst($this->tipe),'class'=>'bg-gray-100 text-gray-500'],
        };
    }
}

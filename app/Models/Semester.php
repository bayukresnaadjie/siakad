<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $table    = 'semester';
    protected $fillable = [
        'kode','nama','tipe',
        'tahun_akademik_mulai','tahun_akademik_selesai',
        'tanggal_mulai','tanggal_selesai',
        'tanggal_krs_mulai','tanggal_krs_selesai',
        'tanggal_uts','tanggal_uas','is_aktif',
    ];
    protected $casts = [
        'tanggal_mulai'       => 'date',
        'tanggal_selesai'     => 'date',
        'tanggal_krs_mulai'   => 'date',
        'tanggal_krs_selesai' => 'date',
        'tanggal_uts'         => 'date',
        'tanggal_uas'         => 'date',
        'is_aktif'            => 'boolean',
    ];

    public function kelas()  { return $this->hasMany(Kelas::class); }
    public function krs()    { return $this->hasMany(Krs::class); }
    public function nilai()  { return $this->hasMany(Nilai::class); }
    public function khs()    { return $this->hasMany(Khs::class); }
    public function tagihan(){ return $this->hasMany(TagihanSpp::class); }

    public function isKrsOpen(): bool
    {
        if (!$this->is_aktif) return false;
        if (!$this->tanggal_krs_mulai || !$this->tanggal_krs_selesai) return false;
        return today()->between($this->tanggal_krs_mulai, $this->tanggal_krs_selesai);
    }

    public static function aktif(): ?self
    {
        return static::where('is_aktif', true)->first();
    }

    public function getTipeLabelAttribute(): string
    {
        return match($this->tipe) {
            'ganjil'  => 'Semester Ganjil',
            'genap'   => 'Semester Genap',
            'pendek'  => 'Semester Pendek',
            default   => ucfirst($this->tipe),
        };
    }
}

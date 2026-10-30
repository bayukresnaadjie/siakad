<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dosen extends Model
{
    use SoftDeletes;

    protected $table    = 'dosen';
    protected $fillable = [
        'user_id','fakultas_id','prodi_id','nidn','nip','nama',
        'gelar_depan','gelar_belakang','jenis_kelamin',
        'tempat_lahir','tanggal_lahir','pendidikan_terakhir',
        'jabatan_fungsional','status','nomor_hp','email_kampus','aktif',
    ];
    protected $casts = ['tanggal_lahir'=>'date','aktif'=>'boolean'];

    public function user()         { return $this->belongsTo(User::class); }
    public function fakultas()     { return $this->belongsTo(Fakultas::class); }
    public function prodi()        { return $this->belongsTo(ProgramStudi::class, 'prodi_id'); }
    public function kelas()        { return $this->hasMany(Kelas::class); }
    public function mahasiswaWali(){ return $this->hasMany(Mahasiswa::class, 'dosen_wali_id'); }

    public function getNamaLengkapAttribute(): string
    {
        $nama = '';
        if ($this->gelar_depan)    $nama .= $this->gelar_depan . ' ';
        $nama .= $this->nama;
        if ($this->gelar_belakang) $nama .= ', ' . $this->gelar_belakang;
        return trim($nama);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'tetap'        => ['label'=>'Tetap',        'class'=>'bg-green-100 text-green-700'],
            'tidak_tetap'  => ['label'=>'Tidak Tetap',  'class'=>'bg-yellow-100 text-yellow-700'],
            'luar_biasa'   => ['label'=>'Luar Biasa',   'class'=>'bg-blue-100 text-blue-700'],
            default        => ['label'=>ucfirst($this->status),'class'=>'bg-gray-100 text-gray-500'],
        };
    }

    public function getJabatanLabelAttribute(): string
    {
        return match($this->jabatan_fungsional) {
            'asisten_ahli'  => 'Asisten Ahli',
            'lektor'        => 'Lektor',
            'lektor_kepala' => 'Lektor Kepala',
            'guru_besar'    => 'Guru Besar / Professor',
            default         => '-',
        };
    }

    public function getAvatarUrlAttribute(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&background=4F46E5&color=fff&size=64';
    }
}

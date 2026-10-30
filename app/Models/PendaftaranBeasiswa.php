<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PendaftaranBeasiswa extends Model {
    protected $table = 'pendaftaran_beasiswa';
    protected $fillable = ['beasiswa_id','mahasiswa_id','motivasi','dokumen_pendukung','status','catatan_admin','diproses_oleh','diproses_at'];
    protected $casts = ['diproses_at'=>'datetime'];
    public function beasiswa()   { return $this->belongsTo(Beasiswa::class); }
    public function mahasiswa()  { return $this->belongsTo(Mahasiswa::class); }
    public function diprosesOleh(){ return $this->belongsTo(User::class,'diproses_oleh'); }
    public function getStatusBadgeAttribute(): array {
        return match($this->status) {
            'diterima'     => ['label'=>'Diterima',      'class'=>'bg-green-100 text-green-700'],
            'lolos_berkas' => ['label'=>'Lolos Berkas',  'class'=>'bg-blue-100 text-blue-700'],
            'pending'      => ['label'=>'Pending',       'class'=>'bg-yellow-100 text-yellow-700'],
            'tidak_lolos'  => ['label'=>'Tidak Lolos',   'class'=>'bg-orange-100 text-orange-700'],
            'ditolak'      => ['label'=>'Ditolak',       'class'=>'bg-red-100 text-red-700'],
            default        => ['label'=>ucfirst($this->status),'class'=>'bg-gray-100 text-gray-500'],
        };
    }
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Wisuda extends Model {
    protected $table = 'wisuda';
    protected $fillable = ['nama','tanggal','lokasi','jam_mulai','kuota','tanggal_daftar_buka','tanggal_daftar_tutup','status'];
    protected $casts = ['tanggal'=>'date','tanggal_daftar_buka'=>'date','tanggal_daftar_tutup'=>'date'];
    public function pendaftaran() { return $this->hasMany(PendaftaranWisuda::class); }
    public function getStatusBadgeAttribute(): array {
        return match($this->status) {
            'buka'        => ['label'=>'Pendaftaran Buka', 'class'=>'bg-green-100 text-green-700'],
            'tutup'       => ['label'=>'Pendaftaran Tutup','class'=>'bg-red-100 text-red-700'],
            'akan_datang' => ['label'=>'Akan Datang',     'class'=>'bg-blue-100 text-blue-700'],
            'selesai'     => ['label'=>'Selesai',         'class'=>'bg-gray-100 text-gray-600'],
            default       => ['label'=>ucfirst($this->status),'class'=>'bg-gray-100 text-gray-500'],
        };
    }
    public function isOpen(): bool { return $this->status==='buka' && today()->between($this->tanggal_daftar_buka,$this->tanggal_daftar_tutup); }
}

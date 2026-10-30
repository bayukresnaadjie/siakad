<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PembayaranSpp extends Model {
    protected $table = 'pembayaran_spp';
    protected $fillable = ['tagihan_spp_id','mahasiswa_id','nomor_bayar','jumlah_bayar','metode','bukti_bayar','status','diverifikasi_oleh','diverifikasi_at'];
    protected $casts = ['jumlah_bayar'=>'decimal:2','diverifikasi_at'=>'datetime'];
    public function tagihan()         { return $this->belongsTo(TagihanSpp::class,'tagihan_spp_id'); }
    public function mahasiswa()       { return $this->belongsTo(Mahasiswa::class); }
    public function diverifikasiOleh(){ return $this->belongsTo(User::class,'diverifikasi_oleh'); }
    public function getStatusBadgeAttribute(): array {
        return match($this->status) {
            'verified' => ['label'=>'Terverifikasi','class'=>'bg-green-100 text-green-700'],
            'pending'  => ['label'=>'Menunggu',     'class'=>'bg-yellow-100 text-yellow-700'],
            'rejected' => ['label'=>'Ditolak',      'class'=>'bg-red-100 text-red-700'],
            default    => ['label'=>ucfirst($this->status),'class'=>'bg-gray-100 text-gray-500'],
        };
    }
}

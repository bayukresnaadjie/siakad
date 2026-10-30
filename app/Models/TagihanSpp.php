<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TagihanSpp extends Model {
    protected $table = 'tagihan_spp';
    protected $fillable = ['mahasiswa_id','semester_id','nomor_tagihan','jumlah','denda','diskon','total','status','jatuh_tempo'];
    protected $casts = ['jumlah'=>'decimal:2','denda'=>'decimal:2','diskon'=>'decimal:2','total'=>'decimal:2','jatuh_tempo'=>'date'];
    public function mahasiswa()  { return $this->belongsTo(Mahasiswa::class); }
    public function semester()   { return $this->belongsTo(Semester::class); }
    public function pembayaran() { return $this->hasMany(PembayaranSpp::class); }
    public function getTotalFormatAttribute(): string { return 'Rp '.number_format((float)$this->total,0,',','.'); }
    public function getStatusBadgeAttribute(): array {
        return match($this->status) {
            'lunas'       => ['label'=>'Lunas',         'class'=>'bg-green-100 text-green-700'],
            'sebagian'    => ['label'=>'Sebagian',      'class'=>'bg-yellow-100 text-yellow-700'],
            'belum_bayar' => ['label'=>'Belum Bayar',   'class'=>'bg-red-100 text-red-700'],
            default       => ['label'=>ucfirst($this->status),'class'=>'bg-gray-100 text-gray-500'],
        };
    }
    public function isTerlambat(): bool { return today()->gt($this->jatuh_tempo) && $this->status !== 'lunas'; }
}

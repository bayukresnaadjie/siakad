<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PendaftaranWisuda extends Model {
    protected $table = 'pendaftaran_wisuda';
    protected $fillable = ['wisuda_id','mahasiswa_id','judul_skripsi','pembimbing_skripsi','ipk_lulus','total_sks_lulus','predikat','status','catatan'];
    protected $casts = ['ipk_lulus'=>'decimal:2'];
    public function wisuda()    { return $this->belongsTo(Wisuda::class); }
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class); }
    public function getStatusBadgeAttribute(): array {
        return match($this->status) {
            'diverifikasi' => ['label'=>'Diverifikasi','class'=>'bg-green-100 text-green-700'],
            'pending'      => ['label'=>'Pending',     'class'=>'bg-yellow-100 text-yellow-700'],
            'ditolak'      => ['label'=>'Ditolak',     'class'=>'bg-red-100 text-red-700'],
            default        => ['label'=>ucfirst($this->status),'class'=>'bg-gray-100 text-gray-500'],
        };
    }
    public function getPredikatLabelAttribute(): string {
        return match($this->predikat) {
            'cumlaude'          => 'Cumlaude',
            'sangat_memuaskan'  => 'Sangat Memuaskan',
            'memuaskan'         => 'Memuaskan',
            default             => '-',
        };
    }
}

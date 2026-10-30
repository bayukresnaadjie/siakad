<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Beasiswa extends Model {
    protected $table = 'beasiswa';
    protected $fillable = ['nama','deskripsi','penyelenggara','nominal','kuota','min_ipk','maks_semester','tanggal_buka','tanggal_tutup','status'];
    protected $casts = ['nominal'=>'decimal:2','min_ipk'=>'decimal:2','tanggal_buka'=>'date','tanggal_tutup'=>'date'];
    public function pendaftaran() { return $this->hasMany(PendaftaranBeasiswa::class); }
    public function getNominalFormatAttribute(): string { return 'Rp '.number_format((float)$this->nominal,0,',','.'); }
    public function getStatusBadgeAttribute(): array {
        return match($this->status) {
            'buka'    => ['label'=>'Buka',    'class'=>'bg-green-100 text-green-700'],
            'tutup'   => ['label'=>'Tutup',   'class'=>'bg-red-100 text-red-700'],
            'selesai' => ['label'=>'Selesai', 'class'=>'bg-gray-100 text-gray-600'],
            default   => ['label'=>'Draft',   'class'=>'bg-yellow-100 text-yellow-700'],
        };
    }
    public function isOpen(): bool { return $this->status==='buka' && today()->between($this->tanggal_buka,$this->tanggal_tutup); }
}

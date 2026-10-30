<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Krs extends Model {
    protected $table = 'krs';
    protected $fillable = ['mahasiswa_id','kelas_id','semester_id','status','disetujui_oleh','disetujui_at','catatan'];
    protected $casts = ['disetujui_at'=>'datetime'];
    public function mahasiswa()    { return $this->belongsTo(Mahasiswa::class); }
    public function kelas()        { return $this->belongsTo(Kelas::class); }
    public function semester()     { return $this->belongsTo(Semester::class); }
    public function disetujuiOleh(){ return $this->belongsTo(Dosen::class,'disetujui_oleh'); }
    public function nilai()        { return $this->hasOne(Nilai::class); }
    public function getStatusBadgeAttribute(): array {
        return match($this->status) {
            'disetujui' => ['label'=>'Disetujui','class'=>'bg-green-100 text-green-700'],
            'diajukan'  => ['label'=>'Diajukan', 'class'=>'bg-yellow-100 text-yellow-700'],
            'ditolak'   => ['label'=>'Ditolak',  'class'=>'bg-red-100 text-red-700'],
            default     => ['label'=>'Draft',     'class'=>'bg-gray-100 text-gray-500'],
        };
    }
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pertemuan extends Model {
    protected $table = 'pertemuan';
    protected $fillable = ['kelas_id','pertemuan_ke','tanggal','materi','tipe'];
    protected $casts = ['tanggal'=>'date'];
    public function kelas()   { return $this->belongsTo(Kelas::class); }
    public function absensi() { return $this->hasMany(Absensi::class); }
    public function getTipeBadgeAttribute(): array {
        return match($this->tipe) {
            'kuliah'    => ['label'=>'Kuliah',    'class'=>'bg-blue-100 text-blue-700'],
            'uts'       => ['label'=>'UTS',       'class'=>'bg-orange-100 text-orange-700'],
            'uas'       => ['label'=>'UAS',       'class'=>'bg-red-100 text-red-700'],
            'praktikum' => ['label'=>'Praktikum', 'class'=>'bg-green-100 text-green-700'],
            'kuis'      => ['label'=>'Kuis',      'class'=>'bg-purple-100 text-purple-700'],
            default     => ['label'=>ucfirst($this->tipe),'class'=>'bg-gray-100 text-gray-500'],
        };
    }
}

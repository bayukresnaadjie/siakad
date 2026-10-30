<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Absensi extends Model {
    protected $table = 'absensi';
    protected $fillable = ['pertemuan_id','mahasiswa_id','status','keterangan'];
    public function pertemuan()  { return $this->belongsTo(Pertemuan::class); }
    public function mahasiswa()  { return $this->belongsTo(Mahasiswa::class); }
    public function getStatusBadgeAttribute(): array {
        return match($this->status) {
            'hadir' => ['label'=>'Hadir','class'=>'bg-green-100 text-green-700','icon'=>'✓'],
            'izin'  => ['label'=>'Izin', 'class'=>'bg-blue-100 text-blue-700',  'icon'=>'I'],
            'sakit' => ['label'=>'Sakit','class'=>'bg-yellow-100 text-yellow-700','icon'=>'S'],
            'alpha' => ['label'=>'Alpha','class'=>'bg-red-100 text-red-700',    'icon'=>'A'],
            default => ['label'=>ucfirst($this->status),'class'=>'bg-gray-100 text-gray-500','icon'=>'-'],
        };
    }
}

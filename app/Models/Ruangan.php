<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Ruangan extends Model {
    protected $table = 'ruangan';
    protected $fillable = ['fakultas_id','kode','nama','gedung','lantai','kapasitas','tipe','aktif'];
    protected $casts = ['aktif'=>'boolean'];
    public function fakultas()  { return $this->belongsTo(Fakultas::class); }
    public function jadwal()    { return $this->hasMany(JadwalKelas::class); }
    public function getTipeBadgeAttribute(): array {
        return match($this->tipe) {
            'kuliah'  => ['label'=>'Kuliah',  'class'=>'bg-blue-100 text-blue-700'],
            'lab'     => ['label'=>'Lab',     'class'=>'bg-green-100 text-green-700'],
            'seminar' => ['label'=>'Seminar', 'class'=>'bg-purple-100 text-purple-700'],
            'aula'    => ['label'=>'Aula',    'class'=>'bg-orange-100 text-orange-700'],
            default   => ['label'=>ucfirst($this->tipe),'class'=>'bg-gray-100 text-gray-600'],
        };
    }
}

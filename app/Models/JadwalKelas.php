<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class JadwalKelas extends Model {
    protected $table = 'jadwal_kelas';
    protected $fillable = ['kelas_id','ruangan_id','hari','jam_mulai','jam_selesai'];
    public function kelas()   { return $this->belongsTo(Kelas::class); }
    public function ruangan() { return $this->belongsTo(Ruangan::class); }
    public function getHariLabelAttribute(): string {
        return match($this->hari) {
            'senin'=>'Senin','selasa'=>'Selasa','rabu'=>'Rabu',
            'kamis'=>'Kamis','jumat'=>'Jumat','sabtu'=>'Sabtu',
            default=>ucfirst($this->hari),
        };
    }
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Khs extends Model {
    protected $table = 'khs';
    protected $fillable = ['mahasiswa_id','semester_id','total_sks_ambil','total_sks_lulus','ip_semester','ipk'];
    protected $casts = ['ip_semester'=>'decimal:2','ipk'=>'decimal:2'];
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class); }
    public function semester()  { return $this->belongsTo(Semester::class); }
    public function getPredikatAttribute(): string {
        return match(true) {
            $this->ip_semester >= 3.51 => 'Cumlaude',
            $this->ip_semester >= 2.76 => 'Sangat Memuaskan',
            $this->ip_semester >= 2.00 => 'Memuaskan',
            default => 'Kurang Memuaskan',
        };
    }
}

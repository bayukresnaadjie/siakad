<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Kelas extends Model {
    protected $table = 'kelas';
    protected $fillable = ['mata_kuliah_id','semester_id','dosen_id','dosen2_id','nama_kelas','kapasitas','jumlah_pertemuan'];
    public function mataKuliah() { return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id'); }
    public function semester()   { return $this->belongsTo(Semester::class); }
    public function dosen()      { return $this->belongsTo(Dosen::class); }
    public function dosen2()     { return $this->belongsTo(Dosen::class, 'dosen2_id'); }
    public function jadwal()     { return $this->hasMany(JadwalKelas::class); }
    public function krs()        { return $this->hasMany(Krs::class); }
    public function nilai()      { return $this->hasMany(Nilai::class); }
    public function pertemuan()  { return $this->hasMany(Pertemuan::class)->orderBy('pertemuan_ke'); }
    public function getNamaLengkapAttribute(): string { return $this->mataKuliah->nama . ' - Kelas ' . $this->nama_kelas; }
    public function getJumlahMahasiswaAttribute(): int { return $this->krs()->where('status','disetujui')->count(); }
}

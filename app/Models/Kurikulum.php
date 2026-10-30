<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Kurikulum extends Model {
    protected $table = 'kurikulum';
    protected $fillable = ['prodi_id','nama','tahun_berlaku','is_aktif'];
    protected $casts = ['is_aktif'=>'boolean'];
    public function prodi()      { return $this->belongsTo(ProgramStudi::class, 'prodi_id'); }
    public function mataKuliah() { return $this->belongsToMany(MataKuliah::class, 'kurikulum_mk')->withPivot('semester'); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use SoftDeletes;

    protected $table    = 'mahasiswa';
    protected $fillable = [
        'user_id','prodi_id','dosen_wali_id','nim','nama',
        'jenis_kelamin','tempat_lahir','tanggal_lahir',
        'nomor_hp','email_kampus','alamat','foto',
        'angkatan','semester_aktif','ipk','total_sks_lulus','status','aktif',
    ];
    protected $casts = [
        'tanggal_lahir' => 'date',
        'ipk'           => 'decimal:2',
        'aktif'         => 'boolean',
    ];

    public function user()       { return $this->belongsTo(User::class); }
    public function prodi()      { return $this->belongsTo(ProgramStudi::class, 'prodi_id'); }
    public function dosenWali()  { return $this->belongsTo(Dosen::class, 'dosen_wali_id'); }
    public function krs()        { return $this->hasMany(Krs::class); }
    public function nilai()      { return $this->hasMany(Nilai::class); }
    public function khs()        { return $this->hasMany(Khs::class)->orderByDesc('created_at'); }
    public function tagihan()    { return $this->hasMany(TagihanSpp::class); }
    public function beasiswa()   { return $this->hasMany(PendaftaranBeasiswa::class); }
    public function absensi()    { return $this->hasMany(Absensi::class); }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'aktif'              => ['label'=>'Aktif',              'class'=>'bg-green-100 text-green-700'],
            'cuti'               => ['label'=>'Cuti',               'class'=>'bg-yellow-100 text-yellow-700'],
            'drop_out'           => ['label'=>'Drop Out',           'class'=>'bg-red-100 text-red-700'],
            'lulus'              => ['label'=>'Lulus',              'class'=>'bg-blue-100 text-blue-700'],
            'mengundurkan_diri'  => ['label'=>'Mengundurkan Diri',  'class'=>'bg-gray-100 text-gray-600'],
            default              => ['label'=>ucfirst($this->status),'class'=>'bg-gray-100 text-gray-500'],
        };
    }

    public function getPredikatAttribute(): string
    {
        return match(true) {
            $this->ipk >= 3.51 => 'Cumlaude',
            $this->ipk >= 2.76 => 'Sangat Memuaskan',
            $this->ipk >= 2.00 => 'Memuaskan',
            default            => 'Kurang Memuaskan',
        };
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&background=6366F1&color=fff&size=64';
    }

    public function krsSemesterIni(): ?Krs
    {
        $semester = Semester::aktif();
        if (!$semester) return null;
        return $this->krs()->where('semester_id', $semester->id)->first();
    }

    public function totalSksSemesterIni(): int
    {
        $semester = Semester::aktif();
        if (!$semester) return 0;
        return $this->krs()
            ->where('semester_id', $semester->id)
            ->where('status', 'disetujui')
            ->join('kelas', 'krs.kelas_id', '=', 'kelas.id')
            ->join('mata_kuliah', 'kelas.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->sum('mata_kuliah.sks');
    }

    public function hitungIpk(): void
    {
        $nilaiLulus = $this->nilai()
            ->where('lulus', true)
            ->whereNotNull('bobot')
            ->join('kelas', 'nilai.kelas_id', '=', 'kelas.id')
            ->join('mata_kuliah', 'kelas.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->selectRaw('SUM(nilai.bobot * mata_kuliah.sks) as total_mutu, SUM(mata_kuliah.sks) as total_sks')
            ->first();

        $totalSks  = (int) ($nilaiLulus->total_sks ?? 0);
        $totalMutu = (float) ($nilaiLulus->total_mutu ?? 0);
        $ipk       = $totalSks > 0 ? round($totalMutu / $totalSks, 2) : 0;

        $this->update(['ipk' => $ipk, 'total_sks_lulus' => $totalSks]);
    }
}

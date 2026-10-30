<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table    = 'nilai';
    protected $fillable = [
        'krs_id','mahasiswa_id','kelas_id','semester_id',
        'nilai_tugas','nilai_kuis','nilai_uts','nilai_uas',
        'nilai_akhir','huruf','bobot','lulus',
        'diinput_oleh','diinput_at','dikunci',
    ];
    protected $casts = [
        'nilai_tugas'  => 'decimal:2',
        'nilai_kuis'   => 'decimal:2',
        'nilai_uts'    => 'decimal:2',
        'nilai_uas'    => 'decimal:2',
        'nilai_akhir'  => 'decimal:2',
        'bobot'        => 'decimal:2',
        'lulus'        => 'boolean',
        'dikunci'      => 'boolean',
        'diinput_at'   => 'datetime',
    ];

    public function krs()          { return $this->belongsTo(Krs::class); }
    public function mahasiswa()    { return $this->belongsTo(Mahasiswa::class); }
    public function kelas()        { return $this->belongsTo(Kelas::class); }
    public function semester()     { return $this->belongsTo(Semester::class); }
    public function diinputOleh()  { return $this->belongsTo(Dosen::class, 'diinput_oleh'); }

    // Hitung nilai akhir: tugas 20%, kuis 10%, UTS 30%, UAS 40%
    public function hitungNilaiAkhir(): void
    {
        $tugas = (float)($this->nilai_tugas ?? 0);
        $kuis  = (float)($this->nilai_kuis  ?? 0);
        $uts   = (float)($this->nilai_uts   ?? 0);
        $uas   = (float)($this->nilai_uas   ?? 0);

        $akhir = ($tugas * 0.20) + ($kuis * 0.10) + ($uts * 0.30) + ($uas * 0.40);
        $akhir = round($akhir, 2);

        [$huruf, $bobot, $lulus] = $this->konversiNilai($akhir);

        $this->update([
            'nilai_akhir' => $akhir,
            'huruf'       => $huruf,
            'bobot'       => $bobot,
            'lulus'       => $lulus,
            'diinput_at'  => now(),
        ]);
    }

    private function konversiNilai(float $nilai): array
    {
        return match(true) {
            $nilai >= 85 => ['A',  4.00, true],
            $nilai >= 80 => ['A-', 3.70, true],
            $nilai >= 75 => ['B+', 3.30, true],
            $nilai >= 70 => ['B',  3.00, true],
            $nilai >= 65 => ['B-', 2.70, true],
            $nilai >= 60 => ['C+', 2.30, true],
            $nilai >= 55 => ['C',  2.00, true],
            $nilai >= 50 => ['C-', 1.70, false],
            $nilai >= 40 => ['D',  1.00, false],
            default      => ['E',  0.00, false],
        };
    }

    public function getHurufBadgeAttribute(): array
    {
        return match($this->huruf) {
            'A','A-'      => ['class'=>'bg-green-100 text-green-700'],
            'B+','B','B-' => ['class'=>'bg-blue-100 text-blue-700'],
            'C+','C'      => ['class'=>'bg-yellow-100 text-yellow-700'],
            'C-','D'      => ['class'=>'bg-orange-100 text-orange-700'],
            'E'           => ['class'=>'bg-red-100 text-red-700'],
            default       => ['class'=>'bg-gray-100 text-gray-500'],
        };
    }
}

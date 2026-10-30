<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Pertemuan kelas
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->integer('pertemuan_ke');
            $table->date('tanggal');
            $table->string('materi')->nullable();
            $table->enum('tipe', ['kuliah','uts','uas','praktikum','kuis'])->default('kuliah');
            $table->timestamps();
            $table->unique(['kelas_id','pertemuan_ke']);
        });

        // Absensi per pertemuan
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->constrained('pertemuan')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->enum('status', ['hadir','izin','sakit','alpha'])->default('alpha');
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['pertemuan_id','mahasiswa_id']);
        });

        // Nilai per kelas per mahasiswa
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('krs_id')->unique()->constrained('krs')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->decimal('nilai_tugas',  5, 2)->nullable();  // bobot 20%
            $table->decimal('nilai_kuis',   5, 2)->nullable();  // bobot 10%
            $table->decimal('nilai_uts',    5, 2)->nullable();  // bobot 30%
            $table->decimal('nilai_uas',    5, 2)->nullable();  // bobot 40%
            $table->decimal('nilai_akhir',  5, 2)->nullable();  // hasil hitung
            $table->string('huruf', 3)->nullable();             // A, A-, B+, B, dll
            $table->decimal('bobot', 3, 2)->nullable();         // 4.0, 3.7, 3.3, dll
            $table->boolean('lulus')->default(false);
            $table->foreignId('diinput_oleh')->nullable()->constrained('dosen')->nullOnDelete();
            $table->timestamp('diinput_at')->nullable();
            $table->boolean('dikunci')->default(false);         // nilai terkunci = tidak bisa diubah
            $table->timestamps();
        });

        // Kartu Hasil Studi per semester
        Schema::create('khs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->integer('total_sks_ambil')->default(0);
            $table->integer('total_sks_lulus')->default(0);
            $table->decimal('ip_semester', 4, 2)->default(0.00);
            $table->decimal('ipk', 4, 2)->default(0.00);
            $table->timestamps();
            $table->unique(['mahasiswa_id','semester_id']);
        });

        // Tagihan SPP
        Schema::create('tagihan_spp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->string('nomor_tagihan', 30)->unique();
            $table->decimal('jumlah', 15, 2);
            $table->decimal('denda', 15, 2)->default(0);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->enum('status', ['belum_bayar','sebagian','lunas'])->default('belum_bayar');
            $table->date('jatuh_tempo');
            $table->timestamps();
            $table->unique(['mahasiswa_id','semester_id']);
        });

        Schema::create('pembayaran_spp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_spp_id')->constrained('tagihan_spp')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->string('nomor_bayar', 30)->unique();
            $table->decimal('jumlah_bayar', 15, 2);
            $table->enum('metode', ['transfer','cash','virtual_account'])->default('transfer');
            $table->string('bukti_bayar')->nullable();
            $table->enum('status', ['pending','verified','rejected'])->default('pending');
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diverifikasi_at')->nullable();
            $table->timestamps();
        });

        // Beasiswa
        Schema::create('beasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('penyelenggara')->nullable();
            $table->decimal('nominal', 15, 2)->default(0);
            $table->integer('kuota')->default(10);
            $table->decimal('min_ipk', 3, 2)->default(3.00);
            $table->integer('maks_semester')->nullable();
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->enum('status', ['draft','buka','tutup','selesai'])->default('draft');
            $table->timestamps();
        });

        Schema::create('pendaftaran_beasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beasiswa_id')->constrained('beasiswa')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->text('motivasi')->nullable();
            $table->string('dokumen_pendukung')->nullable();
            $table->enum('status', ['pending','lolos_berkas','tidak_lolos','diterima','ditolak'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diproses_at')->nullable();
            $table->timestamps();
            $table->unique(['beasiswa_id','mahasiswa_id']);
        });

        // Wisuda
        Schema::create('wisuda', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tanggal');
            $table->string('lokasi')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->integer('kuota')->nullable();
            $table->date('tanggal_daftar_buka');
            $table->date('tanggal_daftar_tutup');
            $table->enum('status', ['akan_datang','buka','tutup','selesai'])->default('akan_datang');
            $table->timestamps();
        });

        Schema::create('pendaftaran_wisuda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wisuda_id')->constrained('wisuda')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->string('judul_skripsi')->nullable();
            $table->string('pembimbing_skripsi')->nullable();
            $table->decimal('ipk_lulus', 4, 2)->nullable();
            $table->integer('total_sks_lulus')->nullable();
            $table->enum('predikat', ['memuaskan','sangat_memuaskan','cumlaude'])->nullable();
            $table->enum('status', ['pending','diverifikasi','ditolak'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->unique(['wisuda_id','mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_wisuda');
        Schema::dropIfExists('wisuda');
        Schema::dropIfExists('pendaftaran_beasiswa');
        Schema::dropIfExists('beasiswa');
        Schema::dropIfExists('pembayaran_spp');
        Schema::dropIfExists('tagihan_spp');
        Schema::dropIfExists('khs');
        Schema::dropIfExists('nilai');
        Schema::dropIfExists('absensi');
        Schema::dropIfExists('pertemuan');
    }
};

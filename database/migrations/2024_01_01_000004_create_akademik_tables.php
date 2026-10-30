<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Mata Kuliah
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained('program_studi')->cascadeOnDelete();
            $table->string('kode', 15)->unique();
            $table->string('nama');
            $table->integer('sks')->default(3);
            $table->integer('sks_teori')->default(3);
            $table->integer('sks_praktikum')->default(0);
            $table->integer('semester')->default(1);           // semester ke berapa diambil
            $table->enum('tipe', ['wajib','pilihan','pratikum'])->default('wajib');
            $table->text('deskripsi')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // Prasyarat mata kuliah
        Schema::create('prasyarat_mk', function (Blueprint $table) {
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->foreignId('prasyarat_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->primary(['mata_kuliah_id','prasyarat_id']);
        });

        // Kurikulum per prodi
        Schema::create('kurikulum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained('program_studi')->cascadeOnDelete();
            $table->string('nama');
            $table->year('tahun_berlaku');
            $table->boolean('is_aktif')->default(false);
            $table->timestamps();
        });

        Schema::create('kurikulum_mk', function (Blueprint $table) {
            $table->foreignId('kurikulum_id')->constrained('kurikulum')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->integer('semester')->default(1);
            $table->primary(['kurikulum_id','mata_kuliah_id']);
        });

        // Kelas per mata kuliah per semester
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('dosen');
            $table->foreignId('dosen2_id')->nullable()->constrained('dosen')->nullOnDelete(); // team teaching
            $table->string('nama_kelas', 5);                   // A, B, C, dll
            $table->integer('kapasitas')->default(40);
            $table->integer('jumlah_pertemuan')->default(16);
            $table->timestamps();
        });

        // Jadwal kelas
        Schema::create('jadwal_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangan')->nullOnDelete();
            $table->enum('hari', ['senin','selasa','rabu','kamis','jumat','sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });

        // KRS - Kartu Rencana Studi
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->enum('status', ['draft','diajukan','disetujui','ditolak'])->default('draft');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('dosen')->nullOnDelete();
            $table->timestamp('disetujui_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->unique(['mahasiswa_id','kelas_id','semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs');
        Schema::dropIfExists('jadwal_kelas');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('kurikulum_mk');
        Schema::dropIfExists('kurikulum');
        Schema::dropIfExists('prasyarat_mk');
        Schema::dropIfExists('mata_kuliah');
    }
};

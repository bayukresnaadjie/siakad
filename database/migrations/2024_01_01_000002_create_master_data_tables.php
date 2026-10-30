<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fakultas', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama');
            $table->string('singkatan', 10)->nullable();
            $table->string('dekan')->nullable();
            $table->string('telpon', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('program_studi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fakultas_id')->constrained('fakultas')->cascadeOnDelete();
            $table->string('kode', 10)->unique();
            $table->string('nama');
            $table->string('singkatan', 10)->nullable();
            $table->enum('jenjang', ['D3','D4','S1','S2','S3','Profesi'])->default('S1');
            $table->string('ketua_prodi')->nullable();
            $table->integer('maks_sks')->default(24);          // maks SKS per semester
            $table->integer('total_sks_lulus')->default(144);  // total SKS lulus
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('semester', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();              // e.g. 20241
            $table->string('nama');                            // e.g. Genap 2023/2024
            $table->enum('tipe', ['ganjil','genap','pendek'])->default('ganjil');
            $table->year('tahun_akademik_mulai');
            $table->year('tahun_akademik_selesai');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->date('tanggal_krs_mulai')->nullable();
            $table->date('tanggal_krs_selesai')->nullable();
            $table->date('tanggal_uts')->nullable();
            $table->date('tanggal_uas')->nullable();
            $table->boolean('is_aktif')->default(false);       // hanya 1 aktif
            $table->timestamps();
        });

        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fakultas_id')->nullable()->constrained('fakultas')->nullOnDelete();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->string('gedung')->nullable();
            $table->string('lantai', 5)->nullable();
            $table->integer('kapasitas')->default(40);
            $table->enum('tipe', ['kuliah','lab','seminar','aula'])->default('kuliah');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangan');
        Schema::dropIfExists('semester');
        Schema::dropIfExists('program_studi');
        Schema::dropIfExists('fakultas');
    }
};

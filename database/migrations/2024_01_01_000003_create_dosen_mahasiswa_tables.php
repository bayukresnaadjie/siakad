<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('fakultas_id')->constrained('fakultas');
            $table->foreignId('prodi_id')->nullable()->constrained('program_studi')->nullOnDelete();
            $table->string('nidn', 20)->unique();
            $table->string('nip', 30)->nullable()->unique();
            $table->string('nama');
            $table->string('gelar_depan', 20)->nullable();            // Dr., Prof., Ir., dr., drg., dll
            $table->string('gelar_belakang')->nullable();       // S.Kom., M.Kom., dll
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('pendidikan_terakhir', ['S1', 'S2', 'S3'])->default('S2');
            $table->enum('jabatan_fungsional', ['asisten_ahli', 'lektor', 'lektor_kepala', 'guru_besar'])->nullable();
            $table->enum('status', ['tetap', 'tidak_tetap', 'luar_biasa'])->default('tetap');
            $table->string('nomor_hp', 20)->nullable();
            $table->string('email_kampus')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('prodi_id')->constrained('program_studi');
            $table->foreignId('dosen_wali_id')->nullable()->constrained('dosen')->nullOnDelete();
            $table->string('nim', 20)->unique();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nomor_hp', 20)->nullable();
            $table->string('email_kampus')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->string('angkatan', 4);                     // e.g. 2021
            $table->integer('semester_aktif')->default(1);
            $table->decimal('ipk', 4, 2)->default(0.00);
            $table->integer('total_sks_lulus')->default(0);
            $table->enum('status', ['aktif', 'cuti', 'drop_out', 'lulus', 'mengundurkan_diri'])->default('aktif');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('dosen');
    }
};

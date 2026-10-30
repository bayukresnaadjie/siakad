<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin','akademik','keuangan','dosen','mahasiswa'])->default('mahasiswa')->after('email');
            $table->string('avatar')->nullable()->after('role');
            $table->boolean('aktif')->default(true)->after('avatar');
            $table->timestamp('last_login')->nullable()->after('aktif');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role','avatar','aktif','last_login']);
        });
    }
};

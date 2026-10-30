<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController, KrsController, NilaiController, AbsensiController,
    JadwalController, SppController, BeasiswaController, WisudaController,
    TranskripController, MasterController, LaporanController
};

// ─── AUTH ─────────────────────────────────────────────────────────────────────
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate(['email'=>'required|email','password'=>'required']);
    if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        auth()->user()->update(['last_login' => now()]);
        return redirect()->intended(route('dashboard'));
    }
    return back()->withErrors(['email'=>'Email atau password salah.'])->onlyInput('email');
})->name('login.post');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// ─── AUTHENTICATED ────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Jadwal
    Route::get('/jadwal',                  [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal',                 [JadwalController::class, 'store'])->name('jadwal.store');
    Route::post('/jadwal/cek-bentrok',     [JadwalController::class, 'cekBentrok'])->name('jadwal.cek-bentrok');

    // KRS (Mahasiswa)
    Route::get('/krs',                     [KrsController::class, 'index'])->name('krs.index');
    Route::post('/krs',                    [KrsController::class, 'store'])->name('krs.store');
    Route::post('/krs/ajukan',             [KrsController::class, 'ajukan'])->name('krs.ajukan');
    Route::delete('/krs/{krs}',            [KrsController::class, 'destroy'])->name('krs.destroy');
    Route::patch('/krs/{krs}/approve',     [KrsController::class, 'approveKrs'])->name('krs.approve');

    // Nilai
    Route::get('/nilai',                   [NilaiController::class, 'mahasiswa'])->name('nilai.mahasiswa');
    Route::get('/nilai/kelas/{kelas}',     [NilaiController::class, 'kelas'])->name('nilai.kelas');
    Route::put('/nilai/kelas/{kelas}',     [NilaiController::class, 'update'])->name('nilai.update');
    Route::patch('/nilai/kelas/{kelas}/kunci', [NilaiController::class, 'kunci'])->name('nilai.kunci');

    // Absensi
    Route::get('/absensi/kelas/{kelas}',                         [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/kelas/{kelas}/pertemuan/{pertemuanKe}', [AbsensiController::class, 'pertemuan'])->name('absensi.pertemuan');
    Route::post('/absensi/kelas/{kelas}/pertemuan/{pertemuan}',  [AbsensiController::class, 'store'])->name('absensi.store');

    // Transkrip & KHS
    Route::get('/transkrip',               [TranskripController::class, 'show'])->name('transkrip.show');
    Route::get('/transkrip/{mahasiswa}',   [TranskripController::class, 'show'])->name('transkrip.mahasiswa');
    Route::get('/khs/{semester}',          [TranskripController::class, 'khs'])->name('khs.show');

    // SPP
    Route::get('/spp',                           [SppController::class, 'index'])->name('spp.index');
    Route::post('/spp/{tagihan}/bayar',          [SppController::class, 'bayar'])->name('spp.bayar');
    Route::patch('/spp/pembayaran/{pembayaran}', [SppController::class, 'verifikasi'])->name('spp.verifikasi');
    Route::post('/spp/generate/{semester}',      [SppController::class, 'generate'])->name('spp.generate');

    // Beasiswa
    Route::get('/beasiswa',                          [BeasiswaController::class, 'index'])->name('beasiswa.index');
    Route::post('/beasiswa',                         [BeasiswaController::class, 'store'])->name('beasiswa.store');
    Route::get('/beasiswa/{beasiswa}',               [BeasiswaController::class, 'show'])->name('beasiswa.show');
    Route::post('/beasiswa/{beasiswa}/daftar',       [BeasiswaController::class, 'daftar'])->name('beasiswa.daftar');
    Route::patch('/beasiswa/pendaftaran/{pendaftaran}', [BeasiswaController::class, 'proses'])->name('beasiswa.proses');

    // Wisuda
    Route::get('/wisuda',                            [WisudaController::class, 'index'])->name('wisuda.index');
    Route::post('/wisuda',                           [WisudaController::class, 'store'])->name('wisuda.store');
    Route::get('/wisuda/{wisuda}',                   [WisudaController::class, 'show'])->name('wisuda.show');
    Route::post('/wisuda/{wisuda}/daftar',           [WisudaController::class, 'daftar'])->name('wisuda.daftar');
    Route::patch('/wisuda/pendaftaran/{pendaftaran}',[WisudaController::class, 'verifikasi'])->name('wisuda.verifikasi');

    // Master Data (Admin/Akademik)
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/mahasiswa',                [MasterController::class, 'mahasiswa'])->name('mahasiswa');
        Route::get('/mahasiswa/{mahasiswa}',    [MasterController::class, 'mahasiswaShow'])->name('mahasiswa.show');
        Route::get('/dosen',                    [MasterController::class, 'dosen'])->name('dosen');
        Route::get('/mata-kuliah',              [MasterController::class, 'mataKuliah'])->name('mata-kuliah');
        Route::get('/fakultas',                 [MasterController::class, 'fakultas'])->name('fakultas');
        Route::get('/semester',                 [MasterController::class, 'semester'])->name('semester');
        Route::patch('/semester/{semester}/aktifkan', [MasterController::class, 'aktifkanSemester'])->name('semester.aktifkan');
    });

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

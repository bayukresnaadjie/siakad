# 🎓 SIAKAD — Sistem Informasi Akademik Universitas

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind-CDN-06B6D4?style=flat&logo=tailwindcss&logoColor=white)

Sistem informasi akademik berbasis web untuk mengelola siklus akademik mahasiswa dari KRS, jadwal, absensi, penilaian, KHS dan transkrip, sampai SPP, beasiswa, dan wisuda. Mendukung 5 role dengan dashboard dan menu yang berbeda.

---

## ✨ Fitur Utama

### 📝 KRS Online (Mahasiswa)
- Pilih kelas dari mata kuliah prodi sendiri
- Validasi otomatis: **periode KRS**, **kapasitas kelas**, dan **batas maksimal SKS** per prodi
- Alur status: `draft → diajukan → disetujui / ditolak`
- Persetujuan oleh **dosen wali** (atau akademik/admin) langsung dari dashboard

### 📅 Jadwal
- Grid jadwal mingguan untuk mahasiswa dan dosen
- Admin/akademik mengatur jadwal per kelas, dengan **pengecekan bentrok ruangan** (AJAX)

### ✅ Absensi
- Input kehadiran per pertemuan (hadir / izin / sakit / alpha)
- Rekap kehadiran per mahasiswa dengan persentase, merah jika di bawah 75%

### 📊 Penilaian
- Dosen input nilai per komponen, dengan **preview nilai akhir live** di browser
- Bobot: **Tugas 20% · Kuis 10% · UTS 30% · UAS 40%**
- Konversi otomatis ke huruf dan bobot mutu, IPK mahasiswa dihitung ulang
- Akademik dapat **mengunci nilai**, lalu **KHS digenerate otomatis**

| Nilai | Huruf | Bobot | Lulus |
|---|---|---|---|
| ≥ 85 | A | 4.00 | ✓ |
| ≥ 80 | A- | 3.70 | ✓ |
| ≥ 75 | B+ | 3.30 | ✓ |
| ≥ 70 | B | 3.00 | ✓ |
| ≥ 65 | B- | 2.70 | ✓ |
| ≥ 60 | C+ | 2.30 | ✓ |
| ≥ 55 | C | 2.00 | ✓ |
| ≥ 50 | C- | 1.70 | ✗ |
| ≥ 40 | D | 1.00 | ✗ |
| < 40 | E | 0.00 | ✗ |

### 🎓 KHS & Transkrip
- Kartu Hasil Studi per semester (IP semester, IPK)
- Transkrip lengkap per semester dengan predikat kelulusan
- Cetak atau simpan PDF lewat fitur print browser (tampilan sudah dibuat print-friendly)

### 💳 SPP
- Generate tagihan massal untuk mahasiswa aktif
- Mahasiswa upload bukti bayar, bagian keuangan memverifikasi
- Status tagihan otomatis: belum bayar → sebagian → lunas

### 🏅 Beasiswa
- Keuangan membuat program beasiswa (nominal, kuota, minimal IPK, periode)
- Mahasiswa mendaftar dengan validasi IPK dan motivasi minimal 100 karakter
- Proses seleksi: lolos berkas → diterima / ditolak

### 🎉 Wisuda
- Pengecekan syarat otomatis: **SKS lulus**, **IPK ≥ 2.00**, **tanpa tunggakan SPP**, **status aktif**
- Predikat dihitung dari IPK (Cumlaude ≥ 3.51, Sangat Memuaskan ≥ 2.76, Memuaskan ≥ 2.00)
- Akademik memverifikasi pendaftar, status mahasiswa berubah menjadi `lulus`

### 🗂️ Master Data & Statistik
- Mahasiswa, dosen, mata kuliah, fakultas dan prodi, semester (aktifkan semester)
- Statistik: distribusi IPK, distribusi nilai, mahasiswa per prodi dan angkatan, status SPP, 10 IPK tertinggi

---

## 👥 Role & Hak Akses

| Role | Akses utama |
|---|---|
| **Admin** | Seluruh fitur, master data, persetujuan KRS, kunci nilai |
| **Akademik** | Jadwal, master data, wisuda, kunci nilai + KHS, persetujuan KRS |
| **Keuangan** | Tagihan & verifikasi SPP, beasiswa, laporan |
| **Dosen** | Jadwal mengajar, absensi, input nilai, KRS mahasiswa wali |
| **Mahasiswa** | KRS, jadwal, nilai/KHS, transkrip, SPP, beasiswa, wisuda |

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|---|---|
| Backend | Laravel 11 (PHP 8.2+) |
| Database | MySQL 8.0 |
| Frontend | Blade + Tailwind CSS (CDN) |
| Auth | Laravel built-in Auth (session) |
| Storage | Laravel Storage (disk `public`) untuk bukti bayar |

---

## ⚙️ Cara Install

### Requirements
- PHP >= 8.2, Composer, MySQL 8.0

### Langkah

```bash
# 1. Clone
git clone https://github.com/bayukresnaadjie/siakad.git
cd siakad

# 2. Dependencies
composer install

# 3. Environment
cp .env.example .env
php artisan key:generate

# 4. Buat database
# CREATE DATABASE siakad;

# 5. Edit .env
# DB_CONNECTION=mysql
# DB_DATABASE=siakad
# DB_USERNAME=root
# DB_PASSWORD=
# SESSION_DRIVER=file
# QUEUE_CONNECTION=sync
# CACHE_STORE=file
# FILESYSTEM_DISK=public

# 6. Migrate + seed data demo
php artisan migrate:fresh --seed

# 7. Link storage (untuk upload bukti bayar)
php artisan storage:link

# 8. Jalankan
php artisan serve
```

Akses: **http://127.0.0.1:8000**

> Query laporan memakai `FIELD()` yang khusus MySQL/MariaDB, jadi gunakan MySQL (bukan SQLite).

---

## 🔑 Akun Demo

Password semua akun: `password`

| Email | Role |
|---|---|
| admin@siakad.test | Admin |
| akademik@siakad.test | Akademik |
| keuangan@siakad.test | Keuangan |
| budi.dosen@siakad.test | Dosen (wali Ahmad, Putri, Maya) |
| siti.dosen@siakad.test | Dosen |
| ahmad@siakad.test | Mahasiswa SI 2021 (IPK 3.75) |
| putri@siakad.test | Mahasiswa SI 2021 (IPK 3.89) |
| rizky@siakad.test | Mahasiswa SI 2022 |
| maya@siakad.test | Mahasiswa SI 2023 (KRS menunggu persetujuan) |

**Data demo:** 3 fakultas, 3 prodi, 5 dosen, 6 mahasiswa, 20+ mata kuliah, 7 kelas dengan jadwal, KRS, absensi, nilai, tagihan SPP, 2 beasiswa, dan 1 periode wisuda. Tanggal semester aktif dibuat relatif terhadap hari ini, sehingga periode KRS selalu terbuka saat demo.

**Skenario yang bisa dicoba:**
1. Login `maya@siakad.test` → KRS sudah diajukan
2. Login `budi.dosen@siakad.test` → setujui KRS Maya dari dashboard
3. Login `budi.dosen@siakad.test` → Kelas Saya → input nilai → lihat huruf muncul langsung
4. Login `akademik@siakad.test` → kunci nilai → KHS tergenerate
5. Login `ahmad@siakad.test` → Transkrip → cetak
6. Login `rizky@siakad.test` → SPP → upload bukti → login `keuangan@siakad.test` → verifikasi

---

## 🗄️ Struktur Database (25 tabel)

```
users                    — akun + role
fakultas, program_studi  — struktur organisasi
semester                 — periode akademik + jadwal KRS/UTS/UAS
ruangan                  — ruang kuliah, lab, aula
dosen, mahasiswa         — profil akademik
mata_kuliah, prasyarat_mk, kurikulum, kurikulum_mk
kelas, jadwal_kelas      — kelas per semester + jadwal
krs                      — rencana studi + status persetujuan
pertemuan, absensi       — kehadiran per pertemuan
nilai, khs               — nilai komponen, huruf, bobot, IP semester
tagihan_spp, pembayaran_spp
beasiswa, pendaftaran_beasiswa
wisuda, pendaftaran_wisuda
```

---

## 📁 Struktur Project

```
app/
├── Http/Controllers/
│   ├── DashboardController.php   ← dashboard per role
│   ├── KrsController.php         ← validasi SKS/kapasitas/periode + approval
│   ├── NilaiController.php       ← hitung nilai, kunci, generate KHS
│   ├── AbsensiController.php
│   ├── JadwalController.php      ← cek bentrok ruangan
│   ├── SppController.php
│   ├── BeasiswaController.php
│   ├── WisudaController.php      ← cek syarat kelulusan
│   ├── TranskripController.php
│   ├── MasterController.php
│   └── LaporanController.php
└── Models/                       ← 22 model (Nilai::hitungNilaiAkhir, Mahasiswa::hitungIpk, dst.)
database/
├── migrations/                   ← 5 file
└── seeders/                      ← 6 seeder
resources/views/
├── layouts/app.blade.php         ← sidebar dinamis per role
├── dashboard/{mahasiswa,dosen,admin,keuangan}.blade.php
└── krs/ jadwal/ nilai/ absensi/ spp/ beasiswa/ wisuda/ transkrip/ master/ laporan/
```

---

## ⚠️ Batasan Saat Ini

- Belum ada tes otomatis (PHPUnit)
- Belum ada form CRUD untuk master data (mahasiswa, dosen, mata kuliah) — saat ini hanya tampilan dan filter
- Belum ada pengaturan prasyarat mata kuliah di alur KRS (tabel `prasyarat_mk` sudah tersedia)
- Tampilan memakai Tailwind via CDN, belum memakai build Vite

## 🔮 Rencana Pengembangan

- [ ] CRUD master data lengkap + import mahasiswa dari Excel
- [ ] Validasi prasyarat mata kuliah saat KRS
- [ ] Export transkrip/KHS ke PDF server-side (DomPDF)
- [ ] Notifikasi (KRS disetujui, nilai keluar, tagihan jatuh tempo)
- [ ] Evaluasi dosen oleh mahasiswa (EDOM)
- [ ] Tes otomatis untuk perhitungan nilai dan IPK

---

## 👨‍💻 Developer

**Bayu Kresna Adjie**
- GitHub: [@bayukresnaadjie](https://github.com/bayukresnaadjie)
- LinkedIn: [bayu-kresna-adjie](https://www.linkedin.com/in/bayu-kresna-adjie-a883301ab)
- Lokasi: Yogyakarta, Indonesia

## 📄 Lisensi

Project ini dibuat untuk keperluan portofolio dan pembelajaran.

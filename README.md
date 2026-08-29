# Sistem Internal PT Properindo Enviro Tech

Aplikasi internal untuk pengelolaan **data karyawan** dan **monitoring pekerjaan**, dibuat sebagai bagian dari tes kompetensi seleksi Staff IT.

Dua kebutuhan dari soal (Sistem Informasi Data Karyawan & Sistem Monitoring Pekerjaan Internal) digabung jadi satu aplikasi, karena PIC pada modul monitoring pekerjaan mengacu langsung ke data karyawan yang sama.

---

## Demo

- **Link aplikasi:** `(isi setelah deploy ke Railway)`
- **Kredensial login:** lihat bagian [Kredensial Login](#kredensial-login)

---

## Tech Stack

| Komponen | Teknologi | Alasan |
|---|---|---|
| Framework | Laravel 11 | Familiar, ekosistem matang, cocok untuk timeline pengerjaan singkat |
| Admin Panel | Filament 3 | CRUD, dashboard, filter, dan form builder generate otomatis — mempercepat development tanpa mengorbankan kerapian |
| Database | MySQL | Sesuai kebutuhan relasi antar tabel (departemen, jabatan, karyawan, pekerjaan) |
| Histori Perubahan | spatie/laravel-activitylog | Mencatat otomatis setiap create/update/delete pada data karyawan |
| Export Laporan | pxlrbt/filament-excel | Export data karyawan ke Excel/CSV langsung dari tabel Filament |
| Notifikasi | Laravel Notification (database) + Scheduler | Notifikasi otomatis saat pekerjaan mendekati/melewati deadline |
| Environment Lokal | Laragon | Web server, PHP, dan MySQL dalam satu paket untuk development di Windows |
| Deployment | Railway | Hosting + MySQL + cron job dalam satu platform |

---

## Fitur

### Modul 1 — Data Karyawan
- Login pengguna (bawaan Filament)
- Dashboard: total karyawan, karyawan aktif, jumlah departemen
- CRUD Departemen, Jabatan, dan Karyawan
- Form Karyawan: pilih Jabatan terlebih dahulu, Departemen ter-isi otomatis (read-only) sesuai jabatan yang dipilih
- Pencarian (nama, kode karyawan) dan filter (departemen, jabatan, status)
- Export laporan karyawan ke Excel/CSV
- Riwayat perubahan data (tambah/ubah/hapus) untuk seluruh karyawan dalam satu tampilan, dengan penanda warna per jenis aksi

### Modul 2 — Monitoring Pekerjaan
- CRUD data pekerjaan
- PIC dipilih langsung dari data Karyawan (bukan tabel terpisah)
- Dashboard: total pekerjaan, sedang proses, terlambat, mendekati deadline (≤ 3 hari)
- Filter berdasarkan PIC, status, dan rentang deadline
- Penanda warna baris deadline: merah (terlambat), kuning (mendekati), normal (aman)
- Notifikasi otomatis (ikon lonceng di panel admin) saat pekerjaan mendekati atau melewati deadline, dijalankan terjadwal setiap hari lewat Laravel Scheduler

---

## Struktur Database (ERD)

```
departments                jabatans                    employees
┌───────────────┐         ┌────────────────────┐      ┌─────────────────────────┐
│ id         PK │◄───┐    │ id              PK  │◄──┐  │ id                  PK  │
│ name          │    └────┤ department_id   FK  │   └──┤ jabatan_id          FK  │
└───────────────┘         │ name                │      │ department_id       FK  │
                           └─────────────────────┘      │ employee_code           │
                                                         │ name                    │
                                                         │ email                   │
                                                         │ status (aktif/nonaktif) │
                                                         └───────────┬─────────────┘
                                                                     │
                                                                     │ pic_id (FK)
                                                                     ▼
                                                         tasks
                                                         ┌─────────────────────────┐
                                                         │ id                  PK  │
                                                         │ task_name               │
                                                         │ pic_id              FK  │ → employees.id
                                                         │ deadline (date)         │
                                                         │ status                  │
                                                         │ priority                │
                                                         └─────────────────────────┘
```

**Penjelasan relasi:**
- Satu **department** memiliki banyak **jabatan** dan banyak **employee**.
- Satu **jabatan** dimiliki satu **department**, dan memiliki banyak **employee**.
- Satu **employee** memiliki satu **department** dan satu **jabatan** (department ter-isi otomatis mengikuti jabatan yang dipilih).
- Satu **employee** bisa menjadi PIC untuk banyak **task**.
- Perubahan data pada tabel `employees` (create/update/delete) tercatat otomatis ke tabel `activity_log` (package `spatie/laravel-activitylog`).

---

## Instalasi Lokal

### Prasyarat
- [Laragon](https://laragon.org/) (menyediakan PHP, Composer, dan MySQL)
- Git

### Langkah

```bash
# 1. Clone repo
git clone https://github.com/username/properindo-app.git
cd properindo-app

# 2. Install dependency PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Sesuaikan koneksi database di .env (default Laragon)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=properindo_karyawan
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Buat database kosong bernama "properindo_karyawan" lewat HeidiSQL/phpMyAdmin bawaan Laragon

# 6. Generate application key
php artisan key:generate

# 7. Jalankan migration + seeder akun admin
php artisan migrate --seed

# 8. Jalankan server lokal
php artisan serve
```

Buka `http://127.0.0.1:8000` di browser — landing page akan tampil dengan tombol menuju halaman login (`/admin`).

### Menjalankan notifikasi deadline secara manual (opsional, untuk testing)
```bash
php artisan tasks:check-deadlines
```

### Menjalankan scheduler (agar notifikasi berjalan otomatis setiap hari)
```bash
php artisan schedule:work
```

---

## Kredensial Login

| Nama | Email | Password |
|---|---|---|
| Admin | admin@properindo.test | password123 |
| Bagus | bagus@properindo.test | password123 |

---

## Catatan Teknis Tambahan

- Ekstensi PHP `intl`, `zip`, dan `gd` dibutuhkan oleh Filament dan package export (dideklarasikan di `composer.json` agar otomatis terpasang saat deploy).
- `QUEUE_CONNECTION` menggunakan `sync` — proses export dan notifikasi dijalankan langsung tanpa background worker terpisah, karena skala data pada sistem ini masih kecil dan ini menyederhanakan proses deployment.
- Timezone aplikasi diset ke `Asia/Jakarta` agar jadwal notifikasi (`Schedule::command(...)->dailyAt(...)`) berjalan sesuai waktu Indonesia.

---

## Deployment

Aplikasi di-deploy ke [Railway](https://railway.app) dengan konfigurasi:
- Service Laravel + service database MySQL dalam satu project
- Start command: `php artisan migrate --force && php artisan db:seed --class=AdminUserSeeder --force && php artisan serve --host 0.0.0.0 --port $PORT`
- Cron job terpisah untuk menjalankan `php artisan tasks:check-deadlines` setiap hari

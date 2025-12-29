# Sistem Pembayaran SPP Pondok Darul Jalal

Sistem manajemen pembayaran SPP berbasis web dengan notifikasi WhatsApp untuk Pondok Darul Jalal.

## Fitur Utama

### Role Admin/Operator

1. **Dashboard** - Menampilkan statistik: Total Siswa Aktif, Total Uang Kas, Pesan Baru, Tagihan Baru
2. **Data User** - CRUD user dengan role (Admin, Operator, Wali Murid)
3. **Data Rekening Sekolah** - Kelola rekening bank untuk menerima pembayaran
4. **Data Wali Murid** - Kelola data wali murid dan hubungannya dengan siswa
5. **Data Siswa** - Kelola data siswa (CRUD, import Excel, aktivasi/non-aktivasi)
6. **Data Biaya** - Kelola jenis biaya (SPP, Katering, Laundry)
7. **Data Tagihan** - Lihat dan kelola tagihan siswa
8. **Data Pembayaran** - Konfirmasi pembayaran dari wali murid
9. **Buat Tagihan** - Generate tagihan bulanan secara otomatis
10. **Pengaturan Aplikasi** - Konfigurasi sistem

### Role Wali Santri

1. **Dashboard** - Ringkasan informasi
2. **Data Siswa** - Lihat data anak yang terdaftar, cetak kartu SPP
3. **Data Tagihan** - Lihat tagihan dan bayar online
4. **Konfirmasi Pembayaran** - Upload bukti transfer
5. **Ubah Profil** - Update data pribadi

## Teknologi

-   **Framework**: Laravel 11
-   **Database**: MySQL
-   **Frontend**: Blade Templates + Tailwind CSS
-   **Authentication**: Laravel Auth dengan Role-based Access Control

## Instalasi

### Prerequisites

-   PHP 8.2 atau lebih tinggi
-   Composer
-   MySQL/MariaDB
-   Node.js & NPM

### Langkah Instalasi

1. Clone repository

```bash
git clone <repository-url>
cd laravel-sistem-pembayaran-spp-ardi
```

2. Install dependencies

```bash
composer install
npm install
```

3. Copy file environment

```bash
cp .env.example .env
```

4. Generate application key

```bash
php artisan key:generate
```

5. Konfigurasi database di file `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spp_darul_jalal
DB_USERNAME=root
DB_PASSWORD=
```

6. Jalankan migration dan seeder

```bash
php artisan migrate --seed
```

7. Build assets

```bash
npm run dev
```

8. Jalankan aplikasi

```bash
php artisan serve
```

## Default Login

**Admin**

-   Email: admin@daruljalal.com
-   Password: password

**Operator**

-   Email: operator@daruljalal.com
-   Password: password

**Wali Santri** (5 akun tersedia)

-   Email: budi@example.com - Password: password (2 anak)
-   Email: siti@example.com - Password: password (1 anak)
-   Email: hendra@example.com - Password: password (3 anak)
-   Email: rina@example.com - Password: password (1 anak)
-   Email: agus@example.com - Password: password (2 anak, 1 non-aktif)

## Struktur Database

### Tabel Utama

-   `users` - Data pengguna sistem
-   `wali_murid` - Data wali murid
-   `siswa` - Data siswa
-   `biaya` - Jenis biaya (SPP, Katering, Laundry)
-   `tagihan` - Tagihan siswa per bulan
-   `detail_tagihan` - Detail item tagihan
-   `pembayaran` - Data pembayaran
-   `rekening_sekolah` - Rekening bank sekolah
-   `job_tagihan` - Log pembuatan tagihan otomatis
-   `pengaturan` - Konfigurasi sistem

## Biaya Default

-   **SPP**: Rp 500.000
-   **Katering**: Rp 300.000
-   **Laundry**: Rp 150.000

## Workflow Pembayaran

1. Admin membuat tagihan bulanan untuk siswa
2. Wali murid melihat tagihan di dashboard
3. Wali murid melakukan transfer dan upload bukti
4. Admin mengkonfirmasi pembayaran
5. Sistem update status tagihan
6. Notifikasi WhatsApp dikirim (akan diimplementasi)

## Routes

### Admin Routes (Prefix: /admin)

-   Dashboard: `/admin/dashboard`
-   Users: `/admin/users`
-   Rekening: `/admin/rekening-sekolah`
-   Wali Murid: `/admin/wali-murid`
-   Siswa: `/admin/siswa`
-   Biaya: `/admin/biaya`
-   Tagihan: `/admin/tagihan`
-   Pembayaran: `/admin/pembayaran`
-   Job Tagihan: `/admin/job-tagihan`
-   Pengaturan: `/admin/pengaturan`

### Wali Routes (Prefix: /wali)

-   Dashboard: `/wali/dashboard`
-   Siswa: `/wali/siswa`
-   Tagihan: `/wali/tagihan`
-   Pembayaran: `/wali/pembayaran`
-   Profile: `/wali/profile`

## Development Roadmap

### Fase 1 - Basic System ✅

-   [x] Database structure
-   [x] Models dan relationships
-   [x] Authentication & Authorization
-   [x] Basic routes
-   [x] Admin dashboard
-   [x] Initial seeder

### Fase 2 - Core Features (Next Step)

-   [ ] Complete all CRUD operations for Admin
-   [ ] File upload for payment proof
-   [ ] Excel import for students
-   [ ] PDF generation for SPP cards
-   [ ] Payment confirmation workflow
-   [ ] Wali Santri dashboard and views

### Fase 3 - Advanced Features

-   [ ] WhatsApp notification integration
-   [ ] Automatic billing job
-   [ ] Reports and analytics
-   [ ] Multi-user support
-   [ ] Activity logs

### Fase 4 - Optimization

-   [ ] Performance optimization
-   [ ] Security hardening
-   [ ] UI/UX improvements
-   [ ] Mobile responsive
-   [ ] Testing

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── Wali/           # Wali Santri controllers
│   │   └── AuthController.php
│   └── Middleware/
│       └── RoleMiddleware.php
├── Models/                 # Eloquent models
│   ├── User.php
│   ├── WaliMurid.php
│   ├── Siswa.php
│   ├── Biaya.php
│   ├── Tagihan.php
│   ├── DetailTagihan.php
│   ├── Pembayaran.php
│   ├── RekeningSekolah.php
│   ├── JobTagihan.php
│   └── Pengaturan.php
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
└── resources/
    └── views/
        ├── layouts/        # Layout templates
        ├── auth/           # Authentication views
        ├── admin/          # Admin views
        └── wali/           # Wali Santri views
```

## API Endpoints (Future)

API endpoints untuk integrasi WhatsApp dan mobile app akan ditambahkan di fase berikutnya.

## Contributing

Untuk kontribusi, silakan buat branch baru dan submit pull request.

## License

This project is private and proprietary to Pondok Darul Jalal.

## Support

Untuk bantuan atau pertanyaan, hubungi tim development.

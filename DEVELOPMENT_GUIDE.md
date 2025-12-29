# Panduan Development - Sistem Pembayaran SPP Pondok Darul Jalal

## Status Saat Ini

### ✅ Yang Sudah Selesai:

1. **Database Structure** - Semua tabel sudah dibuat dengan migrasi
2. **Models** - Semua model dengan relasi sudah lengkap
3. **Authentication** - Login/logout dengan role-based access
4. **Middleware** - Role middleware untuk admin/operator/wali
5. **Routes** - Semua routes sudah terdefinisi
6. **Basic Controllers** - Controller skeleton sudah dibuat
7. **Admin Dashboard** - Dashboard dengan statistik dasar
8. **Seeder** - Data awal (admin user, biaya, pengaturan)

### 📋 Yang Perlu Dilanjutkan:

## 1. Implementasi CRUD Controllers

### Admin Controllers yang perlu dilengkapi:

#### UserController

-   Index: Tampilkan list user dengan pagination
-   Create: Form tambah user (admin/operator/wali)
-   Store: Simpan user baru
-   Edit: Form edit user
-   Update: Update data user
-   Delete: Hapus user

#### RekeningSekolahController

-   Index: List rekening bank
-   Create: Form tambah rekening
-   Store: Simpan rekening baru
-   Edit: Form edit rekening
-   Update: Update rekening
-   Delete: Hapus rekening

#### WaliMuridController

-   Index: List wali murid dengan siswa mereka
-   Create: Form tambah wali murid
-   Store: Simpan wali murid + create user
-   Detail: Tampilkan detail wali dan list anak
-   AddSiswa: Hubungkan siswa ke wali
-   RemoveSiswa: Hapus hubungan siswa-wali
-   Delete: Hapus wali murid

#### SiswaController (Lihat SiswaControllerExample.php)

-   Index: List siswa dengan filter dan search
-   Create: Form tambah siswa
-   Store: Simpan siswa baru
-   Show: Detail siswa dengan tagihan
-   Edit: Form edit siswa
-   Update: Update siswa
-   Delete: Hapus siswa
-   Import: Import dari Excel
-   ToggleStatus: Aktifkan/nonaktifkan siswa
-   DeleteAll: Hapus semua siswa
-   ActivateAll: Aktifkan semua
-   DeactivateAll: Nonaktifkan semua

#### BiayaController

-   Index: List biaya
-   Create: Form tambah biaya
-   Store: Simpan biaya
-   Edit: Form edit biaya
-   Update: Update biaya
-   Delete: Hapus biaya

#### TagihanController

-   Index: List tagihan dengan filter
-   Show: Detail tagihan
-   Detail: Detail tagihan per siswa
-   Bayar: Process pembayaran manual
-   CetakKartu: Generate PDF kartu SPP

#### PembayaranController

-   Index: List pembayaran perlu konfirmasi
-   Show: Detail pembayaran
-   Konfirmasi: Konfirmasi pembayaran
-   Tolak: Tolak pembayaran

#### JobTagihanController

-   Index: List job pembuatan tagihan
-   Create: Buat tagihan bulk untuk bulan tertentu

#### PengaturanController

-   Index: Form pengaturan aplikasi
-   Update: Simpan pengaturan

### Wali Santri Controllers yang perlu dilengkapi:

#### DashboardController

-   Index: Dashboard wali dengan ringkasan

#### SiswaController

-   Index: List anak dari wali
-   KartuSpp: Cetak kartu SPP anak

#### TagihanController

-   Index: List tagihan semua anak
-   Show: Detail tagihan

#### PembayaranController

-   Konfirmasi: Form upload bukti bayar
-   StoreKonfirmasi: Simpan bukti bayar

#### ProfileController

-   Index: Form ubah profil
-   Update: Update profil

## 2. Views yang Perlu Dibuat

### Admin Views

```
resources/views/admin/
├── dashboard.blade.php ✅
├── users/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── rekening-sekolah/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── wali-murid/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── detail.blade.php
├── siswa/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
├── biaya/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── tagihan/
│   ├── index.blade.php
│   ├── show.blade.php
│   └── detail.blade.php
├── pembayaran/
│   ├── index.blade.php
│   └── show.blade.php
├── job-tagihan/
│   └── index.blade.php
└── pengaturan/
    └── index.blade.php
```

### Wali Views

```
resources/views/wali/
├── dashboard.blade.php
├── siswa/
│   └── index.blade.php
├── tagihan/
│   ├── index.blade.php
│   └── show.blade.php
├── pembayaran/
│   └── konfirmasi.blade.php
└── profile/
    └── index.blade.php
```

## 3. Fitur Tambahan

### File Upload

-   Install package: `composer require intervention/image`
-   Konfigurasi storage untuk bukti pembayaran
-   Validation file upload (image, max 2MB)

### Excel Import/Export

-   Install package: `composer require maatwebsite/excel`
-   Buat import class untuk siswa
-   Buat export class untuk laporan

### PDF Generation

-   Install package: `composer require barryvdh/laravel-dompdf`
-   Template kartu SPP
-   Template laporan

### WhatsApp Integration (Fase 3)

-   Install package WhatsApp API
-   Setup webhook
-   Template notifikasi:
    -   Tagihan baru
    -   Konfirmasi pembayaran
    -   Reminder pembayaran

## 4. Job & Queue

### Buat Job untuk Generate Tagihan

```bash
php artisan make:job ProcessTagihan
```

Implementasi:

-   Loop semua siswa aktif
-   Buat tagihan per siswa
-   Tambahkan detail tagihan (SPP wajib, katering/laundry opsional)
-   Update job progress
-   Kirim notifikasi WhatsApp

### Buat Job untuk Reminder

```bash
php artisan make:job SendPaymentReminder
```

## 5. Validation & Request Classes

Buat form request untuk validasi:

```bash
php artisan make:request StoreSiswaRequest
php artisan make:request UpdateSiswaRequest
php artisan make:request StoreTagihanRequest
php artisan make:request StorePembayaranRequest
```

## 6. Testing

### Unit Tests

-   Model relationships
-   Helper methods
-   Business logic

### Feature Tests

-   Authentication
-   Authorization
-   CRUD operations
-   File uploads
-   Payment workflow

## 7. Security

-   [ ] CSRF protection (sudah ada)
-   [ ] SQL injection prevention (Eloquent ORM)
-   [ ] XSS prevention (Blade templating)
-   [ ] File upload validation
-   [ ] Rate limiting untuk login
-   [ ] Password hashing (sudah ada)
-   [ ] Role-based access control (sudah ada)

## 8. Performance

-   [ ] Database indexing
-   [ ] Eager loading untuk relasi
-   [ ] Caching untuk pengaturan
-   [ ] Query optimization
-   [ ] Asset optimization (Vite)

## 9. UI/UX

-   [ ] Responsive design
-   [ ] Loading indicators
-   [ ] Confirmation dialogs
-   [ ] Toast notifications
-   [ ] Form validation feedback
-   [ ] Pagination
-   [ ] Search & filter
-   [ ] Sort columns

## 10. Deployment

### Persiapan Production

-   [ ] Environment configuration
-   [ ] Database backup
-   [ ] SSL certificate
-   [ ] Domain setup
-   [ ] Server requirements
-   [ ] Error monitoring
-   [ ] Log management

## Langkah Berikutnya

1. **Implementasi User Management**

    - Buat views untuk CRUD user
    - Implement UserController methods
    - Test create/edit/delete user

2. **Implementasi Siswa Management**

    - Copy code dari SiswaControllerExample.php ke SiswaController.php
    - Buat semua views siswa
    - Test import Excel (install package dulu)

3. **Implementasi Tagihan System**

    - Buat Job ProcessTagihan
    - Implement TagihanController
    - Buat views tagihan

4. **Implementasi Pembayaran**

    - Setup file upload
    - Implement PembayaranController
    - Test workflow pembayaran

5. **Wali Santri Interface**

    - Implement semua Wali controllers
    - Buat views untuk wali
    - Test dari perspektif wali

6. **WhatsApp Integration**
    - Research WhatsApp API options
    - Implement notification service
    - Test notifikasi

## Tips Development

1. **Testing**: Test setiap fitur setelah implementasi
2. **Git**: Commit setiap fitur yang selesai
3. **Documentation**: Update dokumentasi seiring development
4. **Code Review**: Review code sebelum merge
5. **Database**: Backup database sebelum migration baru

## Kontak Development

Jika ada pertanyaan atau butuh bantuan, silakan hubungi tim development.

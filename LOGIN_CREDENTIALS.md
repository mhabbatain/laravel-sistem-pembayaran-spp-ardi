# Login Credentials

## Role Admin

-   **Email**: admin@daruljalal.com
-   **Password**: password

## Role Operator

1. **Operator 1**

    - **Email**: operator1@daruljalal.com
    - **Password**: password

2. **Operator 2**
    - **Email**: operator2@daruljalal.com
    - **Password**: password

## Role Wali Murid

### 1. Budi Santoso (2 anak)

-   **Email**: budi@example.com
-   **Password**: password
-   **Anak**:
    -   Ahmad Budi Santoso (NISN: 1234567890, Kelas X IPA)
    -   Fatimah Budi Santoso (NISN: 1234567891, Kelas VIII IPS)

### 2. Siti Aminah (1 anak)

-   **Email**: siti@example.com
-   **Password**: password
-   **Anak**:
    -   Muhammad Rizki (NISN: 1234567892, Kelas IX IPA)

### 3. Hendra Wijaya (3 anak)

-   **Email**: hendra@example.com
-   **Password**: password
-   **Anak**:
    -   Dina Wijaya (NISN: 1234567893, Kelas XI IPA)
    -   Rina Wijaya (NISN: 1234567894, Kelas IX IPS)
    -   Andi Wijaya (NISN: 1234567895, Kelas VII IPA)

### 4. Rina Kusuma (1 anak)

-   **Email**: rina@example.com
-   **Password**: password
-   **Anak**:
    -   Siti Nurhaliza (NISN: 1234567896, Kelas X IPS)

### 5. Agus Pratama (2 anak)

-   **Email**: agus@example.com
-   **Password**: password
-   **Anak**:
    -   Rudi Pratama (NISN: 1234567897, Kelas VIII IPA, Aktif)
    -   Dewi Pratama (NISN: 1234567898, Kelas XII IPS, Non-aktif)

## Data Lainnya

### Biaya

-   **SPP**: Rp 500.000
-   **Katering**: Rp 300.000
-   **Laundry**: Rp 150.000
-   **Total per bulan**: Rp 950.000

### Rekening Sekolah

1. **Bank Mandiri** (Aktif)

    - Kode: MANDIRI001
    - Pemilik: Pondok Darul Jalal
    - No. Rekening: 1234567890

2. **Bank BRI** (Aktif)

    - Kode: BRI001
    - Pemilik: Yayasan Darul Jalal
    - No. Rekening: 0987654321

3. **Bank BCA** (Non-aktif)
    - Kode: BCA001
    - Pemilik: Pondok Darul Jalal
    - No. Rekening: 1122334455

### Status Tagihan

#### Oktober 2024

-   Semua siswa aktif: **LUNAS**

#### November 2024

-   Siswa dengan ID genap: **LUNAS**
-   Siswa dengan ID ganjil: **CICILAN** (Sudah bayar SPP saja)

#### Desember 2024

-   Semua siswa aktif: **BARU** (Belum bayar)
-   3 siswa pertama memiliki pembayaran dengan status **PENDING** (menunggu konfirmasi)

## Cara Testing

1. **Login sebagai Admin/Operator**

    - Kelola data siswa, wali, biaya, rekening
    - Konfirmasi pembayaran yang pending
    - Lihat laporan tagihan dan pembayaran

2. **Login sebagai Wali Murid**
    - Lihat tagihan anak
    - Bayar tagihan yang masih baru/cicilan
    - Upload bukti pembayaran
    - Cek history pembayaran

## URL Login

http://localhost/login

# 📱 Panduan Integrasi WhatsApp Self-Hosting

Dokumentasi untuk fitur notifikasi WhatsApp menggunakan Evolution API (self-hosted).

## 📋 Daftar Isi

-   [Persyaratan](#persyaratan)
-   [Instalasi Evolution API](#instalasi-evolution-api)
-   [Konfigurasi Laravel](#konfigurasi-laravel)
-   [Cara Penggunaan](#cara-penggunaan)
-   [Troubleshooting](#troubleshooting)

---

## Persyaratan

1. **Docker Desktop** - Download di https://www.docker.com/products/docker-desktop/
2. **Nomor WhatsApp** - Gunakan nomor yang tidak terpakai untuk WhatsApp Web lainnya
3. **Laravel** sudah terinstall dan berjalan

---

## Instalasi Evolution API

### Langkah 1: Jalankan Docker Desktop

Pastikan Docker Desktop sudah berjalan di komputer Anda.

### Langkah 2: Jalankan Evolution API

Buka terminal di folder proyek dan jalankan:

```bash
docker-compose -f docker-compose.whatsapp.yml up -d
```

Tunggu sampai container selesai di-download dan berjalan.

### Langkah 3: Verifikasi

Akses `http://localhost:8080` di browser. Jika berhasil, akan muncul halaman Evolution API.

---

## Konfigurasi Laravel

### File .env

Pastikan konfigurasi berikut sudah ada di file `.env`:

```env
# WhatsApp Evolution API Configuration
WHATSAPP_API_URL=http://localhost:8080
WHATSAPP_API_KEY=SppDarulJalalApiKey2026
WHATSAPP_INSTANCE_NAME=spp-darul-jalal
```

### Jalankan Queue Worker

Untuk memproses notifikasi secara background, jalankan queue worker:

```bash
php artisan queue:work
```

**Tip:** Di production, gunakan Supervisor untuk menjaga queue worker tetap berjalan.

---

## Cara Penggunaan

### 1. Akses Halaman Pengaturan WhatsApp

Login sebagai Admin, lalu klik menu **WhatsApp** di sidebar.

### 2. Hubungkan WhatsApp

1. Klik tombol **"Hubungkan WhatsApp"**
2. QR Code akan muncul di layar
3. Buka WhatsApp di HP Anda
4. Pergi ke **Menu > Linked Devices > Link a Device**
5. Scan QR Code yang tampil di layar
6. Tunggu sampai status berubah menjadi **"Terhubung"**

### 3. Konfigurasi Notifikasi

Di halaman yang sama, Anda bisa mengatur:

-   ✅ **Aktifkan/Nonaktifkan** notifikasi WhatsApp secara keseluruhan
-   ✅ **Notifikasi Tagihan Baru** - Kirim pesan saat tagihan dibuat
-   ✅ **Notifikasi Konfirmasi Pembayaran** - Kirim pesan saat pembayaran dikonfirmasi
-   ✅ **Notifikasi Pengingat** - Kirim pengingat untuk tagihan yang belum dibayar

### 4. Test Pengiriman

Gunakan form **"Kirim Pesan Test"** untuk memastikan koneksi berfungsi:

1. Masukkan nomor WhatsApp (contoh: `08123456789`)
2. Tulis pesan test
3. Klik **"Kirim Pesan Test"**
4. Periksa HP penerima apakah pesan sudah masuk

---

## Template Pesan

### Notifikasi Tagihan Baru

```
🔔 *TAGIHAN BARU*

Assalamu'alaikum Wr. Wb.

Bapak/Ibu Wali dari *Ahmad Santri*,

Berikut informasi tagihan SPP:

📋 *Detail Tagihan*
━━━━━━━━━━━━━━━━
Periode: *Januari 2026*
Nama: *Ahmad Santri*
Kelas: *VII A*

*Rincian Biaya:*
• SPP: Rp 500.000
• Katering: Rp 300.000
• Laundry: Rp 200.000

━━━━━━━━━━━━━━━━
*Total: Rp 1.000.000*
━━━━━━━━━━━━━━━━

Mohon segera melakukan pembayaran sebelum tanggal jatuh tempo.

Terima kasih atas perhatiannya.

Wassalamu'alaikum Wr. Wb.

_*Pondok Darul Jalal*_
```

### Notifikasi Konfirmasi Pembayaran

```
✅ *PEMBAYARAN DIKONFIRMASI*

Assalamu'alaikum Wr. Wb.

Bapak/Ibu Wali dari *Ahmad Santri*,

Pembayaran Anda telah dikonfirmasi.

📋 *Detail Pembayaran*
━━━━━━━━━━━━━━━━
No. Transaksi: *#123*
Tanggal: *12/01/2026*
Periode: *Januari 2026*
Nama: *Ahmad Santri*

*Jumlah Dibayar: Rp 1.000.000*

Status Tagihan: ✅ LUNAS
━━━━━━━━━━━━━━━━

Terima kasih atas pembayarannya.

Wassalamu'alaikum Wr. Wb.

_*Pondok Darul Jalal*_
```

---

## Arsitektur

```
┌─────────────────┐     HTTP API      ┌──────────────────┐
│   Laravel App   │ ────────────────► │   Evolution API  │
│                 │                   │   (Docker)       │
│ - Controller    │                   │                  │
│ - Service       │ ◄──────────────── │                  │
│ - Job Queue     │     Response      │                  │
└─────────────────┘                   └────────┬─────────┘
                                               │
                                               │ WebSocket
                                               ▼
                                      ┌──────────────────┐
                                      │   WhatsApp Web   │
                                      │   (Connected HP) │
                                      └──────────────────┘
```

---

## Troubleshooting

### QR Code Tidak Muncul

1. Pastikan Evolution API sudah berjalan

    ```bash
    docker ps
    ```

    Harus ada container `evolution_api` yang running.

2. Cek log container:

    ```bash
    docker logs evolution_api
    ```

3. Restart container:
    ```bash
    docker-compose -f docker-compose.whatsapp.yml restart
    ```

### WhatsApp Terputus

1. Pastikan HP tetap terkoneksi internet
2. Jangan logout dari WhatsApp Web di HP
3. Klik **"Refresh Status"** di halaman pengaturan
4. Jika masih tidak bisa, klik **"Restart Instance"** dan scan QR ulang

### Pesan Tidak Terkirim

1. Cek status koneksi di halaman pengaturan
2. Pastikan notifikasi WhatsApp sudah diaktifkan
3. Pastikan nomor HP wali murid valid dan terdaftar WhatsApp
4. Cek log Laravel:
    ```bash
    tail -f storage/logs/laravel.log
    ```

### Queue Tidak Berjalan

Pastikan queue worker sudah dijalankan:

```bash
php artisan queue:work --verbose
```

---

## File-file Terkait

| File                                                | Deskripsi                                     |
| --------------------------------------------------- | --------------------------------------------- |
| `docker-compose.whatsapp.yml`                       | Docker compose untuk Evolution API            |
| `config/services.php`                               | Konfigurasi WhatsApp API                      |
| `app/Services/WhatsAppService.php`                  | Service untuk komunikasi dengan Evolution API |
| `app/Services/WhatsAppNotificationService.php`      | Service untuk template notifikasi             |
| `app/Http/Controllers/Admin/WhatsAppController.php` | Controller pengaturan WhatsApp                |
| `resources/views/admin/whatsapp/index.blade.php`    | View halaman pengaturan                       |
| `app/Jobs/SendWhatsAppTagihanNotification.php`      | Job untuk kirim notifikasi background         |

---

## Perintah Docker Berguna

```bash
# Start Evolution API
docker-compose -f docker-compose.whatsapp.yml up -d

# Stop Evolution API
docker-compose -f docker-compose.whatsapp.yml down

# Restart Evolution API
docker-compose -f docker-compose.whatsapp.yml restart

# Lihat log
docker logs -f evolution_api

# Masuk ke container
docker exec -it evolution_api sh
```

---

## Referensi

-   [Evolution API Documentation](https://doc.evolution-api.com/)
-   [Laravel Queue Documentation](https://laravel.com/docs/queues)
-   [Docker Documentation](https://docs.docker.com/)

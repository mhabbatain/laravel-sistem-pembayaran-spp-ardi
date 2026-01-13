<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RekeningSekolahController;
use App\Http\Controllers\Admin\WaliMuridController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\BiayaController;
use App\Http\Controllers\Admin\TagihanController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\JobTagihanController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\WhatsAppController;
use App\Http\Controllers\Wali\DashboardController as WaliDashboard;
use App\Http\Controllers\Wali\SiswaController as WaliSiswa;
use App\Http\Controllers\Wali\TagihanController as WaliTagihan;
use App\Http\Controllers\Wali\PembayaranController as WaliPembayaran;
use App\Http\Controllers\Wali\ProfileController;
use App\Http\Controllers\PdfController;

Route::get('/', function () {
    // Jika sudah login, redirect ke dashboard sesuai role
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        }
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('wali.dashboard');
    }
    
    // Jika belum login, tampilkan halaman welcome
    return view('welcome');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('user', UserController::class);

    // Rekening Sekolah
    Route::resource('rekening-sekolah', RekeningSekolahController::class);

    // Wali Murid
    Route::resource('wali-murid', WaliMuridController::class);
    Route::get('wali-murid/{waliMurid}/detail', [WaliMuridController::class, 'detail'])->name('wali-murid.detail');
    Route::post('wali-murid/{waliMurid}/add-siswa', [WaliMuridController::class, 'addSiswa'])->name('wali-murid.add-siswa');
    Route::delete('wali-murid/{waliId}/siswa/{siswaId}', [WaliMuridController::class, 'removeSiswa'])->name('wali-murid.remove-siswa');

    // Siswa
    Route::resource('siswa', SiswaController::class);
    Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::delete('siswa/delete-all', [SiswaController::class, 'deleteAll'])->name('siswa.delete-all');
    Route::post('siswa/activate-all', [SiswaController::class, 'activateAll'])->name('siswa.activate-all');
    Route::post('siswa/deactivate-all', [SiswaController::class, 'deactivateAll'])->name('siswa.deactivate-all');
    Route::post('siswa/{siswa}/toggle-status', [SiswaController::class, 'toggleStatus'])->name('siswa.toggle-status');

    // Biaya
    Route::resource('biaya', BiayaController::class);

    // Tagihan
    Route::resource('tagihan', TagihanController::class);
    Route::get('tagihan/{tagihan}/detail', [TagihanController::class, 'detail'])->name('tagihan.detail');
    Route::post('tagihan/{tagihan}/bayar', [TagihanController::class, 'bayar'])->name('tagihan.bayar');
    Route::get('tagihan/{tagihan}/cetak-kartu', [TagihanController::class, 'cetakKartu'])->name('tagihan.cetak-kartu');

    // Pembayaran
    Route::resource('pembayaran', PembayaranController::class);
    Route::post('pembayaran/{pembayaran}/konfirmasi', [PembayaranController::class, 'konfirmasi'])->name('pembayaran.konfirmasi');
    Route::post('pembayaran/{pembayaran}/tolak', [PembayaranController::class, 'tolak'])->name('pembayaran.tolak');

    // Job Tagihan
    Route::resource('job-tagihan', JobTagihanController::class)->except(['edit', 'update']);

    // Pengaturan
    Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    // WhatsApp
    Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
        Route::get('/', [WhatsAppController::class, 'index'])->name('index');
        Route::post('/create-instance', [WhatsAppController::class, 'createInstance'])->name('create-instance');
        Route::get('/qr-code', [WhatsAppController::class, 'getQrCode'])->name('qr-code');
        Route::get('/status', [WhatsAppController::class, 'getStatus'])->name('status');
        Route::post('/disconnect', [WhatsAppController::class, 'disconnect'])->name('disconnect');
        Route::post('/settings', [WhatsAppController::class, 'updateSettings'])->name('settings');
        Route::post('/test', [WhatsAppController::class, 'sendTestMessage'])->name('test');
        Route::post('/restart', [WhatsAppController::class, 'restartInstance'])->name('restart');
    });

    // PDF Routes for Admin
    Route::prefix('pdf')->name('pdf.')->group(function () {
        Route::get('invoice-tagihan/{tagihan}', [PdfController::class, 'invoiceTagihan'])->name('invoice-tagihan');
        Route::get('invoice-tagihan/{tagihan}/view', [PdfController::class, 'invoiceTagihanStream'])->name('invoice-tagihan.view');
        Route::get('kwitansi/{pembayaran}', [PdfController::class, 'kwitansi'])->name('kwitansi');
        Route::get('kwitansi/{pembayaran}/view', [PdfController::class, 'kwitansiStream'])->name('kwitansi.view');
        Route::get('kartu-spp/{siswa}', [PdfController::class, 'kartuSpp'])->name('kartu-spp');
        Route::get('kartu-spp/{siswa}/view', [PdfController::class, 'kartuSppStream'])->name('kartu-spp.view');
        Route::get('laporan-pembayaran', [PdfController::class, 'laporanPembayaran'])->name('laporan-pembayaran');
        Route::get('laporan-tagihan', [PdfController::class, 'laporanTagihan'])->name('laporan-tagihan');
    });
});

// Wali Santri Routes
Route::middleware(['auth', 'role:wali'])->prefix('wali')->name('wali.')->group(function () {
    Route::get('/dashboard', [WaliDashboard::class, 'index'])->name('dashboard');

    // Siswa
    Route::get('siswa', [WaliSiswa::class, 'index'])->name('siswa.index');
    Route::get('siswa/{siswa}/kartu-spp', [WaliSiswa::class, 'kartuSpp'])->name('siswa.kartu-spp');

    // Tagihan
    Route::get('tagihan', [WaliTagihan::class, 'index'])->name('tagihan.index');
    Route::get('tagihan/{tagihan}', [WaliTagihan::class, 'show'])->name('tagihan.show');

    // Pembayaran
    Route::get('pembayaran/konfirmasi/{tagihan}', [WaliPembayaran::class, 'konfirmasi'])->name('pembayaran.konfirmasi');
    Route::post('pembayaran/konfirmasi/{tagihan}', [WaliPembayaran::class, 'storeKonfirmasi'])->name('pembayaran.store-konfirmasi');

    // Profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');

    // PDF Routes for Wali
    Route::prefix('pdf')->name('pdf.')->group(function () {
        Route::get('invoice-tagihan/{tagihan}', [PdfController::class, 'invoiceTagihan'])->name('invoice-tagihan');
        Route::get('invoice-tagihan/{tagihan}/view', [PdfController::class, 'invoiceTagihanStream'])->name('invoice-tagihan.view');
        Route::get('kwitansi/{pembayaran}', [PdfController::class, 'kwitansi'])->name('kwitansi');
        Route::get('kwitansi/{pembayaran}/view', [PdfController::class, 'kwitansiStream'])->name('kwitansi.view');
        Route::get('kartu-spp/{siswa}', [PdfController::class, 'kartuSpp'])->name('kartu-spp');
        Route::get('kartu-spp/{siswa}/view', [PdfController::class, 'kartuSppStream'])->name('kartu-spp.view');
    });
});

// Superadmin (Ketua Yayasan) Routes
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Superadmin\DashboardController::class, 'index'])->name('dashboard');

    // Data Santri (View Only)
    Route::get('siswa', [\App\Http\Controllers\Superadmin\SiswaController::class, 'index'])->name('siswa.index');
    Route::get('siswa/{siswa}', [\App\Http\Controllers\Superadmin\SiswaController::class, 'show'])->name('siswa.show');

    // Data Wali Santri (View Only)
    Route::get('wali-murid', [\App\Http\Controllers\Superadmin\WaliMuridController::class, 'index'])->name('wali-murid.index');
    Route::get('wali-murid/{waliMurid}', [\App\Http\Controllers\Superadmin\WaliMuridController::class, 'show'])->name('wali-murid.show');

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('rekap-pembayaran', [\App\Http\Controllers\Superadmin\LaporanController::class, 'rekapPembayaran'])->name('rekap-pembayaran');
        Route::get('pembayaran', [\App\Http\Controllers\Superadmin\LaporanController::class, 'pembayaran'])->name('pembayaran');
        Route::get('tagihan', [\App\Http\Controllers\Superadmin\LaporanController::class, 'tagihan'])->name('tagihan');
    });

    // Kelola Operator
    Route::resource('operator', \App\Http\Controllers\Superadmin\OperatorController::class);
});

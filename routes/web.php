<?php

use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\AdminAlumniController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminContentController;
use App\Http\Controllers\AdminGalleryController;
use App\Http\Controllers\AdminFotoFasilitasController;
use App\Http\Controllers\AdminKeuanganController;
use App\Http\Controllers\AdminKamarController;
use App\Http\Controllers\AdminLaporanKerusakanController;
use App\Http\Controllers\AdminPembayaranController;
use App\Http\Controllers\AdminPendaftaranController;
use App\Http\Controllers\AdminPenghuniController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PendaftaranController;
use App\Models\Alumni;
use App\Models\FotoFasilitas;
use App\Models\Gallery;
use App\Models\Kamar;
use App\Models\Pengaturan;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', fn () => view('welcome', [
    'galleries' => Schema::hasTable('galleries') ? Gallery::where('terlihat', true)->orderByDesc('unggulan')->latest()->get() : collect(),
    'fotoFasilitas' => Schema::hasTable('foto_fasilitas') ? FotoFasilitas::where('terlihat', true)->latest()->get() : collect(),
    'pengumumans' => Schema::hasTable('pengumuman') ? Pengumuman::where('aktif', true)->latest()->get() : collect(),
    'settings' => Schema::hasTable('pengaturan') ? Pengaturan::pluck('nilai', 'kunci') : collect(),
    'availableRooms' => Schema::hasTable('kamars') ? Kamar::when(Schema::hasColumn('kamars', 'status'), fn ($query) => $query->where('status', 'tersedia'))->whereColumn('terisi', '<', 'kapasitas')->count() : 0,
    'testimonials' => Schema::hasTable('alumni') && Schema::hasColumn('alumni', 'testimoni') ? Alumni::where('testimoni_aktif', true)->whereNotNull('testimoni')->latest('bergabung_sebagai_alumni_pada')->take(3)->get() : collect(),
]))->name('home');
Route::get('/galeri', function (\Illuminate\Http\Request $request) {
    $query = Gallery::where('terlihat', true)->latest();
    if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
    return view('gallery', ['galleries' => $query->get(), 'kategori' => $request->kategori]);
})->name('gallery.public');
Route::get('/pendaftaran', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/pendaftaran/status', [PendaftaranController::class, 'statusForm'])->name('pendaftaran.status');
Route::post('/pendaftaran/status', [PendaftaranController::class, 'status'])->name('pendaftaran.status.check');
Route::get('/pendaftaran/template/{type}', [PendaftaranController::class, 'template'])->name('pendaftaran.template');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
     Route::get('/superadmin/login', [AuthController::class, 'superadminLogin'])->name('superadmin.login');
     Route::post('/superadmin/login', [AuthController::class, 'superadminAuthenticate'])->middleware('throttle:5,1')->name('superadmin.login.store');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
     Route::get('/forgot-password', [AuthController::class, 'forgotForm'])->name('password.request');

});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'idle', 'role:admin,superadmin,bendahara,sekretaris'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
     Route::get('/accounts', [AdminAccountController::class, 'index'])->middleware('role:superadmin')->name('accounts.index');
     Route::post('/accounts', [AdminAccountController::class, 'store'])->middleware('role:superadmin')->name('accounts.store');
     Route::patch('/accounts/{user}', [AdminAccountController::class, 'update'])->middleware('role:superadmin')->name('accounts.update');
      Route::get('/pendaftaran/export', [AdminController::class, 'exportPendaftaran'])->name('pendaftaran.export');
      Route::get('/pendaftaran/export/pdf', [AdminController::class, 'exportPendaftaranPdf'])->name('pendaftaran.export.pdf');
      Route::get('/pembayaran/export', [AdminController::class, 'exportPembayaran'])->name('pembayaran.export');
     Route::get('/alumni', [AdminAlumniController::class, 'index'])->name('alumni.index');
       Route::get('/alumni/{alumni}', [AdminAlumniController::class, 'show'])->name('alumni.show');
       Route::delete('/alumni/{alumni}', [AdminAlumniController::class, 'destroy'])->name('alumni.destroy');
       Route::patch('/alumni/{alumni}/testimoni', [AdminAlumniController::class, 'updateTestimonial'])->name('alumni.testimonial.update');
     Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
     Route::get('/laporan/penghuni-kamar', [AdminController::class, 'laporanHunian'])->name('laporan.hunian');
     Route::get('/laporan/penghuni-kamar/pdf', [AdminController::class, 'exportHunianPdf'])->name('laporan.hunian.pdf');
    Route::get('/laporan-kerusakan', [AdminLaporanKerusakanController::class, 'index'])->name('kerusakan.index');
    Route::post('/laporan-kerusakan', [AdminLaporanKerusakanController::class, 'store'])->name('kerusakan.store');
    Route::get('/laporan-kerusakan/export/pdf', [AdminLaporanKerusakanController::class, 'exportPdf'])->name('kerusakan.export.pdf');
    Route::delete('/laporan-kerusakan/{laporanKerusakan}', [AdminLaporanKerusakanController::class, 'destroy'])->name('kerusakan.destroy');
    Route::get('/laporan/export/pdf', [AdminController::class, 'exportLaporanPdf'])->name('laporan.export.pdf');
    Route::get('/laporan/export/excel', [AdminController::class, 'exportLaporan'])->name('laporan.export.excel');
     Route::get('/keuangan', [AdminKeuanganController::class, 'index'])->name('keuangan.index');
     Route::get('/keuangan/hapus', [AdminKeuanganController::class, 'deleteIndex'])->name('keuangan.delete.index');
     Route::post('/keuangan', [AdminKeuanganController::class, 'store'])->name('keuangan.store');
    Route::delete('/keuangan/{laporanKeuangan}', [AdminKeuanganController::class, 'destroy'])->name('keuangan.destroy');
      Route::get('/pengaturan', [AdminContentController::class, 'index'])->middleware('role:admin,superadmin')->name('pengaturan.index');
      Route::post('/pengaturan', [AdminContentController::class, 'settings'])->middleware('role:admin,superadmin')->name('pengaturan.settings');
      Route::post('/pengumuman', [AdminContentController::class, 'announcement'])->middleware('role:admin,superadmin')->name('pengumuman.store');
      Route::delete('/pengumuman/{pengumuman}', [AdminContentController::class, 'destroyAnnouncement'])->middleware('role:admin,superadmin')->name('pengumuman.destroy');
     Route::get('/gallery', [AdminGalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery', [AdminGalleryController::class, 'store'])->name('gallery.store');
    Route::patch('/gallery/{gallery}', [AdminGalleryController::class, 'update'])->name('gallery.update');
     Route::delete('/gallery/{gallery}', [AdminGalleryController::class, 'destroy'])->name('gallery.destroy');
     Route::get('/foto-fasilitas', [AdminFotoFasilitasController::class, 'index'])->name('foto-fasilitas.index');
     Route::post('/foto-fasilitas', [AdminFotoFasilitasController::class, 'store'])->name('foto-fasilitas.store');
     Route::patch('/foto-fasilitas/{fotoFasilitas}', [AdminFotoFasilitasController::class, 'update'])->name('foto-fasilitas.update');
     Route::delete('/foto-fasilitas/{fotoFasilitas}', [AdminFotoFasilitasController::class, 'destroy'])->name('foto-fasilitas.destroy');
     Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran', [AdminPembayaranController::class, 'store'])->name('pembayaran.store');
     Route::patch('/pembayaran/{pembayaran}', [AdminPembayaranController::class, 'update'])->name('pembayaran.update');
     Route::post('/pembayaran/pengaturan', [AdminPembayaranController::class, 'updateSettings'])->name('pembayaran.settings');
     Route::get('/pembayaran/{pembayaran}/kuitansi', [AdminPembayaranController::class, 'receipt'])->name('pembayaran.receipt');
    Route::get('/kamar', [AdminKamarController::class, 'index'])->name('kamar.index');
     Route::post('/kamar', [AdminKamarController::class, 'store'])->name('kamar.store');
     Route::patch('/kamar/{kamar}', [AdminKamarController::class, 'update'])->name('kamar.update');
     Route::patch('/kamar/penghuni/{user}/pindah', [AdminKamarController::class, 'moveResident'])->name('kamar.penghuni.move');
     Route::patch('/kamar/penghuni/{user}/keluarkan', [AdminKamarController::class, 'removeResident'])->name('kamar.penghuni.remove');
     Route::get('/penghuni/export', [AdminPenghuniController::class, 'export'])->name('penghuni.export');
     Route::get('/penghuni/export/pdf', [AdminPenghuniController::class, 'exportPdf'])->name('penghuni.export.pdf');
     Route::post('/penghuni/import', [AdminPenghuniController::class, 'import'])->name('penghuni.import');
     Route::patch('/penghuni/{user}/status', [AdminPenghuniController::class, 'updateStatus'])->name('penghuni.status');
     Route::resource('/penghuni', AdminPenghuniController::class)->only(['index', 'show', 'store', 'update', 'destroy'])->parameters(['penghuni' => 'user']);
    Route::get('/pendaftaran', [AdminPendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::get('/pendaftaran/{pendaftaran}', [AdminPendaftaranController::class, 'show'])->name('pendaftaran.show');
    Route::patch('/pendaftaran/{pendaftaran}', [AdminPendaftaranController::class, 'update'])->name('pendaftaran.update');
});

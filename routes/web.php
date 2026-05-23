<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\FamilyCardController;
use App\Http\Controllers\LetterRequestController;
use App\Http\Controllers\NewsCategoryController;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\SocialAssistanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VillageProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store'])->name('login.store');
    Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

Route::get('verifikasi-surat/{code}', [LetterRequestController::class, 'verify'])->name('letters.verify');
Route::get('berita-desa', [NewsPostController::class, 'publicIndex'])->name('news.public.index');
Route::get('berita-desa/{post:slug}', [NewsPostController::class, 'publicShow'])->name('news.public.show');
Route::post('berita-desa/{post:slug}/komentar', [NewsPostController::class, 'comment'])->name('news.public.comment');

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/', DashboardController::class)->middleware('permission:dashboard.view')->name('dashboard');
    Route::get('dashboard/statistik', [DashboardController::class, 'data'])->middleware('permission:dashboard.view')->name('dashboard.statistics');
    Route::get('agenda', [DashboardController::class, 'agenda'])->middleware('permission:dashboard.view')->name('agenda.index');
    Route::get('pencarian', [DashboardController::class, 'search'])->middleware('permission:dashboard.view')->name('global-search');
    Route::get('portal-warga', [DashboardController::class, 'citizenPortal'])->middleware('permission:complaints.create')->name('citizen-portal');

    Route::middleware('permission:reports.view')->group(function () {
        Route::get('laporan', [ReportController::class, 'index'])->name('reports.index');
        Route::get('laporan/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::get('laporan/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    });

    Route::get('penduduk', [ResidentController::class, 'index'])->middleware('permission:penduduk.view')->name('residents.index');
    Route::get('penduduk/export/excel', [ResidentController::class, 'exportExcel'])->middleware('permission:penduduk.view')->name('residents.export.excel');
    Route::get('penduduk/export/pdf', [ResidentController::class, 'exportPdf'])->middleware('permission:penduduk.view')->name('residents.export.pdf');
    Route::middleware('permission:penduduk.manage')->group(function () {
        Route::get('penduduk/create', [ResidentController::class, 'create'])->name('residents.create');
        Route::post('penduduk', [ResidentController::class, 'store'])->name('residents.store');
        Route::post('penduduk/import', [ResidentController::class, 'import'])->name('residents.import');
        Route::get('penduduk/{penduduk}/edit', [ResidentController::class, 'edit'])->name('residents.edit');
        Route::put('penduduk/{penduduk}', [ResidentController::class, 'update'])->name('residents.update');
        Route::patch('penduduk/{penduduk}', [ResidentController::class, 'update']);
    });
    Route::get('penduduk/{penduduk}', [ResidentController::class, 'show'])->middleware('permission:penduduk.view')->name('residents.show');
    Route::delete('penduduk/{penduduk}', [ResidentController::class, 'destroy'])->middleware('permission:penduduk.delete')->name('residents.destroy');

    Route::get('kartu-keluarga', [FamilyCardController::class, 'index'])->middleware('permission:kk.view')->name('family-cards.index');
    Route::middleware('permission:kk.manage')->group(function () {
        Route::get('kartu-keluarga/create', [FamilyCardController::class, 'create'])->name('family-cards.create');
        Route::post('kartu-keluarga', [FamilyCardController::class, 'store'])->name('family-cards.store');
        Route::post('kartu-keluarga/sinkron', [FamilyCardController::class, 'sync'])->name('family-cards.sync');
        Route::get('kartu-keluarga/{familyCard}/edit', [FamilyCardController::class, 'edit'])->name('family-cards.edit');
        Route::put('kartu-keluarga/{familyCard}', [FamilyCardController::class, 'update'])->name('family-cards.update');
        Route::delete('kartu-keluarga/{familyCard}', [FamilyCardController::class, 'destroy'])->name('family-cards.destroy');
    });
    Route::get('kartu-keluarga/{familyCard}', [FamilyCardController::class, 'show'])->middleware('permission:kk.view')->name('family-cards.show');

    Route::get('layanan-surat/{letter}/cetak', [LetterRequestController::class, 'print'])->middleware('permission:surat.print')->name('letters.print');
    Route::get('layanan-surat/{letter}/pdf', [LetterRequestController::class, 'pdf'])->middleware('permission:surat.print')->name('letters.pdf');
    Route::get('layanan-surat', [LetterRequestController::class, 'index'])->middleware('permission:surat.view')->name('letters.index');
    Route::middleware('permission:surat.manage')->group(function () {
        Route::get('layanan-surat/create', [LetterRequestController::class, 'create'])->name('letters.create');
        Route::post('layanan-surat', [LetterRequestController::class, 'store'])->name('letters.store');
        Route::patch('layanan-surat/{letter}/setujui', [LetterRequestController::class, 'approve'])->name('letters.approve');
        Route::patch('layanan-surat/{letter}/tolak', [LetterRequestController::class, 'reject'])->name('letters.reject');
    });
    Route::get('layanan-surat/{layanan_surat}', [LetterRequestController::class, 'show'])->middleware('permission:surat.view')->name('letters.show');

    Route::middleware('permission:berita.manage')->group(function () {
        Route::resource('berita', NewsPostController::class)->parameters(['berita' => 'news'])->names('news');
        Route::post('kategori-berita', [NewsCategoryController::class, 'store'])->name('news-categories.store');
        Route::patch('komentar-berita/{comment}/setujui', [NewsPostController::class, 'approveComment'])->name('news-comments.approve');
        Route::patch('komentar-berita/{comment}/tolak', [NewsPostController::class, 'rejectComment'])->name('news-comments.reject');
    });

    Route::get('bantuan-sosial', [SocialAssistanceController::class, 'index'])->middleware('permission:bantuan.view')->name('social-assistance.index');
    Route::middleware('permission:bantuan.manage')->group(function () {
        Route::get('bantuan-sosial/create', [SocialAssistanceController::class, 'create'])->name('social-assistance.create');
        Route::post('bantuan-sosial', [SocialAssistanceController::class, 'store'])->name('social-assistance.store');
        Route::post('kategori-bantuan', [SocialAssistanceController::class, 'storeCategory'])->name('social-assistance-categories.store');
        Route::get('bantuan-sosial/{socialAssistance}/edit', [SocialAssistanceController::class, 'edit'])->name('social-assistance.edit');
        Route::put('bantuan-sosial/{socialAssistance}', [SocialAssistanceController::class, 'update'])->name('social-assistance.update');
        Route::delete('bantuan-sosial/{socialAssistance}', [SocialAssistanceController::class, 'destroy'])->name('social-assistance.destroy');
        Route::post('bantuan-sosial/{socialAssistance}/riwayat', [SocialAssistanceController::class, 'storeHistory'])->name('social-assistance.histories.store');
    });
    Route::get('bantuan-sosial/{socialAssistance}', [SocialAssistanceController::class, 'show'])->middleware('permission:bantuan.view')->name('social-assistance.show');

    Route::get('pengaduan', [ComplaintController::class, 'index'])->middleware('permission:complaints.view')->name('complaints.index');
    Route::middleware('permission:complaints.create')->group(function () {
        Route::get('pengaduan/create', [ComplaintController::class, 'create'])->name('complaints.create');
        Route::post('pengaduan', [ComplaintController::class, 'store'])->name('complaints.store');
    });
    Route::patch('pengaduan/{complaint}/balasan', [ComplaintController::class, 'updateResponse'])->middleware('permission:complaints.manage')->name('complaints.response.update');
    Route::get('pengaduan/{complaint}', [ComplaintController::class, 'show'])->middleware('permission:complaints.view')->name('complaints.show');

    Route::resource('users', UserController::class)
        ->except(['show'])
        ->middleware('permission:users.manage');

    Route::middleware('permission:settings.manage')->group(function () {
        Route::get('pengaturan/profil-desa', [VillageProfileController::class, 'edit'])->name('settings.village-profile.edit');
        Route::put('pengaturan/profil-desa', [VillageProfileController::class, 'update'])->name('settings.village-profile.update');
    });

    Route::get('audit-log', [AuditLogController::class, 'index'])
        ->middleware('permission:audit.view')
        ->name('audit-logs.index');
});

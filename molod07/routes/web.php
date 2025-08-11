<?php

use App\Http\Controllers\Admin\AdministratorsController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\YouthController;
use App\Http\Controllers\Auth\RegisterYouthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Profiles\AdminProfileController;
use App\Http\Controllers\Profiles\YouthProfileController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('pages.main');
})->name("main");

Route::middleware('guest')->group(function () {
    Route::get('/register/youth', [RegisterYouthController::class, 'show'])->name('youth.reg');
    Route::post('/register/youth', [RegisterYouthController::class, 'register'])->name("youth.reg.post");
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'view'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

//Youth routes
Route::middleware(['auth', 'role:youth'])->group(function () {
    Route::get('/profile/youth', [YouthProfileController::class, 'show'])->name('youth.profile');
    Route::post('/profile/youth/post', [YouthProfileController::class, 'updateProfile'])->name('youth.profile.post');
});

//Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profile/admin', [AdminProfileController::class, 'show'])->name('admin.profile');
    Route::post('/profile/admin/post', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.post');

    //Administrators
    Route::get('admin/manage/administrators', [AdministratorsController::class, 'show'])->name('admin.manage.administrators');
    Route::get('admin/manage/administrators/create', [AdministratorsController::class, 'createShow'])->name('admin.manage.administrators.create');
    Route::post('admin/manage/administrators/create/post', [AdministratorsController::class, 'createAdminPost'])->name('admin.manage.administrators.create.post');
    Route::post('admin/manage/administrators/remove', [AdministratorsController::class, 'remove'])->name('admin.manage.administrators.remove');
    Route::post('admin/manage/administrators/block', [AdministratorsController::class, 'block'])->name('admin.manage.administrators.block');
    Route::post('admin/manage/administrators/unblock', [AdministratorsController::class, 'unblock'])->name('admin.manage.administrators.unblock');

    //Youth
    Route::get('admin/manage/youth', [YouthController::class, 'show'])->name('admin.manage.youth');
    Route::post('admin/manage/youth/remove', [YouthController::class, 'remove'])->name('admin.manage.youth.remove');
    Route::post('admin/manage/youth/block', [YouthController::class, 'block'])->name('admin.manage.youth.block');
    Route::post('admin/manage/youth/unblock', [YouthController::class, 'unblock'])->name('admin.manage.youth.unblock');

    //Support
    Route::get('admin/support', [SupportController::class, 'show'])->name('admin.support');
});

//Profile redirect
Route::middleware('auth')->get('/profile', function () {
    $user = Auth::user();
    return match ($user->role) {
        'youth' => redirect()->route('youth.profile'),
        'admin' => redirect()->route('admin.profile'),
        'partner' => redirect()->route('partner.profile'),
        default => abort(403),
    };
})->name('profile');

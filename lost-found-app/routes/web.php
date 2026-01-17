<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController; // อย่าลืมบรรทัดนี้

/*
|--------------------------------------------------------------------------
| Web Routes (ฉบับสมบูรณ์)
|--------------------------------------------------------------------------
*/

// หน้าแรก (Home)
Route::get('/', [ItemController::class, 'index'])->name('home');

// ดูรายละเอียดโพสต์
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

// ดูโปรไฟล์ผู้ใช้ (New Feature ✨)
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');

// โซนคนยังไม่ล็อกอิน
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// โซนสมาชิก (ต้องล็อกอิน)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // เปลี่ยนรหัสผ่าน (แก้ให้เข้าได้แล้ว ✅)
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.update');
    
    // จัดการโพสต์
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    // Chat
    Route::get('/chat/start/{item}', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/chats', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chats/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chats/{id}/send', [ChatController::class, 'send'])->name('chat.send');
    
    // Report
    Route::post('/items/{item}/report', [ReportController::class, 'store'])->name('reports.store');
});

// โซน Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('delete_user');
    Route::get('/items', [AdminController::class, 'items'])->name('items');
    Route::delete('/items/{id}', [AdminController::class, 'deleteItem'])->name('delete_item');
    Route::post('/reports/{id}/dismiss', [AdminController::class, 'dismissReport'])->name('dismiss_report');
});

// Route ลับสำหรับตั้งค่า
Route::get('/fix-images', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');
    if (!file_exists($link)) { symlink($target, $link); return '✅ Link created'; }
    return '✅ Link exists';
});
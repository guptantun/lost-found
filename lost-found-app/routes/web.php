<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// นำเข้า Controller ทั้งหมด
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController; // 👈 เพิ่มอันนี้

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ----------------------------------------------------------------
// 1. หน้าสาธารณะ (Public)
// ----------------------------------------------------------------
Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

// 🔥 เส้นทางดูโปรไฟล์ผู้ใช้
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');

// ----------------------------------------------------------------
// 2. สำหรับคนยังไม่ล็อกอิน (Guest Only)
// ----------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// ----------------------------------------------------------------
// 3. สำหรับสมาชิก (Member Zone)
// ----------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // เปลี่ยนรหัสผ่าน
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.update');
    
    // จัดการประกาศ
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    // Chat & Report
    Route::get('/chat/start/{item}', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/chats', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chats/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chats/{id}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/items/{item}/report', [ReportController::class, 'store'])->name('reports.store');
});

// ----------------------------------------------------------------
// 4. ผู้ดูแลระบบ (Admin Zone)
// ----------------------------------------------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('delete_user');
    Route::get('/items', [AdminController::class, 'items'])->name('items');
    Route::delete('/items/{id}', [AdminController::class, 'deleteItem'])->name('delete_item');
    Route::post('/reports/{id}/dismiss', [AdminController::class, 'dismissReport'])->name('dismiss_report');
});

// ----------------------------------------------------------------
// 5. Route ลับ (ตั้งค่า Admin / แก้รูป)
// ----------------------------------------------------------------
Route::get('/setup-admin/{email}', function ($email) {
    $user = \App\Models\User::where('email', $email)->first();
    if (!$user) return '❌ ไม่พบผู้ใช้';
    $user->is_admin = 1; 
    $user->save();
    return '✅ ตั้งค่า Admin ให้ ' . $email . ' สำเร็จ (Logout/Login ใหม่ด้วย)';
});

Route::get('/fix-images', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');
    if (file_exists($link)) return '✅ มี Symlink แล้ว';
    symlink($target, $link);
    return '✅ สร้าง Symlink สำเร็จ';
});
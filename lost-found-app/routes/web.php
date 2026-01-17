<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// นำเข้า Controller ทั้งหมด
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\ProfileController; // <--- สำคัญมาก! ต้องมีบรรทัดนี้

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// 1. โซนสาธารณะ (เข้าได้ทุกคน / ดูโปรไฟล์คนอื่นได้)
// ====================================================

// หน้าแรก
Route::get('/', [ItemController::class, 'index'])->name('home');

// ดูรายละเอียดของหาย
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

// *** ดูโปรไฟล์ผู้ใช้ (Public Profile) ***
// เส้นทางนี้แหละครับที่จะทำให้กดชื่อแล้วไปหน้าโปรไฟล์ได้
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');


// ====================================================
// 2. โซน Guest (คนยังไม่ล็อกอิน)
// ====================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});


// ====================================================
// 3. โซนสมาชิก (ต้องล็อกอินก่อนถึงเข้าได้)
// ====================================================
Route::middleware('auth')->group(function () {
    // ออกจากระบบ
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // --- เปลี่ยนรหัสผ่าน (ตั้งค่าส่วนตัว) ---
    Route::get('/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('password.update');
    
    // จัดการประกาศ (Items)
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    // แชท
    Route::get('/chat/start/{item}', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/chats', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chats/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chats/{id}/send', [ChatController::class, 'send'])->name('chat.send');

    // แจ้งลบโพสต์
    Route::post('/items/{item}/report', [ReportController::class, 'store'])->name('reports.store');
});


// ====================================================
// 4. โซน Admin (ผู้ดูแลระบบ)
// ====================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('delete_user');
    Route::get('/items', [AdminController::class, 'items'])->name('items');
    Route::delete('/items/{id}', [AdminController::class, 'deleteItem'])->name('delete_item');
    Route::post('/reports/{id}/dismiss', [AdminController::class, 'dismissReport'])->name('dismiss_report');
});

// Route พิเศษสำหรับแก้ปัญหาภาพไม่ขึ้น
Route::get('/fix-images', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');
    if (file_exists($link)) return '✅ Storage Link มีอยู่แล้ว';
    symlink($target, $link);
    return '✅ สร้าง Storage Link สำเร็จ! รูปภาพจะแสดงแล้วครับ';
});
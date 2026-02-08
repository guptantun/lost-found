<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================================================================
// 1. โซนสาธารณะ (Public) - เข้าถึงได้ทุกคน
// ================================================================

// หน้าแรก (รายการของหาย/เจอของ)
Route::get('/', [ItemController::class, 'index'])->name('home');

// ดูรายละเอียดประกาศ
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

// ดูโปรไฟล์ผู้ใช้ (วางไว้ตรงนี้เพื่อให้กดดูได้ทุกคนโดยไม่ต้องล็อกอิน)
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');


// ================================================================
// 2. โซน Guest (สำหรับผู้ที่ยังไม่ล็อกอิน)
// ================================================================
Route::middleware('guest')->group(function () {
    // ล็อกอิน
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // สมัครสมาชิก
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});


// ================================================================
// 3. โซนสมาชิก (ต้อง Login ก่อนถึงจะเข้าได้)
// ================================================================
Route::middleware('auth')->group(function () {
    
    // ออกจากระบบ
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- จัดการโปรไฟล์ส่วนตัว (แก้ไขส่วนนี้เพื่อแก้ Error Route not defined) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- เปลี่ยนรหัสผ่าน ---
    Route::get('/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // --- จัดการประกาศ (CRUD) ---
    // สร้างประกาศใหม่
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    
    // หน้าแก้ไขประกาศ (เฉพาะเจ้าของ)
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    
    // ลบประกาศ
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    // --- รายงานโพสต์ (Report) ---
    Route::post('/items/{item}/report', [ReportController::class, 'store'])->name('reports.store');

    // --- ระบบแชท ---
    Route::get('/chat/start/{item}', [ChatController::class, 'start'])->name('chat.start'); // เริ่มแชทจากประกาศ
    Route::get('/chats', [ChatController::class, 'index'])->name('chat.index');             // รายการแชททั้งหมด
    Route::get('/chats/{id}', [ChatController::class, 'show'])->name('chat.show');          // ห้องแชท
    Route::post('/chats/{id}/send', [ChatController::class, 'send'])->name('chat.send');    // ส่งข้อความ
});


// ================================================================
// 4. โซน Admin (สำหรับผู้ดูแลระบบ)
// ================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // จัดการผู้ใช้
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('delete_user');
    Route::post('/users/{id}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('toggle_admin');
    
    // จัดการประกาศ
    Route::get('/items', [AdminController::class, 'items'])->name('items');
    Route::delete('/items/{id}', [AdminController::class, 'deleteItem'])->name('delete_item');
    
    // จัดการรายงาน (ใช้ POST เพราะใน Blade ใช้ฟอร์ม #postForm)
    Route::post('/reports/{id}/dismiss', [AdminController::class, 'dismissReport'])->name('dismiss_report');
});


// ================================================================
// Route พิเศษสำหรับแก้ไขรูปไม่ขึ้น (Utility)
// ================================================================
Route::get('/fix-images', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');
    
    if (file_exists($link)) {
        return '✅ Storage Link มีอยู่แล้ว (ไม่ต้องทำอะไร)';
    }
    
    try {
        symlink($target, $link);
        return '✅ สร้าง Storage Link สำเร็จ! (รูปน่าจะขึ้นแล้ว)';
    } catch (\Exception $e) {
        return '❌ เกิดข้อผิดพลาด: ' . $e->getMessage();
    }
});
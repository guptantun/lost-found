<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// นำเข้า Controller ทั้งหมดไว้ตรงนี้ทีเดียว
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ----------------------------------------------------------------
// 1. หน้าสาธารณะ (Public) - เข้าได้ทุกคนไม่ต้องล็อกอิน
// ----------------------------------------------------------------
Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

// ดูโปรไฟล์ผู้ใช้ (เปิดสาธารณะเพื่อให้คนติดต่อคืนของได้)
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
// 3. สำหรับสมาชิก (Member Zone) - ต้องล็อกอินก่อน
// ----------------------------------------------------------------
Route::middleware('auth')->group(function () {
    // ออกจากระบบ
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // --- เปลี่ยนรหัสผ่าน ---
    // ใช้ ProfileController ตามที่อัปเดตล่าสุด
    Route::get('/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('password.update');
    
    // จัดการประกาศ (Items)
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy'); // สมาชิกทั่วไปลบของตัวเอง

    // ระบบแชท (Chat System)
    Route::get('/chat/start/{item}', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/chats', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chats/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chats/{id}/send', [ChatController::class, 'send'])->name('chat.send'); // เปลี่ยนชื่อเมธอดให้ตรงกับ Controller ล่าสุด (send หรือ sendMessage)

    // แจ้งลบโพสต์ (Report)
    Route::post('/items/{item}/report', [ReportController::class, 'store'])->name('reports.store');
});


// ----------------------------------------------------------------
// 4. ผู้ดูแลระบบ (Admin Zone) - ระดับประเทศ 🏛️
// ----------------------------------------------------------------
// Group นี้มี Prefix 'admin' และ Name 'admin.' ให้แล้ว
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // 4.1 หน้า Dashboard ภาพรวม
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // 4.2 จัดการ Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('delete_user');
    
    // 4.3 จัดการ Items (ประกาศ)
    Route::get('/items', [AdminController::class, 'items'])->name('items');
    Route::delete('/items/{id}', [AdminController::class, 'deleteItem'])->name('delete_item');

    // 4.4 Actions พื้นฐาน (จัดการ Report)
    Route::post('/reports/{id}/dismiss', [AdminController::class, 'dismissReport'])->name('dismiss_report');
});


// ----------------------------------------------------------------
// 🔥 5. Route ลับสำหรับตั้งค่า Admin (แบบระบุอีเมลได้เอง)
// ----------------------------------------------------------------
Route::get('/setup-admin/{email}', function ($email) {
    
    // ค้นหา User ตามอีเมลที่พิมพ์มาใน URL
    $user = \App\Models\User::where('email', $email)->first();

    if (!$user) {
        return '❌ ไม่พบผู้ใช้: ' . $email . ' (กรุณาสมัครสมาชิกก่อนนะครับ)';
    }

    // ตั้งค่า Admin
    $user->is_admin = 1; 
    
    $user->save();

    return '✅ สำเร็จ! ตั้งค่าให้ ' . $email . ' เป็น Admin เรียบร้อยแล้ว (Logout/Login ใหม่ด้วยนะครับ)';
});


// ----------------------------------------------------------------
// 🛠️ 6. Route พิเศษสำหรับแก้ปัญหา "รูปไม่ขึ้น" (กดแล้วจะสร้าง Storage Link ให้)
// ----------------------------------------------------------------
Route::get('/fix-images', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');

    // เช็คว่ามีโฟลเดอร์ public/storage หรือยัง
    if (file_exists($link)) {
        return '✅ ทางเชื่อม (Symlink) มีอยู่แล้วครับ! <br> (ถ้ายังไม่เห็นรูป แปลว่ารูปเก่าโดนลบไปตอน Deploy ครับ ให้ลองอัปโหลดรูปใหม่ดู)';
    }

    // สั่งสร้างทางเชื่อม
    try {
        symlink($target, $link);
        return '✅ สร้างทางเชื่อมรูปภาพ (Storage Link) สำเร็จแล้ว! <br> ลองกลับไปรีเฟรชหน้าเว็บดู หรือลองอัปโหลดรูปใหม่ครับ';
    } catch (\Exception $e) {
        return '❌ เกิดข้อผิดพลาด: ' . $e->getMessage();
    }
});
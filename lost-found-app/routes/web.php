<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// นำเข้า Controller ทั้งหมด
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController; 

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
    
    // จัดการประกาศ (Items)
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy'); // สมาชิกทั่วไปลบของตัวเอง

    // ระบบแชท (Chat System)
    Route::get('/chat/start/{item}', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/chats', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chats/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chats/{id}/send', [ChatController::class, 'send'])->name('chat.send');

    // แจ้งลบโพสต์ (Report)
    Route::post('/items/{item}/report', [ReportController::class, 'store'])->name('reports.store');
});

// ----------------------------------------------------------------
// 4. ผู้ดูแลระบบ (Admin Zone) - ระดับประเทศ 🏛️
// ----------------------------------------------------------------
// Group นี้มี Prefix 'admin' และ Name 'admin.' ให้แล้ว
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // 4.1 หน้า Dashboard ภาพรวม
    // URL: /admin
    // Route Name: admin.dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // 4.2 จัดการ Users
    // URL: /admin/users
    // Route Name: admin.users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    
    // URL: /admin/users/{id} (Method: DELETE)
    // Route Name: admin.delete_user
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('delete_user');
    
    // 4.3 จัดการ Items (ประกาศ)
    // URL: /admin/items
    // Route Name: admin.items
    Route::get('/items', [AdminController::class, 'items'])->name('items');
    
    // URL: /admin/items/{id} (Method: DELETE)
    // Route Name: admin.delete_item
    Route::delete('/items/{id}', [AdminController::class, 'deleteItem'])->name('delete_item');

    // 4.4 Actions พื้นฐาน (จัดการ Report)
    // URL: /admin/reports/{id}/dismiss
    // Route Name: admin.dismiss_report
    Route::post('/reports/{id}/dismiss', [AdminController::class, 'dismissReport'])->name('dismiss_report');
});

// ----------------------------------------------------------------
// 🔥 5. Route ลับสำหรับตั้งค่า Admin (ใช้เสร็จแล้วแนะนำให้ลบออก)
// ----------------------------------------------------------------
Route::get('/setup-admin', function () {
    // 🔴 1. แก้เป็นอีเมลของคุณที่สมัครสมาชิกไว้แล้ว ตรงนี้! 👇
    $email = 'caption.naktai@gmail.com'; 

    $user = \App\Models\User::where('email', $email)->first();

    if (!$user) {
        return 'ไม่พบ User อีเมล: ' . $email . ' (กรุณาสมัครสมาชิกที่หน้าเว็บก่อนครับ)';
    }

    // 🔴 2. ตั้งค่า Admin 
    // (ส่วนใหญ่จะเป็น is_admin แต่ถ้า database คุณใช้ชื่ออื่น เช่น role หรือ type ให้แก้ตรงนี้)
    $user->is_admin = 1; 
    // $user->role = 'admin'; // <--- ถ้าใช้ role ให้เปิดบรรทัดนี้แทน
    // $user->type = 'admin'; // <--- ถ้าใช้ type ให้เปิดบรรทัดนี้แทน

    $user->save();

    return '✅ เรียบร้อย! ตั้งค่าให้ ' . $email . ' เป็น Admin แล้ว กรุณา Logout แล้ว Login ใหม่ครับ';
});
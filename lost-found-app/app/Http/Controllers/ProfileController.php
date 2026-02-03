<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;

class ProfileController extends Controller
{
    // แสดงหน้าโปรไฟล์ (Public)
    public function show($id)
    {
        $user = User::findOrFail($id);
        // ส่ง $user ไปที่ View (Items จะถูกเรียกผ่าน $user->items ใน View เอง)
        return view('profile.show', compact('user'));
    }

    // แสดงฟอร์มเปลี่ยนรหัส
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    // อัปเดตรหัสผ่าน (แก้เป็นขั้นต่ำ 4 ตัวตรงนี้)
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:4|confirmed', // <--- แก้ตรงนี้
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง']);
        }

        User::where('id', Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว!');
    }
    // --- ส่วนที่เพิ่มใหม่: สำหรับแก้ไขข้อมูลส่วนตัว (Edit Profile) ---

    // 1. แสดงหน้าฟอร์มแก้ไขข้อมูล
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    // 2. บันทึกข้อมูลที่แก้ไข (ชื่อ, เบอร์, Bio, Line)
    public function update(Request $request)
    {
        // ตรวจสอบความถูกต้องของข้อมูล (Validation)
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.Auth::id()],
            'phone' => ['nullable', 'string', 'max:20'],
            'line_id' => ['nullable', 'string', 'max:50'],
            'facebook' => ['nullable', 'url'], // ต้องเป็นลิงก์ (http://...)
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        // ดึง User ปัจจุบันมาแก้ไข
        $user = Auth::user();

        // ใส่ข้อมูลใหม่ลงไป
        $user->fill($validated);

        // ถ้ามีการแก้ Email ให้เคลียร์สถานะ verify (เผื่อไว้)
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // กลับไปหน้าเดิมพร้อมข้อความแจ้งเตือน
        return back()->with('success', 'บันทึกข้อมูลโปรไฟล์เรียบร้อยแล้ว!');
    }
}
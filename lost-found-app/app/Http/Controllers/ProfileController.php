<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;

class ProfileController extends Controller
{
    // ------------------------------------------
    // ส่วนที่ 1: แสดงหน้าโปรไฟล์ (Public Profile)
    // ------------------------------------------
    public function show($id)
    {
        // 1. หา User ตาม ID (ถ้าไม่เจอให้ Error 404)
        $user = User::findOrFail($id);
        
        // 2. ดึงประกาศทั้งหมดที่ User คนนี้เคยโพสต์
        $items = Item::where('user_id', $id)->orderBy('created_at', 'desc')->get();

        // 3. ส่งข้อมูลไปที่หน้า View
        return view('profile.show', compact('user', 'items'));
    }

    // ------------------------------------------
    // ส่วนที่ 2: เปลี่ยนรหัสผ่าน (Change Password)
    // ------------------------------------------
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:4|confirmed', // <-- ขั้นต่ำ 4 ตัว
        ]);

        // เช็คว่ารหัสเก่าถูกไหม
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง']);
        }

        // อัปเดตรหัสใหม่
        User::where('id', Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว!');
    }
}
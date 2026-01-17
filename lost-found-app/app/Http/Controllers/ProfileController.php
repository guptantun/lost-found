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
}